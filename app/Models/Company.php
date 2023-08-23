<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Company extends Model
{
    use HasFactory ,SoftDeletes;

    protected $primaryKey = 'company_id';
    
    public function ip(){
        return $this->hasMany(IP::class);
    }
    
}
