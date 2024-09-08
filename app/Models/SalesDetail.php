<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    public $table = 'sales_detail';

    use HasFactory;

    protected $fillable = ['price','quantity','total','saleId','productId'];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
