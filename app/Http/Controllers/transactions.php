<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class transactions extends Controller
{
    private $expected;
    private $received;
    private $process;
    private $req;
    
    private function postcheck()
    {
        $datatype = array(
            'int' => '/^[0-9]+$/',
            'double' => '/^[0-9]+(\.[0-9]{2})*$/',
            'string' => '/^.*$/'
        );
        foreach($this->expected as $k => $v)
        {
            $this->received[$k] = '0';
            if(isset($this->req[$k]))
            {
                if(is_array($v))
                {
                    if(in_array($this->req[$k], $v))
                    {
                        $this->process[$k] = $this->req[$k];
                        $this->received[$k] = '1';
                    }
                }
                elseif(preg_match($datatype[$v], $this->req[$k]))
                {
                    $this->process[$k] = $this->req[$k];
                    $this->received[$k] = '1';
                }
            }
        }
    }
    
    public function addTransaction($req)
    {
        $date_time = new \DateTime();
        $this->req = $req;
        $num = (int)$req['type'];
        
        if(is_numeric($num))
        {
            switch($num)
            {
                // Update the address
                case 1:
                    $this->expected = array('address' => 'string', 'city' => 'string', 'state' => 'string', 'postal_code' => 'string');
                    $this->received = array();
                    $this->process = array();
                    if(isset($this->req['account']))
                    {
                        $this->postcheck();
                        
                        if(!in_array('0', $this->received))
                        {
                            $affected = DB::table('customer')
                            ->where('fed_id', $this->req['account'])
                            ->update($this->process);
                        }
                    }
                break;
                // Insert a transaction
                case 2:
                    $this->expected = array('account_id' => 'int', 'amount' => 'double', 'txn_type_cd' => array('CDT', 'DBT'));
                    $this->received = array();
                    $this->process = array(
                        'txn_date' => $date_time->format('Y-m-d H:i:s'),
                        'teller_emp_id' => 11,
                        'execution_branch_id' => 2
                    );
                    $this->postcheck();
                    if(!in_array('0', $this->received))
                    {
                        DB::table('transaction')->insert($this->process);
                    }
                   
                break;
            }
        }
    }
}
