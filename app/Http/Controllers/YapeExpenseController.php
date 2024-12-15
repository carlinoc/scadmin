<?php

namespace App\Http\Controllers;

use App\Models\YapeExpense;
use Illuminate\Http\Request;
use Illuminate\View\View;

class YapeExpenseController extends Controller
{
    public function index(): View
    {
        return view('yapeexpense.index');
    }
}
