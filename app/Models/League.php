<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $table = 'competitions';

    protected $fillable = ['uuid', 'name', 'name_en', 'name_th', 'country', 'logo'];

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }
}
