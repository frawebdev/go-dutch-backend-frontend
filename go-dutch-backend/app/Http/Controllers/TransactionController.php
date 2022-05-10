<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function show($id)
    {
        $transactions = Participant::where('id', $id)->with('transactions')->first();

        return $transactions;
    }
}
