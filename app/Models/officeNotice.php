<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class officeNotice extends Model
{
    use HasFactory,SoftDeletes;
    protected $primaryKey = 'office_notices_id';

}
