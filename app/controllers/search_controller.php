<?php

class SearchController extends AppController {

    var $name = 'Search';
    var $uses = array('Contact');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    function index($critera = null) {
        if ($critera != null) {
            $this->loadModel('Contact');
            $this->loadModel('Request');
            $this->loadModel('Company');
            $this->loadModel('Document');
            $this->loadModel('Lead');
            $this->loadModel('License');

            $optionContact = array(
                'conditions' => array(
                    'or' => array(
                        'Contact.firstname LIKE' => '%'.$critera.'%',
                        'Contact.lastname LIKE' => '%'.$critera.'%',
                        'Contact.emails LIKE' => '%'.$critera.'%',
                        'Contact.phones LIKE' => '%'.$critera.'%',
                        'Contact.company_name LIKE' => '%'.$critera.'%',
                        'Contact.fullname LIKE' => '%'.$critera.'%',
                        'UPPER(Country.name) LIKE' => '%'.strtoupper($critera).'%',
                    )
                )
            );
            $optionCompanies = array(
                'conditions' => array(
                    'or' => array(
                        'Company.name LIKE' => '%'.$critera.'%',
                        'Company.description LIKE' => '%'.$critera.'%',
                        'Company.address LIKE' => '%'.$critera.'%',
                        'Company.phone LIKE' => '%'.$critera.'%',
                        'Company.vat LIKE' => '%'.$critera.'%',
                    )
                )
            );
            $optionDocuments = array(
                'conditions' => array(
                    'or' => array(
                        'Document.name LIKE' => '%'.$critera.'%'
                    )
                )
            );
            $optionLeads = array(
                'conditions' => array(
                    'or' => array(
                        'Lead.name LIKE' => '%'.$critera.'%',
                        'Lead.description LIKE' => '%'.$critera.'%',
                    )
                )
            );
            $optionLicenses = array(
                'conditions' => array(
                    'or' => array(
                        'License.serialkey LIKE' => '%'.$critera.'%',
                    )
                )
            );
            $optionRequests = array(
                'conditions' => array(
                    'or' => array(
                        'RequestTrial.installation_id LIKE' => '%'.$critera.'%',
                    )
                )
            );

            $contacts = $this->Contact->find('all', $optionContact);
            $companies = $this->Company->find('all', $optionCompanies);
            $documents = $this->Document->find('all', $optionDocuments);
            $leads = $this->Lead->find('all', $optionLeads);
            $licenses = $this->License->find('all', $optionLicenses);
            $requests = $this->Request->find('all', $optionRequests);

            $this->set('results', array_merge($leads, $licenses, $companies, $contacts, $documents, $requests));
        }
    }

}

?>