<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'role',
        'purpose',
        'subject',
        'message',
        'attachment',
        'attachment_name',
        'status',
        'is_read',
        'is_starred',
        'admin_notes',
        'ip_address',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
    ];

    /**
     * Nhãn hiển thị trạng thái
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending'   => 'Chờ xử lý',
            'contacted' => 'Đang trao đổi',
            'approved'  => 'Đã chấp thuận',
            'declined'  => 'Đã từ chối',
            default     => 'Mới',
        };
    }

    /**
     * Màu sắc huy hiệu trạng thái
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending'   => '#f59e0b', // Amber/Vàng
            'contacted' => '#2563eb', // Blue/Xanh dương
            'approved'  => '#16a34a', // Green/Xanh lá
            'declined'  => '#94a3b8', // Gray/Xám
            default     => '#f59e0b',
        };
    }

    /**
     * Background huy hiệu trạng thái
     */
    public function getStatusBgAttribute()
    {
        return match ($this->status) {
            'pending'   => '#fef3c7',
            'contacted' => '#dbeafe',
            'approved'  => '#dcfce7',
            'declined'  => '#f1f5f9',
            default     => '#fef3c7',
        };
    }

    /**
     * Biểu tượng FontAwesome & màu sắc theo định dạng file đính kèm
     */
    public function getFileIconInfoAttribute()
    {
        if (!$this->attachment) {
            return ['icon' => 'fa-regular fa-file', 'color' => '#64748b', 'bg' => '#f1f5f9', 'type' => 'Tài liệu'];
        }

        $filename = $this->attachment_name ?: $this->attachment;
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match ($ext) {
            'pdf'               => ['icon' => 'fa-solid fa-file-pdf', 'color' => '#ef4444', 'bg' => '#fef2f2', 'type' => 'PDF Document'],
            'doc', 'docx'       => ['icon' => 'fa-solid fa-file-word', 'color' => '#2563eb', 'bg' => '#eff6ff', 'type' => 'Word Document'],
            'xls', 'xlsx'       => ['icon' => 'fa-solid fa-file-excel', 'color' => '#16a34a', 'bg' => '#f0fdf4', 'type' => 'Excel Sheet'],
            'ppt', 'pptx'       => ['icon' => 'fa-solid fa-file-powerpoint', 'color' => '#ea580c', 'bg' => '#fff7ed', 'type' => 'PowerPoint'],
            'zip', 'rar', '7z'  => ['icon' => 'fa-solid fa-file-zipper', 'color' => '#ca8a04', 'bg' => '#fefce8', 'type' => 'Archive ZIP'],
            'png', 'jpg', 'jpeg', 'webp', 'svg' => ['icon' => 'fa-solid fa-file-image', 'color' => '#9333ea', 'bg' => '#faf5ff', 'type' => 'Hình ảnh'],
            default             => ['icon' => 'fa-solid fa-file-lines', 'color' => '#475569', 'bg' => '#f8fafc', 'type' => strtoupper($ext) ?: 'File'],
        };
    }

    /**
     * Dung lượng file định dạng human-readable (KB / MB)
     */
    public function getFileSizeFormattedAttribute()
    {
        if (!$this->attachment) return null;
        $path = public_path($this->attachment);
        if (!file_exists($path)) return null;

        $bytes = filesize($path);
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }
}
