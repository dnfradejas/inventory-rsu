<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    

    public function getIndex()
    {
        return view('cashier.transaction.index');
    }
}
