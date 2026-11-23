<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

class BlogController extends Controller
{
    /**
     * Trang chủ Blog diễn đàn (Median UI)
     */
    public function home(Request $request)
    {
        $query = Post::with(['categoryItem', 'comments'])->where('is_published', true);

        if ($request->filled('cat')) {
            $catSlug = $request->query('cat');
            $query->whereHas('categoryItem', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        $posts = $query->orderBy('id', 'desc')->get();

        // Lấy danh sách các bài viết nổi bật: lấy theo lượt tim nhiều nhất (likes_count), tối đa 5 bài
        $featuredPosts = Post::where('is_published', true)
            ->orderBy('likes_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $trendingPosts = $featuredPosts;

        return view('pages.home', compact('posts', 'featuredPosts', 'trendingPosts'));
    }

    /**
     * Chi tiết bài viết
     */
    public function postDetail($id = null)
    {
        if ($id) {
            $post = Post::with(['categoryItem', 'comments'])->findOrFail($id);
            $post->increment('views_count');
        } else {
            $post = Post::with(['categoryItem', 'comments'])->where('is_published', true)->orderBy('id', 'desc')->first();
            if (!$post) {
                $post = Post::with(['categoryItem', 'comments'])->firstOrFail();
            }
        }

        // Lấy danh sách bài viết nổi bật: lấy theo lượt tim nhiều nhất (ngoại trừ bài đang xem), tối đa 5 bài
        $featuredPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->orderBy('likes_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $trendingPosts = $featuredPosts;

        return view('pages.post-detail', compact('post', 'featuredPosts', 'trendingPosts'));
    }

    /**
     * Gửi bình luận cho bài viết
     */
    public function storeComment(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'content' => 'required|string|max:1000',
            'author_name' => 'nullable|string|max:100',
        ]);

        $post->comments()->create([
            'author_name' => $request->input('author_name', 'Độc giả thành viên') ?: 'Độc giả thành viên',
            'content' => trim($request->input('content')),
            'status' => 'approved',
            'likes_count' => 0,
        ]);

        return redirect()->back()->with('success', 'Bình luận của bạn đã được đăng tải thành công!');
    }

    /**
     * Trang Về tôi (Founder Admin Profile & Triết lý)
     */
    public function about()
    {
        $recentPosts = Post::where('is_published', true)->orderBy('id', 'desc')->take(3)->get();
        return view('pages.about', compact('recentPosts'));
    }

    /**
     * Trang liên hệ & hợp tác
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Tiếp nhận đề xuất / Thư ngỏ hợp tác từ độc giả & doanh nghiệp
     */
    public function submitProposal(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone'           => 'required|string|max:50',
            'company'         => 'nullable|string|max:255',
            'role'            => 'nullable|string|max:255',
            'purpose'         => 'required|string|max:255',
            'subject'         => 'required|string|max:255',
            'message'         => 'required|string|max:5000',
            'file_attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,zip,png,jpg,jpeg',
        ], [
            'name.required'    => 'Vui lòng nhập họ và tên của bạn.',
            'email.required'   => 'Vui lòng cung cấp địa chỉ email hợp lệ.',
            'phone.required'   => 'Vui lòng nhập số điện thoại hoặc Zalo liên hệ.',
            'purpose.required' => 'Vui lòng chọn mục đích kết nối phù hợp.',
            'subject.required' => 'Vui lòng nhập tiêu đề thư ngỏ.',
            'message.required' => 'Vui lòng nhập nội dung đề xuất chi tiết.',
            'file_attachment.max' => 'Tài liệu đính kèm vượt quá giới hạn cho phép (Tối đa 10MB).',
            'file_attachment.mimes' => 'Hệ thống chỉ hỗ trợ file PDF, DOCX, PPTX, ZIP hoặc ảnh.',
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('file_attachment') && $request->file('file_attachment')->isValid()) {
            $file = $request->file('file_attachment');
            $attachmentName = $file->getClientOriginalName();
            $filename = 'proposal_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/proposals');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            $attachmentPath = 'uploads/proposals/' . $filename;
        }

        \App\Models\Proposal::create([
            'name'            => $request->input('name'),
            'email'           => $request->input('email'),
            'phone'           => $request->input('phone'),
            'company'         => $request->input('company'),
            'role'            => $request->input('role'),
            'purpose'         => $request->input('purpose'),
            'subject'         => $request->input('subject'),
            'message'         => $request->input('message'),
            'attachment'      => $attachmentPath,
            'attachment_name' => $attachmentName,
            'status'          => 'pending',
            'ip_address'      => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Thư ngỏ & Đề xuất hợp tác của Quý đối tác đã được gửi thành công! Ban Quản trị KẾT NỐI sẽ phản hồi qua Email hoặc Số điện thoại trong vòng 24 giờ làm việc.');
    }

    /**
     * Thích / Bỏ thích bài viết (Lưu vào Database và Session)
     */
    public function togglePostLike($id)
    {
        $post = Post::findOrFail($id);
        $likedPosts = session()->get('liked_posts', []);

        if (in_array($post->id, $likedPosts)) {
            $post->decrement('likes_count');
            if ($post->likes_count < 0) {
                $post->likes_count = 0;
                $post->save();
            }
            $likedPosts = array_values(array_diff($likedPosts, [$post->id]));
            $isLiked = false;
        } else {
            $post->increment('likes_count');
            $likedPosts[] = $post->id;
            $isLiked = true;
        }

        session()->put('liked_posts', $likedPosts);

        return response()->json([
            'success'     => true,
            'is_liked'    => $isLiked,
            'likes_count' => $post->likes_count,
        ]);
    }

    /**
     * Thích / Bỏ thích bình luận ngoài trang bài viết
     */
    public function toggleCommentLike($id)
    {
        $comment = \App\Models\Comment::findOrFail($id);
        $likedComments = session()->get('liked_comments', []);
        $isAdmin = auth()->check();

        if ($isAdmin) {
            // Nếu là Tác giả/Admin đang đăng nhập thì toggle trạng thái likes_count của tác giả
            $isLiked = $comment->likes_count > 0;
            $comment->likes_count = $isLiked ? 0 : 1;
            $comment->save();
            $userLiked = $comment->likes_count > 0;
            $authorLiked = $comment->likes_count > 0;
        } else {
            // Độc giả thông thường
            if (in_array($comment->id, $likedComments)) {
                $comment->decrement('likes_count');
                if ($comment->likes_count < 0) {
                    $comment->likes_count = 0;
                    $comment->save();
                }
                $likedComments = array_values(array_diff($likedComments, [$comment->id]));
                $userLiked = false;
            } else {
                $comment->increment('likes_count');
                $likedComments[] = $comment->id;
                $userLiked = true;
            }
            session()->put('liked_comments', $likedComments);
            $authorLiked = $comment->likes_count > 0;
        }

        return response()->json([
            'success'      => true,
            'is_liked'     => $userLiked,
            'likes_count'  => $comment->likes_count,
            'author_liked' => $authorLiked,
            'is_admin'     => $isAdmin,
        ]);
    }

    /**
     * Gửi tin nhắn / Thư ngỏ riêng cho Founder Admin từ trang Về tôi
     */
    public function submitQuickMessage(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'name.required'    => 'Vui lòng nhập họ và tên của bạn.',
            'contact.required' => 'Vui lòng cung cấp Email hoặc Số điện thoại / Zalo để Admin liên hệ lại.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn hoặc thư ngỏ.',
        ]);

        $contact = trim($request->input('contact'));
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);

        $email = $isEmail ? $contact : 'Chưa có email';
        $phone = $isEmail ? 'Chưa có SĐT' : $contact;

        // Trích một phần nội dung làm tiêu đề thư ngỏ
        $snippet = \Illuminate\Support\Str::limit(trim($request->input('message')), 65);

        \App\Models\Proposal::create([
            'name'            => $request->input('name'),
            'email'           => $email,
            'phone'           => $phone,
            'company'         => null,
            'role'            => 'Độc giả / Đối tác gửi riêng',
            'purpose'         => 'Tin nhắn / Thư ngỏ riêng cho Founder',
            'subject'         => 'Thư ngỏ riêng: ' . $snippet,
            'message'         => $request->input('message'),
            'attachment'      => null,
            'attachment_name' => null,
            'status'          => 'pending',
            'ip_address'      => $request->ip(),
        ]);

        return redirect()->back()->with('success_quick_message', 'Cảm ơn bạn! Lời nhắn / Thư ngỏ riêng của bạn đã được gửi trực tiếp đến Founder Admin.');
    }
}
