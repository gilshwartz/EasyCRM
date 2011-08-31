<?php

class CompaniesController extends AppController {

    var $name = 'Companies';
    var $components = array('RequestHandler');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    function index() {
        $this->paginate = array(
            'order' => array(
                'Company.name' => 'asc'
            ),
            'recursive' => 1,
            'conditions' => array('group_id' => $this->Auth->user('group_id'))
        );
        $this->set('editable', ($this->isAdmin() || $this->isCompany()));
        $this->set('companies', $this->paginate());
    }

    function add() {
        if (!empty($this->data)) {
            $message = 'false';
            try {
                $this->data['Company']['group_id'] = $this->Auth->user('group_id');

                // Create the new Company
                $this->Company->begin();
                $this->Company->create();

                // save lead details
                if ($this->Company->save($this->data)) {
                    $this->Company->commit();
                    $message = $this->Company->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Company->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        }
        $this->set('countries', $this->Company->Country->find('list'));
    }

    function edit($id = null) {
        if ($id == null)
            $this->cakeError('error404');
        $message = 'false';
        if (!empty($this->data)) {
            try {
                // Create the new Event
                $this->Company->begin();
                $this->Company->id = $id;
                
                if(!isset($this->data['Company']['is_partner']) || $this->data['Company']['is_partner'] == "")
                    $this->data['Company']['is_partner'] = 0;

                // save company details
                if ($this->Company->save($this->data)) {
                    $this->Company->commit();
                    $message = $this->Company->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Company->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->set('company', $this->Company->read(null, $id));
            $this->set('countries', $this->Company->Country->find('list'));
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->Company->begin();
            if (!$this->Company->delete($id)) {
                throw new Exception("Cannot delete company");
            }
            $this->Company->commit();
            $message = $id;
        } catch (Exception $e) {
            $this->Company->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    function getleads($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('Lead');
        $options = array(
            'fields' => array('Lead.*'),
            'conditions' => array('Lead.company_id' => $id)
        );
        $this->set('leads', $this->Lead->find('all', $options));
    }

    function checkvat($uevat) {
        ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
        $client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");

        $parameters->countryCode = substr($uevat, 0, 2);
        $parameters->vatNumber = substr($uevat, 2, strlen($uevat));

        try {
            $result = $client->checkVat($parameters);

            $results['uevat']['countryCode'] = $result->countryCode;
            $results['uevat']['vatNumber'] = $result->vatNumber;
            $results['uevat']['requestDate'] = $result->requestDate;
            $results['uevat']['valid'] = $result->valid;
            $results['uevat']['name'] = $result->name;
            $results['uevat']['address'] = $result->address;

            $this->set('results', $results);
            $this->set('tag', true);
        } catch (Exception $exception) {
            $result = 'Error : ' . $exception->getMessage();
        }
        $this->render('/ajax/results_xml');
    }

}

?>