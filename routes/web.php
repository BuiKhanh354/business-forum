<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - KẾT NỐI Business Community
|--------------------------------------------------------------------------
*/

// Public Blog Routes (Giao diện người dùng công khai - Chỉ xem & đọc)
Route::get('/', [BlogController::class, 'home'])->name('home');
Route::get('/ve-toi', [BlogController::class, 'about'])->name('about');
Route::get('/bai-viet/{id?}', [BlogController::class, 'postDetail'])->name('post.detail');
Route::post('/bai-viet/{id}/thich', [BlogController::class, 'togglePostLike'])->name('post.like');
Route::post('/bai-viet/{id}/binh-luan', [BlogController::class, 'storeComment'])->name('post.comment');
Route::get('/lien-he', [BlogController::class, 'contact'])->name('contact');
Route::post('/lien-he', [BlogController::class, 'submitProposal'])->name('contact.submit');
Route::post('/tin-nhan-rieng', [BlogController::class, 'submitQuickMessage'])->name('quick.message');
Route::post('/cai-dat-giao-dien/chuyen-giao-dien-nhanh', [AdminController::class, 'quickToggleTheme'])->name('theme.quickToggle');

// Authentication Routes (Đăng nhập / Đăng xuất Quản trị)
Route::get('/dang-nhap', [AuthController::class, 'login'])->name('login');
Route::post('/dang-nhap', [AuthController::class, 'authenticate'])->name('login.submit');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

// Admin Workspace Routes (Khu vực quản trị được bảo vệ bởi middleware auth)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Quản lý bài viết (Chức năng toàn diện: Danh sách, Thêm, Sửa, Xóa, Đổi trạng thái)
    Route::get('/bai-viet', [AdminController::class, 'posts'])->name('posts');
    Route::post('/bai-viet', [AdminController::class, 'storePost'])->name('posts.store');
    Route::put('/bai-viet/{id}', [AdminController::class, 'updatePost'])->name('posts.update');
    Route::delete('/bai-viet/{id}', [AdminController::class, 'destroyPost'])->name('posts.destroy');
    Route::patch('/bai-viet/{id}/chuyen-trang-thai', [AdminController::class, 'togglePostStatus'])->name('posts.toggle');

    // Quản lý danh mục (Chức năng toàn diện: Danh sách, Tạo mới, Chỉnh sửa popup, Xóa)
    Route::get('/danh-muc', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/danh-muc', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/danh-muc/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/danh-muc/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Quản lý bình luận
    Route::get('/binh-luan', [AdminController::class, 'comments'])->name('comments');
    Route::patch('/binh-luan/{id}/thich', [AdminController::class, 'toggleCommentLike'])->name('comments.like');
    Route::delete('/binh-luan/{id}', [AdminController::class, 'destroyComment'])->name('comments.destroy');

    // Quản lý Đề xuất & Thư ngỏ hợp tác
    Route::get('/de-xuat-hop-tac', [AdminController::class, 'proposals'])->name('proposals.index');
    Route::get('/de-xuat-hop-tac/{id}/tai-file', [AdminController::class, 'downloadProposalAttachment'])->name('proposals.download');
    Route::patch('/de-xuat-hop-tac/{id}/trang-thai', [AdminController::class, 'updateProposalStatus'])->name('proposals.status');
    Route::patch('/de-xuat-hop-tac/{id}/ghi-chu', [AdminController::class, 'updateProposalNotes'])->name('proposals.notes');
    Route::patch('/de-xuat-hop-tac/{id}/danh-dau', [AdminController::class, 'toggleProposalStar'])->name('proposals.star');
    Route::delete('/de-xuat-hop-tac/{id}', [AdminController::class, 'destroyProposal'])->name('proposals.destroy');

    Route::get('/cai-dat-giao-dien', [AdminController::class, 'theme'])->name('theme');
    Route::post('/cai-dat-giao-dien', [AdminController::class, 'updateTheme'])->name('theme.update');
    Route::post('/cai-dat-giao-dien/khoi-phuc', [AdminController::class, 'resetTheme'])->name('theme.reset');
    Route::get('/thong-tin-ca-nhan', [AdminController::class, 'profile'])->name('profile');
    Route::post('/thong-tin-ca-nhan', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('/thong-tin-ca-nhan/doi-mat-khau', [AdminController::class, 'changePassword'])->name('password.change');

    // Trung tâm thông báo hệ thống cho Admin
    Route::get('/thong-bao', [AdminController::class, 'getNotifications'])->name('notifications');
    Route::post('/thong-bao/doc-muc', [AdminController::class, 'markSingleNotificationRead'])->name('notifications.readSingle');
    Route::post('/thong-bao/danh-dau-da-doc', [AdminController::class, 'markNotificationsRead'])->name('notifications.read');
});
