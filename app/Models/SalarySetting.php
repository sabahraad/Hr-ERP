<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySetting extends Model
{
    use HasFactory;

    protected $fillable = ['components'];
    protected $primaryKey = 'salary_settings_id';

    protected $casts = [
        'components' => 'array',
    ];
}
