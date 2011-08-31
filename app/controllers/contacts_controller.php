<?php

class ContactsController extends AppController {

    var $name = 'Contacts';
    var $components = array('RequestHandler');
    var $languages = array('English', 'French', 'Deutch', 'German', 'Chinese', 'Italian', 'Spanish');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    function add() {
        $message = 'false';
        if (!empty($this->data)) {
            try {
                // Create the new Company
                $this->Contact->begin();
                $this->Contact->create();

                // Serialize emails and phones
                $emails = array($this->data['Contact']['email1'], $this->data['Contact']['email2']);
                $phones = array($this->data['Contact']['phone1'], $this->data['Contact']['phone2']);

                $this->data['Contact']['emails'] = serialize($emails);
                $this->data['Contact']['phones'] = serialize($phones);

                // save lead details
                if ($this->Contact->save($this->data)) {
                    $this->Contact->commit();
                    $message = $this->Contact->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Contact->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->set('languages', $this->languages);
            $this->set('countries', $this->Contact->Country->find('list'));
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('error500');
        }
        if (!empty($this->data)) {
            try {
                $this->Contact->id = $id;

                $emails = array($this->data['Contact']['email1'], $this->data['Contact']['email2']);
                $phones = array($this->data['Contact']['phone1'], $this->data['Contact']['phone2']);

                $this->data['Contact']['emails'] = serialize($emails);
                $this->data['Contact']['phones'] = serialize($phones);

                $this->Contact->begin();
                if ($this->Contact->save($this->data)) {
                    $this->Contact->commit();
                    $message = $this->Contact->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Contact->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        }
        if (empty($this->data)) {
            $contact = $this->Contact->read(null, $id);
            $this->set('contact', $contact['Contact']);
            $this->set('languages', $this->languages);
            $this->set('countries', $this->Contact->Country->find('list'));
        }
    }

    public function delete($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->Contact->begin();
            if (!$this->Contact->delete($id)) {
                throw new Exception("Cannot delete contact");
            }
            $this->Contact->commit();
            $message = $id;
        } catch (Exception $e) {
            $this->Contact->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    public function autocomplete() {
        if (!empty($this->params['url']['q'])) {
            $q = $this->params['url']['q'];
            if (!$this->isPartner()) {
                $results = $this->Contact->find('list', array('conditions' => array('OR' => array('fullname LIKE' => '%' . $q . '%', 'company_name LIKE' => '%' . $q . '%'))));
            } else {
                $results = $this->Contact->find('list', array('conditions' =>
                            array(
                                'OR' => array('fullname LIKE' => '%' . $q . '%', 'company_name LIKE' => '%' . $q . '%'),
                            )
                                )
                );
            }
            $this->set('results', $results);
            $this->layout = 'no_layout';
            $this->render('/ajax/results');
        } else {
            $this->cakeError('error404');
        }
    }

    function getleads($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('LeadsContact');
        $options = array(
            'fields' => array('Leads.*'),
            'conditions' => array('LeadsContact.contacts_id' => $id)
        );
        $this->set('leads', $this->LeadsContact->find('all', $options));
    }

    function getrequests($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('Request');
        $options = array(
            'fields' => array('Request.*'),
            'conditions' => array('Contact.id' => $id)
        );
        $this->set('requests', $this->Request->find('all', $options));
    }

}

?>
