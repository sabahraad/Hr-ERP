<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $primaryKey = 'vendors_id';
    
    public function requisitionCategory()
    {
        return $this->belongsTo(RequisitionCategory::class, 'requisition_categories_id', 'requisition_categories_id');
    }
}
