<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosExpense extends Model
{
    use HasFactory;
    protected $table = 'posexpense';
    public $timestamps = false;
}
