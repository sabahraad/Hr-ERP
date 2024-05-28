<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class officeLocation extends Model
{
    use HasFactory,SoftDeletes;
    protected $primaryKey = 'office_locations_id';

    // Define the relationship with the LocationWiseEmployee model
    public function locationWiseEmployees()
    {
        return $this->hasMany(LocationWiseEmployee::class, 'office_locations_id', 'office_locations_id');
    }
}
