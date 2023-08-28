<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class leaveSetting extends Model
{
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'leave_setting_id';

    public function company(){
        return $this->belongsTo(company::class);
    }
}
