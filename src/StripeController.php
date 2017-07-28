<?php

namespace Yokesharun\Stripe;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StripeController extends Controller
{
	/**
     * Set Stripe Key.
     *
     * @return \Illuminate\Http\Response
     */
    public function SetStripeKey(){
        return \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }


    /**
     * Get a stripe customer id.
     *
     * @return \Illuminate\Http\Response
     */
    public function CreateCustomer($email)
    {

        try{

            $this->SetStripeKey();
            $customer = \Stripe\Customer::create([
                'email' => $email,
            ]);

            return $customer['id'];

        } catch(Exception $e){
            return $e;
        }
    }



    /**
     * Get all customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetCustomers()
    {

        try{

            $this->SetStripeKey();
            return \Stripe\Customer::all();

        } catch(Exception $e){
            return $e;
        }
    }


    /**
     * Delete Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function DeleteCustomer($customer_id)
    {

        try{

            $this->SetStripeKey();
            $customer = \Stripe\Customer::retrieve($customer_id);
			return $customer->delete();

        } catch(Exception $e){
            return $e;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddCard($card_token,$customer_id,$email = null)
    {

        try{

        	if($customer_id == ''){
        		$customer_id = $this->CreateCustomer($email);
        	}
            
            $this->SetStripeKey();
            $customer = \Stripe\Customer::retrieve($customer_id);
            return $customer->sources->create(["source" => $card_token]);

        } catch(Exception $e){
            return $e;
            
        } 
    }



   	/**
     * Get all Added Cards.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetCards($customer_id)
    {
        try{

        	$this->SetStripeKey();

            return \Stripe\Customer::retrieve($customer_id)->sources->all([
            			'object' => 'card'
            	]);

        } catch(Exception $e){
            return $e;
        }
    }


   	/**
     * Delete Cards.
     *
     * @return \Illuminate\Http\Response
     */
    public function DeleteCard($customer_id, $card_id)
    {
        try{

        	$this->SetStripeKey();
			$customer = \Stripe\Customer::retrieve($card_id);
			return $customer->sources->retrieve($customer_id)->delete();

        } catch(Exception $e){
            return $e;
        }
    }


}
