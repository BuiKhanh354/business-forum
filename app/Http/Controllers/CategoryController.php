<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Trang quản lý danh mục (Danh sách & Form tạo danh mục)
     */
    public function index(Request $request)
    {
        $query = Category::withCount('posts');

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('slug', 'LIKE', "%{$keyword}%");
            });
        }

        $categories = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('pages.manage-categories', compact('categories'));
    }

    /**
     * Thêm danh mục mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $name = trim($request->input('name'));
        $slug = Str::slug($name);

        if (empty($slug)) {
            $slug = 'danh-muc-' . time();
        }

        $existing = Category::where('slug', $slug)->first();
        if ($existing) {
            $slug .= '-' . (Category::where('slug', 'LIKE', "{$slug}%")->count() + 1);
        }

        $category = Category::create([
            'name' => $name,
            'slug' => $slug,
            'color' => $request->input('color', '#2563eb'),
            'description' => $request->input('description'),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo danh mục "' . $name . '" thành công!',
                'category' => $category,
            ]);
        }

        return redirect()->back()->with('success', 'Đã tạo danh mục "' . $name . '" thành công!');
    }

    /**
     * Cập nhật / Sửa danh mục chuyên đề
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $name = trim($request->input('name'));

        if ($name !== $category->name) {
            $slug = Str::slug($name);
            if (empty($slug)) {
                $slug = 'danh-muc-' . time();
            }
            $existing = Category::where('slug', $slug)->where('id', '!=', $id)->first();
            if ($existing) {
                $slug .= '-' . (Category::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count() + 1);
            }
            $category->slug = $slug;
        }

        $category->name = $name;
        $category->color = $request->input('color', $category->color) ?: '#2563eb';
        $category->description = $request->input('description');
        $category->save();

        // Đồng bộ cập nhật tên chuyên mục cho các bài viết thuộc danh mục này
        try {
            Post::where('category_id', $category->id)->update([
                'category' => $category->name
            ]);
        } catch (\Throwable $e) {}

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật danh mục "' . $name . '" thành công!',
                'category' => $category,
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật danh mục "' . $name . '" thành công!');
    }

    /**
     * Xóa danh mục - Tự động cập nhật các bài viết thuộc danh mục này
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $name = $category->name;

        // Cập nhật các bài viết liên kết sang 'Chưa phân loại' để không bị mất bài viết
        try {
            Post::where('category_id', $category->id)->update([
                'category_id' => null,
                'category' => 'Chưa phân loại'
            ]);
        } catch (\Throwable $e) {
            // Tiếp tục nếu bảng posts chưa có trường category_id
        }

        // Xóa danh mục khỏi cơ sở dữ liệu
        $category->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa danh mục "' . $name . '" thành công!',
            ]);
        }

        return redirect()->back()->with('success', 'Đã xóa danh mục "' . $name . '" thành công!');
    }
}
