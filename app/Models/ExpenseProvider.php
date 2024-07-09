<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseProvider extends Model
{
    use HasFactory;
    public $table = 'expenseprovider';
    public $timestamps = false;
}
