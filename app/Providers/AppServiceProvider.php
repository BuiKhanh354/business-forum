<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use App\Models\Proposal;
use App\Models\Comment;
use Carbon\Carbon;

if (!function_exists('setting')) {
    /**
     * Helper toàn cục lấy giá trị cài đặt giao diện/hệ thống
     */
    function setting($key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Thiết lập ngôn ngữ thời gian tiếng Việt (ví dụ: 5 phút trước, 2 giờ trước)
        Carbon::setLocale('vi');

        // Sử dụng giao diện phân trang Bootstrap 5 chuẩn thay vì Tailwind mặc định
        Paginator::useBootstrapFive();

        // Chia sẻ danh mục chuyên đề & cài đặt giao diện toàn cục cho các components và views
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('categories')) {
                    $globalCategories = Category::withCount('posts')->orderBy('id', 'asc')->get();
                    $view->with('globalCategories', $globalCategories);
                }

                if (Schema::hasTable('settings')) {
                    $siteSettings = Setting::getAllSettings();
                    $view->with('siteSettings', $siteSettings);
                }

                if (Schema::hasTable('users')) {
                    $authorUser = User::first();
                    if (!$authorUser) {
                        $authorUser = new User([
                            'name' => 'Bùi Viết Khánh',
                            'avatar' => 'assets/images/admin-khanh.jpg',
                            'role_title' => 'Founder & Ban Quản trị',
                            'bio' => 'Chia sẻ chiến lược kinh doanh, quản trị dòng tiền và đầu tư tài chính.',
                        ]);
                    }
                    $view->with('authorUser', $authorUser);
                    $currentUser = Auth::user();
                    $view->with('currentUser', $currentUser);
                }

                $pendingProposalsCount = 0;
                if (Schema::hasTable('proposals')) {
                    $pendingProposalsCount = Proposal::where('is_read', false)->count();
                    $view->with('pendingProposalsCount', $pendingProposalsCount);

                    $latestProposals = Proposal::orderBy('id', 'desc')->take(8)->get();
                    $view->with('latestProposals', $latestProposals);
                }

                $recentCommentsCount = 0;
                if (Schema::hasTable('comments')) {
                    $latestComments = Comment::with('post')->orderBy('id', 'desc')->take(8)->get();
                    $view->with('latestComments', $latestComments);

                    $recentCommentsCount = Comment::where('is_read', false)->count();
                    $view->with('recentCommentsCount', $recentCommentsCount);
                }

                // Tổng số thông báo chưa đọc thực tế trong Database
                $unreadNotifCount = $pendingProposalsCount + $recentCommentsCount;
                $view->with('unreadNotifCount', $unreadNotifCount);
            } catch (\Throwable $e) {
                // Fallback nếu database chưa khởi tạo hoặc đang chạy migration
            }
        });
    }
}
