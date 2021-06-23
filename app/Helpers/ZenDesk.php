<?php
namespace App\Helpers;
use Zendesk\API\HttpClient as ZendeskAPI;

class ZenDesk {
    public function __construct() {
		
	}
    public static function createUser($input) {
		$data = [];
        $subdomain = \Config::get('settings.ZENDESK_SUBDOMAIN');
        $username  = \Config::get('settings.ZENDESK_USER'); // replace this with your registered email
        $token     = \Config::get('settings.ZENDESK_TOKEN'); // replace this with your token
        $client = new ZendeskAPI($subdomain);
        $client->setAuth('basic', ['username' => $username, 'token' => $token]);
        $params = array('query' => $input['contact_email']);
        $search = $client->users()->search($params);
        if (empty($search->users)) {
            $userData = [
                'name' => $input['contact_firstname'].' '.$input['contact_lastname'],
                'email' => $input['contact_email'],
                'phone' => '+1'.$input['contact_phone'],
                'wastetech_user_id' => $input['contact_id']
            ];
            $newUser = $client->users()->create($userData);
            $created_user = $newUser->user->id;
        } else {
            foreach ($search->users as $UserData) {
                $created_user = $UserData->id;
            }
        }
        $organisationData = [
            'external_id' => $input['branch_uniq_id'],
            'name' => $input['branch_name'],
            'wastetech_org_id' => $input['branch_uniq_id'],
            'organization_fields' => array('shipping_address' => $input['branch_address'])
        ];
        $newOrganization = $client->organizations()->create($organisationData);
        return array('contact_id'=> $created_user, 'company_id'=>$newOrganization->organization->id);
	}
}