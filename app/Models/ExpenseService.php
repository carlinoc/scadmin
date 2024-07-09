<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseService extends Model
{
    use HasFactory;
    public $table = 'expenseservice';
    public $timestamps = false;
}
