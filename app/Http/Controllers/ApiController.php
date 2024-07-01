<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Nowpayment;

class ApiController extends Controller
{
    public function e_check(request $request){

        $get_user =  User::where('email', $request->email)->first() ?? null;

        if($get_user == null){

            return response()->json([
                'status' => false,
                'message' => 'No user found, please check email and try again',
            ]);
        }


        return response()->json([
            'status' => true,
            'user' => $get_user->username,
        ]);

    }


    public function e_fund(request $request){

        $get_user =  User::where('email', $request->email)->first() ?? null;

        if($get_user == null){

            return response()->json([
                'status' => false,
                'message' => 'No user found, please check email and try again',
            ]);
        }

        User::where('email', $request->email)->increment('money', $request->amount) ?? null;

        $amount = number_format($request->amount, 2);

        $get_depo = Nowpayment::where('invoice_id', $request->order_id)->first() ?? null;
        if ($get_depo == null){
            $trx = new Nowpayment();
            $trx->invoice_id = $request->order_id;
            $trx->user_id = $get_user->id;
            $trx->payment_status = "Success";
            $trx->amount = $request->amount;
            $trx->save();
        }else{
            Nowpayment::where('invoice_id', $request->order_id)->update(['payment_status'=> "Success"]);
        }

        return response()->json([
            'status' => true,
            'message' => "NGN $amount has been successfully added to your wallet",
        ]);

    }


    public function verify_username(request $request)
    {

        $get_user =  User::where('email', $request->email)->first() ?? null;

        if($get_user == null){

            return response()->json([
                'username' => "Not Found, Pleas try again"
            ]);

        }

        return response()->json([
            'username' => $get_user->username
        ]);



    }

}
