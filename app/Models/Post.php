<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'cover_image',
        'gallery_images',
        'external_link',
        'external_link_title',
        'external_link_image',
        'reading_time',
        'views_count',
        'likes_count',
        'is_featured',
        'is_published',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
    ];

    /**
     * Lấy toàn bộ danh sách ảnh của bài viết (Tối đa 5 ảnh)
     */
    public function getAllImagesAttribute(): array
    {
        if (!empty($this->gallery_images) && is_array($this->gallery_images) && count($this->gallery_images) > 0) {
            return array_slice($this->gallery_images, 0, 5);
        }
        if (!empty($this->cover_image)) {
            return [$this->cover_image];
        }
        return ['assets/images/post-tech-ai.jpg'];
    }

    /**
     * Danh mục mà bài viết này thuộc về
     */
    public function categoryItem()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Danh sách bình luận thuộc về bài viết
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Lấy ảnh thực tế của bài báo ngoài (OpenGraph Image) thay vì ảnh bài viết của Admin
     */
    public function getExternalArticleImageAttribute(): ?string
    {
        if (!empty($this->external_link_image)) {
            return $this->external_link_image;
        }

        if (!empty($this->external_link)) {
            $og = self::fetchOgImage($this->external_link);
            if ($og) {
                try {
                    $this->external_link_image = $og;
                    $this->saveQuietly();
                } catch (\Throwable $e) {}
                return $og;
            }
        }

        return null;
    }

    /**
     * Thu thập thẻ OpenGraph image hoặc ảnh bìa của trang báo từ liên kết bên ngoài
     */
    public static function fetchOgImage(?string $url): ?string
    {
        if (empty($url)) return null;
        $url = trim($url);
        if (!preg_match('~^https?://~i', $url)) {
            $url = 'https://' . $url;
        }

        try {
            $ctx = stream_context_create([
                'http' => [
                    'timeout'         => 3,
                    'user_agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'follow_location' => 1,
                    'max_redirects'   => 3,
                ],
                'ssl' => [
                    'verify_peer'      => false,
                    'verify_peer_name' => false,
                ]
            ]);

            $fp = @fopen($url, 'r', false, $ctx);
            if (!$fp) return null;

            $html = '';
            while (!feof($fp) && strlen($html) < 65536) {
                $html .= fread($fp, 8192);
                if (stripos($html, '</head>') !== false) break;
            }
            fclose($fp);

            $img = null;
            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $img = trim($m[1]);
            } elseif (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/i', $html, $m)) {
                $img = trim($m[1]);
            } elseif (preg_match('/<meta[^>]+name=["\']twitter:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $img = trim($m[1]);
            } elseif (preg_match('/<link[^>]+rel=["\']image_src["\'][^>]+href=["\']([^"\']+)["\']/i', $html, $m)) {
                $img = trim($m[1]);
            }

            if (!empty($img)) {
                if (!preg_match('~^https?://~i', $img) && !str_starts_with($img, '//')) {
                    $parsed = parse_url($url);
                    $scheme = $parsed['scheme'] ?? 'https';
                    $host = $parsed['host'] ?? '';
                    $img = $scheme . '://' . $host . '/' . ltrim($img, '/');
                } elseif (str_starts_with($img, '//')) {
                    $img = 'https:' . $img;
                }
                return $img;
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }
}
