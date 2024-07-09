<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBox extends Model
{
    use HasFactory;
    public $table = 'paybox';
    public $timestamps = false;
}
