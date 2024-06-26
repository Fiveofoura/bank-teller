<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankTeller extends Controller
{
    public function sacc(request $request)
    {
        $req = $request->all();
        
        $views = array();
        
        $account = trim($req['account']);
        if(preg_match('/^[0-9]+[0-9\-]+[0-9]+$/', $account))
        {  
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
                }
                return view('searchResult')->with('vc', $views)
                ->with('account', $req['account']);
            }
        }
    }
}
