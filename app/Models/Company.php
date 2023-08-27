<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Company extends Model
{
    use HasFactory ,SoftDeletes;

    protected $primaryKey = 'company_id';
    
    public function ip(){
        return $this->hasMany(IP::class);
    }
    
}
