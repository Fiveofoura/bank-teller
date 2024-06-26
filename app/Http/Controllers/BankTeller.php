<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankTeller extends Controller
{
    public function sacc(Request $request)
    {
        $req = $request->all();
        
        $custID = DB::table('customer')
        ->rightJoin('account', 'customer.cust_id', '=', 'account.cust_id')
        ->where('customer.fed_id', '=', $req['account'])
        ->get();
        foreach ($custID as $user) {
           
        }
    }
}
