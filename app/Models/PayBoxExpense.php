<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBoxExpense extends Model
{
    use HasFactory;
    protected $table = 'payboxexpense';
    public $timestamps = false;
}
