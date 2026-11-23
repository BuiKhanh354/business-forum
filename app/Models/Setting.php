<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Lấy giá trị cài đặt theo key (có cache)
     */
    public static function get($key, $default = null)
    {
        try {
            $settings = self::getAllSettings();
            return array_key_exists($key, $settings) ? $settings[$key] : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Lưu hoặc cập nhật một key cài đặt
     */
    public static function set($key, $value, $group = 'general')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget('site_settings_all');

        return $setting;
    }

    /**
     * Lấy toàn bộ danh sách settings dạng mảng key => value
     */
    public static function getAllSettings()
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                \Illuminate\Support\Facades\Schema::create('settings', function ($table) {
                    $table->id();
                    $table->string('key')->unique();
                    $table->longText('value')->nullable();
                    $table->string('group', 50)->default('general');
                    $table->timestamps();
                });
            }

            return Cache::remember('site_settings_all', 3600, function () {
                return self::pluck('value', 'key')->toArray();
            });
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Xóa cache settings khi cập nhật
     */
    public static function clearCache()
    {
        Cache::forget('site_settings_all');
    }
}
