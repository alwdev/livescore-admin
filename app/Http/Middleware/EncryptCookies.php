<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    // ถ้ามี cookie ที่ไม่ต้องเข้ารหัส ให้ใส่ชื่อไว้ใน array นี้
    protected $except = [
        //
    ];
}
