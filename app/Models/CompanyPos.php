<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPos extends Model
{
    use HasFactory;
    protected $table = 'companypos';
    public $timestamps = false;
}
