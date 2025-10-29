<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'thumbnail',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // 🔁 ความสัมพันธ์กับผู้เขียน
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 🧠 Accessor: ถ้าไม่มีรูปปก ให้ fallback เป็น placeholder
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail
            ? $this->thumbnail
            : asset('images/default-article.jpg'); // 👈 คุณสามารถใส่ placeholder ของคุณเองได้
    }

    // ⚡ Auto generate slug เมื่อสร้าง
    protected static function booted()
    {
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }

            // ถ้า status = published → บันทึกเวลาปัจจุบัน
            if ($article->status === 'published' && empty($article->published_at)) {
                $article->published_at = now();
            }
        });

        static::updating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }

            // update published_at ถ้าเพิ่งเปลี่ยนสถานะเป็น published
            if ($article->isDirty('status') && $article->status === 'published') {
                $article->published_at = now();
            }
        });
    }

    // 🧭 Helper: เช็คสถานะ
    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }
}
