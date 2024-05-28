<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationWiseEmployee extends Model
{
    use HasFactory;
    protected $primaryKey = 'location_wise_employees_id';

    public function officeLocation()
    {
        return $this->belongsTo(OfficeLocation::class, 'office_locations_id', 'office_locations_id');
    }

}
