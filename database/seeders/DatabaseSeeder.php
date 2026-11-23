<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo tài khoản Admin mặc định
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Khánh',
                'password' => Hash::make('password123'),
                'avatar' => 'assets/images/admin-khanh.jpg',
                'phone' => '0900 123 456',
                'role_title' => 'Founder & Ban Quản trị',
                'bio' => 'Chia sẻ chiến lược kinh doanh, quản trị dòng tiền và đầu tư tài chính.',
            ]
        );

        // 2. Danh mục chuẩn (Categories)
        $cat1 = Category::firstOrCreate(
            ['slug' => 'chien-luoc-kinh-doanh'],
            [
                'name' => 'Chiến lược kinh doanh',
                'color' => '#2563eb',
                'description' => 'Phân tích chu kỳ kinh tế, quản trị doanh nghiệp và chiến lược mở rộng thị trường.'
            ]
        );

        $cat2 = Category::firstOrCreate(
            ['slug' => 'tai-chinh-dong-tien'],
            [
                'name' => 'Tài chính & Dòng tiền',
                'color' => '#16a34a',
                'description' => 'Kiểm soát dòng tiền, tối ưu hóa chi phí vận hành và quản trị rủi ro tài chính.'
            ]
        );

        $cat3 = Category::firstOrCreate(
            ['slug' => 'thi-truong-dau-tu'],
            [
                'name' => 'Thị trường & Đầu tư',
                'color' => '#d97706',
                'description' => 'Cập nhật diễn biến thị trường, cơ hội hợp tác và xúc tiến đầu tư kinh doanh.'
            ]
        );

        $cat4 = Category::firstOrCreate(
            ['slug' => 'cong-nghe-ai'],
            [
                'name' => 'Công nghệ & AI',
                'color' => '#7c3aed',
                'description' => 'Chuyển đổi số, tự động hóa quy trình và ứng dụng trí tuệ nhân tạo.'
            ]
        );

        $cat5 = Category::firstOrCreate(
            ['slug' => 'quan-tri-esg'],
            [
                'name' => 'Quản trị & ESG',
                'color' => '#0d9488',
                'description' => 'Mô hình quản trị bền vững, tiêu chuẩn xanh và trách nhiệm xã hội.'
            ]
        );

        $cat6 = Category::firstOrCreate(
            ['slug' => 'thong-bao'],
            [
                'name' => 'Thông báo',
                'color' => '#64748b',
                'description' => 'Thông báo nội bộ, lịch tọa đàm và sự kiện cộng đồng doanh nghiệp.'
            ]
        );

        // 3. Bài viết mẫu 1 (Tiêu điểm công nghệ & kinh tế số)
        $post1 = Post::firstOrCreate(
            ['slug' => 'chien-luoc-thich-ung-doanh-nghiep-2026'],
            [
                'category_id' => $cat1->id,
                'title' => 'Chiến lược thích ứng của doanh nghiệp trước chu kỳ công nghệ và kinh tế số 2026',
                'category' => 'Chiến lược kinh doanh',
                'excerpt' => 'Chia sẻ một bài báo về xu hướng phát triển trí tuệ nhân tạo và kinh tế số hiện nay. Đây là nguồn tham khảo rất hữu ích cho cộng đồng doanh nghiệp trong việc tối ưu hóa chi phí vận hành và xây dựng lợi thế cạnh tranh dài hạn.',
                'content' => 'Nội dung chi tiết phân tích chuyên sâu về sự chuyển dịch của các mô hình kinh doanh trong kỷ nguyên số...',
                'cover_image' => 'post-tech-ai.jpg',
                'reading_time' => 5,
                'views_count' => 1420,
                'likes_count' => 156,
                'is_featured' => true,
                'is_published' => true,
            ]
        );

        // 4. Bài viết mẫu 2 (Sự kiện kết nối doanh nhân)
        $post2 = Post::firstOrCreate(
            ['slug' => 'thong-bao-toa-dam-ket-noi-doanh-nhan-thang-11'],
            [
                'category_id' => $cat6->id,
                'title' => 'Thông báo: Tọa đàm kết nối doanh nhân và cơ hội xúc tiến đầu tư tháng 11',
                'category' => 'Thông báo',
                'excerpt' => 'Trân trọng kính mời các thành viên ban điều hành, quý nhà đầu tư cùng tham dự buổi tọa đàm kết nối kinh doanh nhằm mở rộng mạng lưới giao thương và tìm kiếm cơ hội hợp tác chiến lược trong quý 4.',
                'content' => 'Thông tin chi tiết về chương trình tọa đàm, diễn giả khách mời và quy trình đăng ký tham dự...',
                'cover_image' => 'post-summit.jpg',
                'reading_time' => 3,
                'views_count' => 980,
                'likes_count' => 89,
                'is_featured' => false,
                'is_published' => true,
            ]
        );

        // 5. Bình luận mẫu
        Comment::firstOrCreate(
            ['content' => 'Góc nhìn rất thực tế và sắc bén của anh Khánh! Doanh nghiệp vừa và nhỏ hiện nay rất cần tối ưu chi phí trước khi mở rộng quy mô.'],
            [
                'post_id' => $post1->id,
                'author_name' => 'Nguyễn Văn Minh (CEO TechCorp)',
                'author_email' => 'minh.nguyen@techcorp.vn',
                'status' => 'approved',
                'likes_count' => 14,
            ]
        );

        Comment::firstOrCreate(
            ['content' => 'Tôi đã đăng ký vé tham dự tọa đàm! Rất mong được gặp gỡ và giao lưu cùng các anh em doanh nhân.'],
            [
                'post_id' => $post2->id,
                'author_name' => 'Trần Thị Thu (Giám đốc Đầu tư Alpha Capital)',
                'author_email' => 'thu.tran@alphacapital.vn',
                'status' => 'approved',
                'likes_count' => 8,
            ]
        );
    }
}
