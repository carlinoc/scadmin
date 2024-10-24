<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosIncome extends Model
{
    use HasFactory;
    protected $table = 'posincome';
    public $timestamps = false;
}
