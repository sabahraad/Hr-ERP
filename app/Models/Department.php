<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory,SoftDeletes;
    protected $primaryKey = 'dept_id';

    public function company(){
        return $this->belongsTo(company::class);
    }
    
    public function designation(){
        return $this->hasMany(designation::class);
    }
}
