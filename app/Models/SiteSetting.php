<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'site_name',
        'logo',
        'favicon',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'contact_email',
        'contact_phone',
        'contact_address',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
    ];
}
