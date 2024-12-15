<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YapeExpense extends Model
{
    use HasFactory;
    protected $table = 'yapeexpense';
    public $timestamps = false;
}
