<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleSplit extends Model
{
    use HasFactory;
    protected $table = 'salesplit';
    public $timestamps = false;
}
