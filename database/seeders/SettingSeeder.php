<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            // Thương hiệu & Nhận diện
            'site_name'             => ['value' => 'KẾT NỐI', 'group' => 'branding'],
            'brand_name'            => ['value' => 'Median UI', 'group' => 'branding'],
            'site_slogan'           => ['value' => 'Diễn đàn chia sẻ tri thức quản trị kinh doanh & kết nối doanh nhân', 'group' => 'branding'],
            'site_logo'             => ['value' => '', 'group' => 'branding'],
            'site_favicon'          => ['value' => '', 'group' => 'branding'],

            // Giao diện & Màu sắc
            'theme_mode'            => ['value' => 'light', 'group' => 'appearance'],
            'theme_preset'          => ['value' => 'default', 'group' => 'appearance'],
            'primary_color'         => ['value' => '#2563eb', 'group' => 'appearance'],
            'accent_color'          => ['value' => '#d97706', 'group' => 'appearance'],
            'bg_color'              => ['value' => '#f8fafc', 'group' => 'appearance'],
            'card_bg_color'         => ['value' => '#ffffff', 'group' => 'appearance'],
            'border_radius'         => ['value' => 'default', 'group' => 'appearance'],

            // Ảnh bìa Blog (Cover Banner)
            'cover_desktop'         => ['value' => 'assets/images/cover-banner.jpg', 'group' => 'cover'],
            'cover_mobile'          => ['value' => 'assets/images/cover-night-skyline.jpg', 'group' => 'cover'],
            'cover_height_desktop'  => ['value' => '350px', 'group' => 'cover'],
            'cover_height_mobile'   => ['value' => '220px', 'group' => 'cover'],

            // Tiêu đề Blog & Nội dung trang chủ
            'blog_title'            => ['value' => 'Blog', 'group' => 'content'],
            'blog_subtitle'         => ['value' => 'Cùng trao đổi góc nhìn chiến lược, cập nhật thị trường và chia sẻ kiến thức quản trị.', 'group' => 'content'],
            'footer_about'          => ['value' => 'KẾT NỐI - Nền tảng chia sẻ thông tin, thảo luận chuyên sâu về quản trị doanh nghiệp, đầu tư tài chính và phát triển bền vững.', 'group' => 'content'],

            // Liên hệ & Chân trang
            'contact_hotline'       => ['value' => '1900 6868', 'group' => 'contact'],
            'contact_email'         => ['value' => 'banbientap@ketnoi.vn', 'group' => 'contact'],
            'contact_address'       => ['value' => 'Tầng 18, Toà tháp Doanh nhân, Quận 1, TP. Hồ Chí Minh', 'group' => 'contact'],
            'copyright_text'        => ['value' => '© 2026 KẾT NỐI Business Community. Tất cả quyền được bảo lưu.', 'group' => 'contact'],

            // Mạng xã hội
            'social_facebook'       => ['value' => 'https://facebook.com', 'group' => 'social'],
            'social_linkedin'       => ['value' => 'https://linkedin.com', 'group' => 'social'],
            'social_youtube'        => ['value' => 'https://youtube.com', 'group' => 'social'],
            'social_telegram'       => ['value' => 'https://telegram.org', 'group' => 'social'],
        ];

        foreach ($defaults as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $data['value'], 'group' => $data['group']]
            );
        }

        Setting::clearCache();
    }
}
