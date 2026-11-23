<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Proposal;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Quản lý bài viết: Thống kê + Lọc tìm kiếm + Bảng danh sách bài viết
     */
    public function posts(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // 'published', 'draft'
        $categoryId = $request->input('category_id');

        $query = Post::with(['categoryItem'])->withCount('comments')->orderBy('id', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $posts = $query->paginate(10)->withQueryString();

        $totalPosts = Post::count();
        $totalComments = Comment::count();
        $totalDrafts = Post::where('is_published', false)->count();
        $totalPublished = Post::where('is_published', true)->count();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('pages.admin', compact(
            'posts',
            'totalPosts',
            'totalComments',
            'totalDrafts',
            'totalPublished',
            'categories',
            'search',
            'status',
            'categoryId'
        ));
    }

    /**
     * Tạo bài viết mới
     */
    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
            'cover_image' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
            'external_link' => 'nullable|string|max:500',
            'external_link_title' => 'nullable|string|max:255',
            'reading_time' => 'nullable|integer',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $title = trim($request->input('title'));
        $slug = Str::slug($title);
        $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $categoryName = null;
        if ($request->filled('category_id')) {
            $cat = Category::find($request->input('category_id'));
            if ($cat) $categoryName = $cat->name;
        }

        // Xử lý upload tối đa 5 ảnh từ máy tính
        $uploadedImages = [];
        $destinationPath = public_path('uploads/posts');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach (array_slice($files, 0, 5) as $file) {
                if ($file && $file->isValid()) {
                    $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $file->move($destinationPath, $fileName);
                    $uploadedImages[] = 'uploads/posts/' . $fileName;
                }
            }
        } elseif ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $file = $request->file('cover_image');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $uploadedImages[] = 'uploads/posts/' . $fileName;
        }

        $coverImagePath = count($uploadedImages) > 0 ? $uploadedImages[0] : 'assets/images/post-tech-ai.jpg';
        $galleryImages = count($uploadedImages) > 0 ? $uploadedImages : [$coverImagePath];

        Post::create([
            'title' => $title,
            'slug' => $slug,
            'category_id' => $request->input('category_id'),
            'category' => $categoryName ?? 'Chiến lược kinh doanh',
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'cover_image' => $coverImagePath,
            'gallery_images' => $galleryImages,
            'external_link' => $request->input('external_link'),
            'external_link_title' => $request->input('external_link_title'),
            'external_link_image' => $request->filled('external_link') ? Post::fetchOgImage($request->input('external_link')) : null,
            'reading_time' => $request->input('reading_time', 5) ?: 5,
            'views_count' => 0,
            'likes_count' => 0,
            'is_published' => $request->has('is_published') ? (bool)$request->input('is_published') : true,
            'is_featured' => $request->has('is_featured') ? (bool)$request->input('is_featured') : false,
        ]);

        return redirect()->route('admin.posts')->with('success', 'Đã tạo bài viết "' . $title . '" thành công với ' . count($galleryImages) . ' ảnh!');
    }

    /**
     * Cập nhật bài viết
     */
    public function updatePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
            'cover_image' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
            'external_link' => 'nullable|string|max:500',
            'external_link_title' => 'nullable|string|max:255',
            'reading_time' => 'nullable|integer',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $title = trim($request->input('title'));
        if ($title !== $post->title) {
            $slug = Str::slug($title);
            $count = Post::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            $post->slug = $slug;
        }

        $categoryName = $post->category;
        if ($request->filled('category_id')) {
            $cat = Category::find($request->input('category_id'));
            if ($cat) $categoryName = $cat->name;
        }

        // Xử lý upload ảnh mới (nếu người dùng có chọn tải ảnh mới)
        $destinationPath = public_path('uploads/posts');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $coverImagePath = $post->cover_image;
        $galleryImages = $post->gallery_images ?? ($post->cover_image ? [$post->cover_image] : []);

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $newImages = [];
            foreach (array_slice($files, 0, 5) as $file) {
                if ($file && $file->isValid()) {
                    $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $file->move($destinationPath, $fileName);
                    $newImages[] = 'uploads/posts/' . $fileName;
                }
            }
            if (count($newImages) > 0) {
                $galleryImages = $newImages;
                $coverImagePath = $newImages[0];
            }
        } elseif ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $file = $request->file('cover_image');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $coverImagePath = 'uploads/posts/' . $fileName;
            $galleryImages = [$coverImagePath];
        }

        $post->update([
            'title' => $title,
            'category_id' => $request->input('category_id'),
            'category' => $categoryName,
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'cover_image' => $coverImagePath,
            'gallery_images' => $galleryImages,
            'external_link' => $request->input('external_link'),
            'external_link_title' => $request->input('external_link_title'),
            'external_link_image' => $request->filled('external_link') ? Post::fetchOgImage($request->input('external_link')) : null,
            'reading_time' => $request->input('reading_time', $post->reading_time) ?: 5,
            'is_published' => $request->has('is_published') ? (bool)$request->input('is_published') : false,
            'is_featured' => $request->has('is_featured') ? (bool)$request->input('is_featured') : false,
        ]);

        return redirect()->route('admin.posts')->with('success', 'Đã cập nhật bài viết "' . $title . '" thành công!');
    }

    /**
     * Đổi nhanh trạng thái Xuất bản / Bản nháp
     */
    public function togglePostStatus($id)
    {
        $post = Post::findOrFail($id);
        $post->is_published = !$post->is_published;
        $post->save();

        $statusText = $post->is_published ? 'Đã xuất bản' : 'Đã chuyển thành bản nháp';
        return redirect()->route('admin.posts')->with('success', 'Bài viết "' . $post->title . '" ' . $statusText . '!');
    }

    /**
     * Xóa bài viết
     */
    public function destroyPost($id)
    {
        $post = Post::findOrFail($id);
        $title = $post->title;
        
        // Xóa các bình luận liên quan đến bài viết
        $post->comments()->delete();
        $post->delete();

        return redirect()->route('admin.posts')->with('success', 'Đã xóa bài viết "' . $title . '" thành công!');
    }

    /**
     * Quản lý bình luận
     */
    public function comments(Request $request)
    {
        $query = Comment::with(['post.categoryItem'])->orderBy('id', 'desc');

        if ($request->input('filter') === 'liked') {
            $query->where('likes_count', '>', 0);
        }

        $comments = $query->paginate(15)->withQueryString();
        $totalComments = Comment::count();
        $likedComments = Comment::where('likes_count', '>', 0)->count();
        $todayComments = Comment::whereDate('created_at', today())->count();

        // Chuyên mục có nhiều bình luận nhất
        $topCategory = Category::withCount(['posts as comments_count' => function ($q) {
            $q->join('comments', 'posts.id', '=', 'comments.post_id');
        }])->orderBy('comments_count', 'desc')->first();

        $topCategoryName = $topCategory?->name ?? 'Chiến lược';
        $topCategoryComments = $topCategory?->comments_count ?? 0;

        // Tự động mở modal chi tiết bình luận khi chuyển từ thông báo sang
        $autoOpenComment = null;
        if ($request->filled('comment_id')) {
            $autoOpenComment = Comment::with(['post'])->find($request->input('comment_id'));
            if ($autoOpenComment && !$autoOpenComment->is_read) {
                $autoOpenComment->is_read = true;
                $autoOpenComment->save();
            }
        }

        return view('pages.manage-comments', compact(
            'comments',
            'totalComments',
            'likedComments',
            'todayComments',
            'topCategoryName',
            'topCategoryComments',
            'autoOpenComment'
        ));
    }

    /**
     * Thích / Bỏ thích bình luận (Admin toggle like)
     */
    public function toggleCommentLike($id)
    {
        $comment = Comment::findOrFail($id);
        $isLiked = $comment->likes_count > 0;
        $comment->likes_count = $isLiked ? 0 : 1;
        $comment->save();

        $totalLiked = Comment::where('likes_count', '>', 0)->count();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_liked' => $comment->likes_count > 0,
                'likes_count' => $comment->likes_count,
                'total_liked' => $totalLiked,
            ]);
        }

        return redirect()->back()->with('success', $comment->likes_count > 0 ? 'Đã thích bình luận!' : 'Đã bỏ thích bình luận.');
    }

    /**
     * Xóa bình luận
     */
    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.comments')->with('success', 'Đã xóa bình luận thành công!');
    }

    /**
     * Cài đặt giao diện & theme toàn trang
     */
    public function theme()
    {
        $settings = Setting::getAllSettings();

        // 1. Lịch sử ảnh bìa Desktop
        $defaultDeskHistory = [
            'assets/images/cover-banner.jpg',
            'assets/images/cover-boardroom.jpg',
            'assets/images/cover-night-skyline.jpg',
            'assets/images/post-tech-ai.jpg'
        ];
        $rawDeskHistory = json_decode($settings['cover_desktop_history'] ?? '[]', true);
        if (!is_array($rawDeskHistory) || count($rawDeskHistory) === 0) {
            $rawDeskHistory = $defaultDeskHistory;
        }
        $currentDesk = $settings['cover_desktop'] ?? 'assets/images/cover-banner.jpg';
        $coverDesktopHistory = array_values(array_filter($rawDeskHistory, function($img) use ($currentDesk) {
            return $img !== $currentDesk;
        }));

        // 2. Lịch sử ảnh bìa Mobile
        $defaultMobHistory = [
            'assets/images/cover-night-skyline.jpg',
            'assets/images/cover-banner.jpg',
            'assets/images/cover-boardroom.jpg',
            'assets/images/post-tech-ai.jpg'
        ];
        $rawMobHistory = json_decode($settings['cover_mobile_history'] ?? '[]', true);
        if (!is_array($rawMobHistory) || count($rawMobHistory) === 0) {
            $rawMobHistory = $defaultMobHistory;
        }
        $currentMob = $settings['cover_mobile'] ?? 'assets/images/cover-night-skyline.jpg';
        $coverMobileHistory = array_values(array_filter($rawMobHistory, function($img) use ($currentMob) {
            return $img !== $currentMob;
        }));

        return view('pages.theme-settings', compact('settings', 'coverDesktopHistory', 'coverMobileHistory'));
    }

    /**
     * Cập nhật cài đặt giao diện toàn trang
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'site_favicon'          => 'nullable|mimes:ico,png,jpg,svg|max:2048',
            'cover_desktop_file'    => 'nullable|image|max:10240',
            'cover_mobile_file'     => 'nullable|image|max:10240',
        ]);

        $uploadDir = public_path('uploads/theme');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Xử lý upload Favicon
        if ($request->hasFile('site_favicon')) {
            $faviconFile = $request->file('site_favicon');
            $favName = 'favicon_' . time() . '_' . Str::random(6) . '.' . $faviconFile->getClientOriginalExtension();
            $faviconFile->move($uploadDir, $favName);
            Setting::set('site_favicon', 'uploads/theme/' . $favName, 'branding');
        }

        // Xử lý upload Cover Desktop & Lưu lịch sử ảnh cũ
        $oldDeskCover = Setting::get('cover_desktop', 'assets/images/cover-banner.jpg');
        $newDeskCover = null;

        if ($request->hasFile('cover_desktop_file')) {
            $coverDeskFile = $request->file('cover_desktop_file');
            $coverDeskName = 'cover_desk_' . time() . '_' . Str::random(6) . '.' . $coverDeskFile->getClientOriginalExtension();
            $coverDeskFile->move($uploadDir, $coverDeskName);
            $newDeskCover = 'uploads/theme/' . $coverDeskName;
            Setting::set('cover_desktop', $newDeskCover, 'cover');
        } elseif ($request->filled('cover_desktop')) {
            $newDeskCover = $request->input('cover_desktop');
            Setting::set('cover_desktop', $newDeskCover, 'cover');
        }

        if ($oldDeskCover && $newDeskCover && $oldDeskCover !== $newDeskCover) {
            $defaultDeskHistory = [
                'assets/images/cover-banner.jpg',
                'assets/images/cover-boardroom.jpg',
                'assets/images/cover-night-skyline.jpg',
                'assets/images/post-tech-ai.jpg'
            ];
            $deskHistory = json_decode(Setting::get('cover_desktop_history', '[]'), true);
            if (!is_array($deskHistory) || count($deskHistory) === 0) {
                $deskHistory = $defaultDeskHistory;
            }
            array_unshift($deskHistory, $oldDeskCover);
            $deskHistory = array_values(array_unique(array_filter($deskHistory)));
            $deskHistory = array_slice($deskHistory, 0, 8);
            Setting::set('cover_desktop_history', json_encode($deskHistory), 'cover');
        }

        // Xử lý upload Cover Mobile & Lưu lịch sử ảnh cũ
        $oldMobCover = Setting::get('cover_mobile', 'assets/images/cover-night-skyline.jpg');
        $newMobCover = null;

        if ($request->hasFile('cover_mobile_file')) {
            $coverMobFile = $request->file('cover_mobile_file');
            $coverMobName = 'cover_mob_' . time() . '_' . Str::random(6) . '.' . $coverMobFile->getClientOriginalExtension();
            $coverMobFile->move($uploadDir, $coverMobName);
            $newMobCover = 'uploads/theme/' . $coverMobName;
            Setting::set('cover_mobile', $newMobCover, 'cover');
        } elseif ($request->filled('cover_mobile')) {
            $newMobCover = $request->input('cover_mobile');
            Setting::set('cover_mobile', $newMobCover, 'cover');
        }

        if ($oldMobCover && $newMobCover && $oldMobCover !== $newMobCover) {
            $defaultMobHistory = [
                'assets/images/cover-night-skyline.jpg',
                'assets/images/cover-banner.jpg',
                'assets/images/cover-boardroom.jpg',
                'assets/images/post-tech-ai.jpg'
            ];
            $mobHistory = json_decode(Setting::get('cover_mobile_history', '[]'), true);
            if (!is_array($mobHistory) || count($mobHistory) === 0) {
                $mobHistory = $defaultMobHistory;
            }
            array_unshift($mobHistory, $oldMobCover);
            $mobHistory = array_values(array_unique(array_filter($mobHistory)));
            $mobHistory = array_slice($mobHistory, 0, 8);
            Setting::set('cover_mobile_history', json_encode($mobHistory), 'cover');
        }

        // Danh sách các fields cần lưu
        $fields = [
            'site_name'             => 'branding',
            'brand_name'            => 'branding',
            'theme_mode'            => 'appearance',
            'theme_preset'          => 'appearance',
            'primary_color'         => 'appearance',
            'accent_color'          => 'appearance',
            'bg_color'              => 'appearance',
            'card_bg_color'         => 'appearance',
            'border_radius'         => 'appearance',
            'cover_height_desktop'  => 'cover',
            'cover_height_mobile'   => 'cover',
            'blog_title'            => 'content',
            'blog_subtitle'         => 'content',
            'footer_about'          => 'content',
            'contact_hotline'       => 'contact',
            'contact_email'         => 'contact',
            'contact_address'       => 'contact',
            'copyright_text'        => 'contact',
            'social_facebook'       => 'social',
            'social_linkedin'       => 'social',
            'social_youtube'        => 'social',
            'social_telegram'       => 'social',
        ];

        foreach ($fields as $key => $group) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key), $group);
            }
        }

        // Tự động đồng bộ brand_name theo site_name để thống nhất nhận diện thương hiệu
        if ($request->filled('site_name')) {
            Setting::set('brand_name', $request->input('site_name'), 'branding');
        }

        if ($request->has('theme_mode')) {
            session()->put('theme_mode', $request->input('theme_mode'));
        }

        Setting::clearCache();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu và áp dụng toàn bộ cài đặt giao diện thành công!'
            ]);
        }

        return redirect()->route('admin.theme')->with('success', 'Đã lưu và áp dụng toàn bộ cài đặt giao diện thành công!');
    }

    /**
     * Chuyển đổi nhanh chế độ Sáng / Tối từ Topbar và lưu ngay vào Database & Session
     */
    public function quickToggleTheme(Request $request)
    {
        $mode = $request->input('mode') === 'dark' ? 'dark' : 'light';
        Setting::set('theme_mode', $mode, 'appearance');
        Setting::clearCache();
        session()->put('theme_mode', $mode);

        return response()->json([
            'success' => true,
            'mode'    => $mode,
        ]);
    }

    /**
     * Khôi phục cài đặt giao diện mặc định
     */
    public function resetTheme()
    {
        $seeder = new \Database\Seeders\SettingSeeder();
        $seeder->run();

        return redirect()->route('admin.theme')->with('success', 'Đã khôi phục toàn bộ giao diện về cấu hình Sapphire Executive mặc định!');
    }

    /**
     * Hồ sơ cá nhân Admin
     */
    public function profile()
    {
        $user = User::first() ?? new User([
            'name'       => 'Admin Khánh',
            'email'      => 'admin@gmail.com',
            'phone'      => '0900 123 456',
            'avatar'     => 'assets/images/admin-khanh.jpg',
            'role_title' => 'Founder & Ban Quản trị',
            'bio'        => 'Chia sẻ chiến lược kinh doanh, quản trị dòng tiền và đầu tư tài chính.',
        ]);

        $defaultHistory = [
            'assets/images/admin-khanh.jpg',
            'assets/images/avatar-admin-2.jpg',
            'assets/images/avatar-admin-3.jpg'
        ];

        $rawHistory = is_array($user->avatar_history) && count($user->avatar_history) > 0 
            ? $user->avatar_history 
            : $defaultHistory;

        // Lọc các ảnh đại diện cũ (không bao gồm ảnh đang hoạt động hiện tại)
        $avatarHistory = array_values(array_filter($rawHistory, function($item) use ($user) {
            return $item !== $user->avatar;
        }));

        if (empty($avatarHistory)) {
            $avatarHistory = array_values(array_filter($defaultHistory, function($item) use ($user) {
                return $item !== $user->avatar;
            }));
        }

        $postsCount = Post::count();
        $commentsCount = Comment::count();
        $settings = Setting::getAllSettings();

        // Danh sách liên kết mạng xã hội động (Hỗ trợ nhận diện icon tab của mọi website)
        $socialLinks = json_decode(Setting::get('social_links', '[]'), true);
        if (!is_array($socialLinks) || empty($socialLinks)) {
            $defaultLinks = [
                Setting::get('social_facebook', 'https://facebook.com'),
                Setting::get('social_linkedin', 'https://linkedin.com'),
                Setting::get('social_youtube', 'https://youtube.com'),
                Setting::get('social_telegram', 'https://telegram.org'),
            ];
            $socialLinks = array_values(array_filter($defaultLinks));
        }

        return view('pages.profile', compact('user', 'postsCount', 'commentsCount', 'avatarHistory', 'settings', 'socialLinks'));
    }

    /**
     * Cập nhật thông tin cá nhân Admin (Bỏ qua mật khẩu, lưu lịch sử avatar cũ)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'role_title'  => 'nullable|string|max:255',
            'bio'         => 'nullable|string|max:1000',
            'avatar_file' => 'nullable|image|max:5120',
        ]);

        $user = User::first();
        if (!$user) {
            $user = new User();
            $user->password = bcrypt('123456');
        }

        $oldAvatar = $user->avatar ?: 'assets/images/admin-khanh.jpg';
        $newAvatar = null;

        // Xử lý upload ảnh đại diện mới từ thiết bị
        if ($request->hasFile('avatar_file')) {
            $uploadDir = public_path('uploads/avatars');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $avatarFile = $request->file('avatar_file');
            $avatarName = 'avatar_' . time() . '_' . Str::random(6) . '.' . $avatarFile->getClientOriginalExtension();
            $avatarFile->move($uploadDir, $avatarName);
            $newAvatar = 'uploads/avatars/' . $avatarName;
        } elseif ($request->filled('avatar')) {
            $newAvatar = $request->input('avatar');
        }

        // Cập nhật lịch sử ảnh đại diện cũ nếu có đổi ảnh mới
        if ($newAvatar && $newAvatar !== $oldAvatar) {
            $defaultHistory = [
                'assets/images/admin-khanh.jpg',
                'assets/images/avatar-admin-2.jpg',
                'assets/images/avatar-admin-3.jpg'
            ];
            $history = is_array($user->avatar_history) && count($user->avatar_history) > 0 
                ? $user->avatar_history 
                : $defaultHistory;

            // Đưa ảnh vừa bị thay thế vào đầu danh sách lịch sử
            array_unshift($history, $oldAvatar);
            
            // Lọc trùng và giới hạn tối đa 6 ảnh cũ gần nhất
            $history = array_values(array_unique(array_filter($history)));
            $history = array_slice($history, 0, 6);

            $user->avatar_history = $history;
            $user->avatar = $newAvatar;
        } elseif ($newAvatar) {
            $user->avatar = $newAvatar;
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->role_title = $request->input('role_title');
        $user->bio = $request->input('bio');
        $user->save();

        // Cập nhật thông tin liên hệ Hotline, Email, Địa chỉ
        $contactSettings = [
            'contact_hotline'       => 'contact',
            'contact_email'         => 'contact',
            'contact_address'       => 'contact',
        ];

        foreach ($contactSettings as $key => $group) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key), $group);
            }
        }

        // Cập nhật danh sách liên kết mạng xã hội bất kỳ (Nhận diện tự động icon tab)
        if ($request->has('social_links')) {
            $rawLinks = (array) $request->input('social_links');
            $cleanLinks = [];
            foreach ($rawLinks as $link) {
                $trimmed = trim($link);
                if (!empty($trimmed)) {
                    if (!preg_match('/^https?:\/\//i', $trimmed)) {
                        $trimmed = 'https://' . $trimmed;
                    }
                    $cleanLinks[] = $trimmed;
                }
            }
            $cleanLinks = array_values(array_unique($cleanLinks));
            Setting::set('social_links', json_encode($cleanLinks), 'social');

            // Đồng bộ lại 4 trường cũ nếu có để đảm bảo tương thích ngược
            $fb = ''; $li = ''; $yt = ''; $tg = '';
            foreach ($cleanLinks as $l) {
                if (stripos($l, 'facebook.com') !== false && !$fb) $fb = $l;
                elseif (stripos($l, 'linkedin.com') !== false && !$li) $li = $l;
                elseif (stripos($l, 'youtube.com') !== false && !$yt) $yt = $l;
                elseif ((stripos($l, 'telegram.org') !== false || stripos($l, 't.me') !== false) && !$tg) $tg = $l;
            }
            Setting::set('social_facebook', $fb, 'social');
            Setting::set('social_linkedin', $li, 'social');
            Setting::set('social_youtube', $yt, 'social');
            Setting::set('social_telegram', $tg, 'social');
        }
        Setting::clearCache();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật thông tin cá nhân và kênh truyền thông thành công!'
            ]);
        }

        return redirect()->route('admin.profile')->with('success', 'Đã cập nhật thông tin cá nhân và kênh truyền thông thành công!');
    }

    /**
     * Đổi mật khẩu tài khoản quản trị
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required'     => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'          => 'Mật khẩu mới phải có tối thiểu 6 ký tự.',
            'new_password.confirmed'    => 'Xác nhận mật khẩu mới không trùng khớp.',
        ]);

        $user = Auth::user() ?: User::first();

        if (!$user || !Hash::check($request->input('current_password'), $user->password)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không chính xác! Vui lòng kiểm tra lại.'
                ], 422);
            }
            return back()->with('password_error', 'Mật khẩu hiện tại không chính xác! Vui lòng kiểm tra lại.');
        }

        if (Hash::check($request->input('new_password'), $user->password)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại!'
                ], 422);
            }
            return back()->with('password_error', 'Mật khẩu mới không được trùng với mật khẩu hiện tại!');
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công! Hãy ghi nhớ mật khẩu mới của bạn.'
            ]);
        }

        return redirect()->route('admin.profile')->with('password_success', 'Đổi mật khẩu thành công! Mật khẩu mới đã được cập nhật an toàn.');
    }

    /**
     * Quản lý đề xuất & Thư ngỏ hợp tác
     */
    public function proposals(Request $request)
    {
        $query = Proposal::query();

        // Lọc theo trạng thái
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Lọc theo mục đích
        if ($request->filled('purpose') && $request->input('purpose') !== 'all') {
            $query->where('purpose', $request->input('purpose'));
        }

        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $kw = $request->input('keyword');
            $query->where(function ($q) use ($kw) {
                $q->where('name', 'like', "%{$kw}%")
                  ->orWhere('email', 'like', "%{$kw}%")
                  ->orWhere('phone', 'like', "%{$kw}%")
                  ->orWhere('company', 'like', "%{$kw}%")
                  ->orWhere('subject', 'like', "%{$kw}%");
            });
        }

        // Lọc theo đánh dấu sao
        if ($request->filled('starred') && $request->input('starred') == '1') {
            $query->where('is_starred', true);
        }

        $proposals = $query->orderBy('is_starred', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(12)
                           ->withQueryString();

        // Thống kê nhanh
        $totalProposals     = Proposal::count();
        $pendingProposals   = Proposal::where('status', 'pending')->count();
        $contactedProposals = Proposal::where('status', 'contacted')->count();
        $approvedProposals  = Proposal::where('status', 'approved')->count();

        // Danh sách mục đích thực tế để lọc
        $purposes = Proposal::select('purpose')->distinct()->pluck('purpose');

        // Tự động mở modal chi tiết đề xuất khi chuyển từ thông báo sang
        $autoOpenProposal = null;
        if ($request->filled('proposal_id')) {
            $autoOpenProposal = Proposal::find($request->input('proposal_id'));
            if ($autoOpenProposal && !$autoOpenProposal->is_read) {
                $autoOpenProposal->is_read = true;
                $autoOpenProposal->save();
            }
        }

        return view('pages.manage-proposals', compact(
            'proposals',
            'totalProposals',
            'pendingProposals',
            'contactedProposals',
            'approvedProposals',
            'purposes',
            'autoOpenProposal'
        ));
    }

    /**
     * Cập nhật trạng thái đề xuất
     */
    public function updateProposalStatus(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,contacted,approved,declined',
        ]);

        $proposal->status = $request->input('status');
        $proposal->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật trạng thái đề xuất thành công!',
                'status' => $proposal->status,
                'status_label' => $proposal->status_label,
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đề xuất thành công!');
    }

    /**
     * Cập nhật ghi chú nội bộ của admin cho đề xuất
     */
    public function updateProposalNotes(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->admin_notes = $request->input('admin_notes');
        $proposal->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã lưu ghi chú nội bộ thành công!']);
        }

        return redirect()->back()->with('success', 'Đã lưu ghi chú nội bộ thành công!');
    }

    /**
     * Đánh dấu sao / ưu tiên đề xuất
     */
    public function toggleProposalStar($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->is_starred = !$proposal->is_starred;
        $proposal->save();

        return redirect()->back()->with('success', $proposal->is_starred ? 'Đã đánh dấu sao ưu tiên đề xuất!' : 'Đã bỏ đánh dấu sao đề xuất.');
    }

    /**
     * Tải file tài liệu / hồ sơ đính kèm của đề xuất hợp tác
     */
    public function downloadProposalAttachment($id)
    {
        $proposal = Proposal::findOrFail($id);

        if (!$proposal->attachment) {
            return redirect()->back()->with('error', 'Hồ sơ đề xuất này không có file đính kèm.');
        }

        $fullPath = public_path($proposal->attachment);

        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'File đính kèm không còn tồn tại trên hệ thống máy chủ.');
        }

        $downloadName = $proposal->attachment_name ?: basename($fullPath);

        return response()->download($fullPath, $downloadName);
    }

    /**
     * Xóa đề xuất hợp tác
     */
    public function destroyProposal($id)
    {
        $proposal = Proposal::findOrFail($id);

        if ($proposal->attachment && file_exists(public_path($proposal->attachment))) {
            @unlink(public_path($proposal->attachment));
        }

        $proposal->delete();

        return redirect()->back()->with('success', 'Đã xóa đề xuất hợp tác thành công!');
    }

    /**
     * Lấy dữ liệu thông báo hệ thống cho Admin (Real-time polling & Bell dropdown)
     */
    public function getNotifications()
    {
        $latestProposals = Proposal::orderBy('id', 'desc')->take(10)->get();
        $latestComments = Comment::with('post:id,title')->orderBy('id', 'desc')->take(10)->get();

        $unreadProposalsCount = Proposal::where('is_read', false)->count();
        $unreadCommentsCount = Comment::where('is_read', false)->count();

        $unreadCount = $unreadProposalsCount + $unreadCommentsCount;

        return response()->json([
            'success'          => true,
            'unread_count'     => $unreadCount,
            'proposals_count'  => $unreadProposalsCount,
            'comments_count'   => $unreadCommentsCount,
            'proposals'        => $latestProposals->map(function ($p) {
                return [
                    'id'      => $p->id,
                    'type'    => 'proposal',
                    'is_read' => (bool)$p->is_read,
                    'name'    => $p->name,
                    'company' => $p->company,
                    'snippet' => Str::limit($p->subject ?: $p->message, 70),
                    'time'    => $p->created_at ? $p->created_at->diffForHumans() : '',
                    'url'     => route('admin.proposals.index', ['proposal_id' => $p->id]),
                ];
            }),
            'comments'         => $latestComments->map(function ($c) {
                return [
                    'id'         => $c->id,
                    'type'       => 'comment',
                    'is_read'    => (bool)$c->is_read,
                    'author'     => $c->author_name,
                    'post_title' => $c->post ? Str::limit($c->post->title, 40) : 'Bài viết',
                    'snippet'    => Str::limit($c->content, 65),
                    'time'       => $c->created_at ? $c->created_at->diffForHumans() : '',
                    'url'        => route('admin.comments', ['comment_id' => $c->id]),
                ];
            }),
        ]);
    }

    /**
     * Đánh dấu 1 thông báo cụ thể là đã đọc khi click xem
     */
    public function markSingleNotificationRead(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'proposal' && !empty($id)) {
            Proposal::where('id', $id)->update(['is_read' => true]);
        } elseif ($type === 'comment' && !empty($id)) {
            Comment::where('id', $id)->update(['is_read' => true]);
        }

        $unreadProposals = Proposal::where('is_read', false)->count();
        $unreadComments = Comment::where('is_read', false)->count();
        $totalUnread = $unreadProposals + $unreadComments;

        return response()->json([
            'success'         => true,
            'unread_count'    => $totalUnread,
            'proposals_count' => $unreadProposals,
            'comments_count'  => $unreadComments,
        ]);
    }

    /**
     * Đánh dấu toàn bộ thông báo đã đọc
     */
    public function markNotificationsRead()
    {
        Proposal::where('is_read', false)->update(['is_read' => true]);
        Comment::where('is_read', false)->update(['is_read' => true]);

        return response()->json([
            'success'         => true,
            'unread_count'    => 0,
            'proposals_count' => 0,
            'comments_count'  => 0,
        ]);
    }
}
