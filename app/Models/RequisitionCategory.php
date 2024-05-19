<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionCategory extends Model
{
    use HasFactory;
    protected $primaryKey = 'requisition_categories_id';
    
    public function products()
    {
        return $this->hasMany(Product::class, 'requisition_categories_id', 'requisition_categories_id');
    }

}
