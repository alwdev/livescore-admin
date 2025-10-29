<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs ที่ไม่ต้องตรวจสอบ CSRF (ถ้ามี)
     *
     * @var array<int, string>
     */
    protected $except = [
        // ตัวอย่าง: 'webhook/*'
    ];
}
