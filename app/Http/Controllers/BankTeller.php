<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankTeller extends Controller
{
    public function sacc(request $request)
    {
        $req = $request->all();
        $views = array();
        $trans = array();
        $transheaders = array(
            'txn_id' => 'ID',
            'txn_date' => 'Date',
            'txn_type_cd' => 'Type',
            'amount' => 'Amount',   
        );
        
        $account = trim($req['account']);
        if(preg_match('/^[0-9]+[0-9\-]+[0-9]+$/', $account))
        {  
            if(isset($req['type']))
            {
                if(isset($req['deb']))
                {
                    if(session()->get('debounce') === $req['deb'])
                    {
                        $tx = new transactions();
                        $gt = $tx->addTransaction($req);
                    }
                }
            }
            // Create a blueprint for the account display
            $viewclass = new \stdClass();
            $viewclass->address = array(
                'address' => '',
                'city' => '',
                'state' => '',
                'postal_code' => ''
            );
            $viewclass->account = new \stdClass();
            $viewclass->account = array(
                'name' => '',
                'open_date' => '',
                'last_activity_date' => '',
                'status' => ''
            );
            $viewclass->account_id = 0;
            
            $custID = DB::table('customer')
            ->rightJoin('account', 'customer.cust_id', '=', 'account.cust_id')
            ->rightJoin('product', 'account.product_cd', '=', 'product.product_cd')
            ->where('customer.fed_id', '=', $account)
            ->get();
            
            // Order the data into viewable sections
            if(isset($custID) > 0)
            {
                $db = (string) Str::uuid();
                session(['debounce' => $db]);
                foreach ($custID as $k => $v)
                {
                    $views[$k] = clone $viewclass;
                    foreach($v as $vk => $vv)
                    {
                        if(array_key_exists($vk, $views[$k]->address))
                        {
                            $views[$k]->address[$vk] = array('label' => str_replace('_', ' ', ucfirst($vk)), 'value' => $vv);
                        }
                        elseif(array_key_exists($vk, $views[$k]->account))
                        {
                            $views[$k]->account[$vk] = array('label' => str_replace('_', ' ', ucfirst($vk)), 'value' => $vv);
                        }
                        
                        //Get account ID
                        if($vk === 'account_id')
                        {
                            if(preg_match('/^[0-9]+$/', $vv))
                            {
                                $views[$k]->account_id = $vv;
                            }
                        }
                    }
                    if(isset($views[$k]->account_id))
                    { 
                        $trans[$k] = DB::table('transaction')
                        ->where('transaction.account_id', '=', $views[$k]->account_id)
                        ->get();
                    }
                }
                return view('searchResult')->with('vc', $views)
                                           ->with('th', $transheaders)
                                           ->with('trans', $trans)
                                           ->with('account', $req['account']);
            }
        }
    }
}
