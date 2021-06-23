<?php
namespace App\Helpers;
use Zendesk\API\HttpClient as ZendeskAPI;

class HubSpot {

    public function __construct() {
		
	}
    
    public static function createUser($input) {
        $data = [];
        $api_key = \Config::get('settings.HUBSPOT_KEY');
        $hubSpot = \HubSpot\Factory::createWithApiKey($api_key);
        $hubspotCompanyData = [
            'name' => $input['branch_name'],
            'state' => $input['branch_state'],
            'city' => $input['branch_city'],
            'country' => $input['branch_country'],
            'lead_source_1' => 'Marketing',
            'lead_source_2' => 'Facebook',
            'shipping_address' => $input['branch_address'],
            'red_bag_reserve' => $input['red_bag_reserve'],
            'rb_container_type' => $input['rb_container_type'],
            'sharps_reserve' => $input['sharps_reserve'],
            'sh_container_type' => $input['sh_container_type'],
            'wastetech_company_id' => $input['branch_uniq_id']
        ];
        $companyInput = new \HubSpot\Client\Crm\Companies\Model\SimplePublicObjectInput();
        $companyInput->setProperties($hubspotCompanyData);
        $response = $hubSpot->crm()->companies()->basicApi()->create($companyInput);

        /***check if contact exists */
        $contactId = null;
        $filter = new \HubSpot\Client\Crm\Contacts\Model\Filter();
        $filter
            ->setOperator('EQ')
            ->setPropertyName('email')
            ->setValue($input['contact_email']);

        $filterGroup = new \HubSpot\Client\Crm\Contacts\Model\FilterGroup();
        $filterGroup->setFilters([$filter]);
        $searchRequest = new \HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest();
        $searchRequest->setFilterGroups([$filterGroup]);
        $contactsPage = $hubSpot->crm()->contacts()->searchApi()->doSearch($searchRequest);
        foreach ($contactsPage->getResults() as $contact) {
            $contactId = $contact->getId();
        }
        /***check if contact exists */
        if(!$contactId) {
            $hubspotContactData = [
                'firstname' => $input['contact_firstname'],
                'lastname' => $input['contact_lastname'],
                'phone' => $input['contact_phone'],
                'email' => $input['contact_email'],
                'role' => $input['contact_role']
            ];
            $contactInput = new \HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput();
            $contactInput->setProperties($hubspotContactData);
            $contact = $hubSpot->crm()->contacts()->basicApi()->create($contactInput);
        }
        



        sleep(10);
        /*****Get the contact created */
        if(!$contactId) {
            $filter = new \HubSpot\Client\Crm\Contacts\Model\Filter();
            $filter
                ->setOperator('EQ')
                ->setPropertyName('email')
                ->setValue($input['contact_email']);

            $filterGroup = new \HubSpot\Client\Crm\Contacts\Model\FilterGroup();
            $filterGroup->setFilters([$filter]);
            $searchRequest = new \HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest();
            $searchRequest->setFilterGroups([$filterGroup]);
            $contactsPage = $hubSpot->crm()->contacts()->searchApi()->doSearch($searchRequest);
            foreach ($contactsPage->getResults() as $contact) {
                $contactId = $contact->getId();
            }
        }
        /*****Get the contact created */

        /*****Get the Company created */
        $filter = new \HubSpot\Client\Crm\Companies\Model\Filter();
        $filter
            ->setOperator('EQ')
            ->setPropertyName('name')
            ->setValue($input['branch_name']);

        $filterGroup = new \HubSpot\Client\Crm\Companies\Model\FilterGroup();
        $filterGroup->setFilters([$filter]);
        $searchRequest = new \HubSpot\Client\Crm\Companies\Model\PublicObjectSearchRequest();
        $searchRequest->setFilterGroups([$filterGroup]);
        $companyPage = $hubSpot->crm()->companies()->searchApi()->doSearch($searchRequest);
        print_r($companyPage);
        foreach ($companyPage->getResults() as $company) {
            $company_id = $company->getId();
        }
        /*****Get the Company created */
        
        $contact_id = $contactId;
        $to_object_id = $company_id;
        $to_object_type = "company";
        $association_type = 1;
        $association = $hubSpot->crm()->contacts()->associationsApi()->create($contact_id,$to_object_type,$to_object_id,$association_type);
        return array('contact_id'=>$contactId, 'company_id'=>$company_id);
    }
}