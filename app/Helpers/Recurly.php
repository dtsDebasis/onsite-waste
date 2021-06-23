<?php
namespace App\Helpers;

class Recurly {
    public function __construct() {
		
	}
    public static function createUser($input) {
        $data = [];
        $api_key = \Config::get('settings.RECURLY_KEY');
        $client = new \Recurly\Client($api_key);
        $account_create = [
            "code" => $input['branch_uniq_id'],
            "company" => $input['branch_name'],
            "first_name" => $input['branch_name'],
            "last_name" => "",
            "email" => $input['branch_email'],
            "shipping_addresses" => [
                [
                    "first_name" => $input['contact_firstname'],
                    "last_name" => $input['contact_lastname'],
                    "street1" => $input['branch_address'],
                    "city" => $input['branch_city'],
                    "postal_code" => $input['branch_postcode'],
                    "country" => $input['branch_country']
                ]
            ]
        ];
    
        $response = $client->createAccount($account_create);

        /****billing info not needed */

        /*$binfo_update = [
            "first_name" => "Douglas",
            "last_name" => "Du Monde",
        ];
        $binfo = $client->updateBillingInfo($account_id, $binfo_update);*/
        
        /****billing info not needed */   
        // return $account_create;
        return array( 'company_id'=>$response->getId());
    }
}