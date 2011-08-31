<?php

class LeadsController extends AppController {

    var $name = 'Leads';
    var $components = array('RequestHandler');
    var $paginate = array(
        'limit' => 50
    );

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }
    
    function opportunities() {
        $this->Lead->recursive = 1;
        $this->loadModel('User');
        $groups = $this->_getSubGroups($this->Auth->user('group_id'));
        $options['conditions'] = array(
            'User.group_id' => $this->_getSubGroups($this->Auth->user('group_id'))
        );
        $users = array_keys($this->User->find('list', $options));
        $this->paginate['conditions'] = array(
            'user_id' => $users,
            'status NOT' => array('avangate', 'client', 'lost')
        );
        $this->set('leads', $this->paginate());
    }

    function myopportunities() {
        $this->Lead->recursive = 1;
        $this->paginate['conditions'] = array(
            'user_id' => $this->Session->read('Auth.User.id'),
            'status NOT' => array('avangate', 'client', 'lost')
        );
        $this->set('leads', $this->paginate());
    }
    
    function mywons() {
        $this->Lead->recursive = 1;
        $this->paginate['conditions'] = array(
            'user_id' => $this->Session->read('Auth.User.id'),
            'status' => array('avangate', 'client')
        );
        $this->set('leads', $this->paginate());
    }

    function mylosts() {
        $this->Lead->recursive = 1;
        $this->paginate['conditions'] = array(
            'user_id' => $this->Session->read('Auth.User.id'),
            'status' => array('lost')
        );
        $this->set('leads', $this->paginate());
    }

    function monitor() {
        $this->loadModel('Group');

        $options['conditions'] = array(
            'Group.id' => $this->_getSubGroups($this->Auth->user('group_id'))
        );
        $this->Group->recursive = 0;
        $this->set('groups', $this->Group->find('list', $options));
    }

    function monitordetails($groupId = null) {
        $this->loadModel('User');
        $usersOptions['fields'] = array('fullname');
        $usersOptions['conditions'] = array('group_id' => $groupId);

        $childrens = $this->User->find('list', $usersOptions);
        $users = array();
        foreach ($childrens as $key => $value)
            array_push($users, $key);

        $options['conditions'] = array(
            'user_id' => $users,
            'status NOT' => array('avangate', 'client', 'lost')
        );

        $this->set('leads', $this->Lead->find('all', $options));
        $this->set('users', $childrens);
    }

    function view($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $lead = $this->Lead->read(null, $id);
        if (base64_decode($lead['Lead']['description'], true))
            $lead['Lead']['description'] = base64_decode($lead['Lead']['description']);
        $partner_company = $this->Lead->Company->read('is_partner', $lead['Lead']['company_id']);
        $this->set('token', strtoupper(substr(sha1($id), 0, 5)));        
        $this->set('lead', $lead);
        $this->set('partner_company', $partner_company);
        $this->set('companies', $this->Lead->Company->find('list', array('order' => 'Company.name', 'conditions' => array('group_id' => $this->Auth->user('group_id')))));
        if (! $this->isPartner()) {
            $this->set('users', $this->Lead->User->find('list'));
        } else {            
            $this->set('users', $this->User->find('list', array('conditions' => array('group_id' => $this->Auth->user('group_id')))));
        }

        $this->render('/leads/view/view');
    }

    function add() {
        $message = 'false';
        try {
            // Set the default name
            if (!isset($this->data['Lead']['name']) || empty($this->data['Lead']['name']))
                $this->data['Lead']['name'] = 'New Opportunity';

            // Set the AM
            if (!isset($this->data['Lead']['user_id']) || empty($this->data['Lead']['user_id']))
                $this->data['Lead']['user_id'] = $this->Session->read('Auth.User.id');

            // Initialise object history
            $this->data['Lead']['history'] = serialize(array(
                        time() => array(
                            'user' => $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.lastname'),
                            'message' => 'Lead created by ' . $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.lastname')
                        )
                    ));
            $nextMonth = time() + (31 * 24 * 60 * 60);
            $this->data['Lead']['forecast_close'] = date('Y-m-d H:i:s', $nextMonth);

            // Create the new Lead
            $this->Lead->begin();
            $this->Lead->create();

            // save lead details
            if ($this->Lead->save($this->data)) {

                // find the contact attach to request and attach it to the lead's contacts
                if (!empty($this->data['LeadsRequest'])) {
                    $this->data['LeadsRequest']['leads_id'] = $this->Lead->id;
                    $this->Lead->LeadsRequest->create();
                    if (!$this->Lead->LeadsRequest->save($this->data)) {
                        throw new Exception("Cannot save request");
                    }
                }
                if (!empty($this->data['LeadsContact'])) {
                    $this->data['LeadsContact']['leads_id'] = $this->Lead->id;
                    $this->Lead->LeadsContact->create();
                    if (!$this->Lead->LeadsContact->save($this->data)) {
                        throw new Exception("Cannot save contact");
                    }
                }
                $this->Lead->commit();
                $message = $this->Lead->id;
            } else {
                throw new Exception("Cannot save");
            }
        } catch (Exception $e) {
            $this->Lead->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            if (!empty($this->data)) {
                
                $this->Lead->id = $id;
                $history = unserialize($this->Lead->field('history'));
                $history[time()] = array(
                    'user' => $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.lastname'),
                    'message' => $this->data['Lead']['msg']
                );

                $this->data['Lead']['history'] = serialize($history);
                
                $this->data['Lead']['description'] = base64_encode($this->data['Lead']['description']);

                $owner = $this->Lead->field('user_id'); 
                if ($owner != $this->data['Lead']['user_id']){
                    $this->loadModel('User');
                    $this->User->id = $this->data['Lead']['user_id'];
                    $user = $this->User->read('group_id');
                    
                    $this->Lead->Company->recursive = -1;
                    $company = $this->Lead->Company->findById($this->data['Lead']['company_id']);                    
                    unset($company['Company']['id']);
                    $company['Company']['group_id'] = $user['User']['group_id'];
                    
                    $companyExist = $this->Lead->Company->find('first', 
                            array('conditions' => 
                                array(
                                    'name' => $company['Company']['name'], 
                                    'group_id' => $user['User']['group_id']
                                    )
                                )
                            );
                    if (empty($companyExist)) {                    
                        $this->Lead->Company->begin();
                        $this->Lead->Company->create();
                        if ($this->Lead->Company->save($company)) {
                            $this->Lead->Company->commit();
                            $this->data['Lead']['company_id'] = $this->Lead->Company->id;
                        } else {
                            $this->Lead->Company->rollback();
                        }
                    } else {
                        $this->data['Lead']['company_id'] = $companyExist['Company']['id'];
                    }
                }
                
                $this->Lead->begin();
                if (!$this->Lead->save($this->data)) {
                    throw new Exception("Cannot save lead");
                }
                $this->Lead->commit();
                $message = $this->Lead->id;
            }
        } catch (Exception $e) {
            $this->Lead->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    function delete($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->Lead->begin();
            if (!$this->Lead->delete($id)) {
                throw new Exception("Cannot delete lead");
            }
            $this->Lead->commit();
            $message = $id;
        } catch (Exception $e) {
            $this->Lead->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    function addcontact($id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            if (!empty($this->data)) {
                $this->loadModel('LeadsContact');

                $this->Lead->id = $id;
                $history = unserialize($this->Lead->field('history'));
                $history[time()] = array(
                    'user' => $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.lastname'),
                    'message' => "Add contact to lead"
                );

                $contact['Lead']['history'] = serialize($history);
                $contact['LeadsContact']['leads_id'] = $id;
                $contact['LeadsContact']['contacts_id'] = $this->data['Contact']['contacts_id'];
                $this->LeadsContact->begin();
                $this->LeadsContact->create();
                if (!$this->LeadsContact->save($contact) || !$this->Lead->save($contact)) {
                    throw new Exception("Cannot save lead");
                }
                $this->LeadsContact->commit();
                $message = $id;
            }
        } catch (Exception $e) {
            $this->LeadsContact->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    function removecontact($id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            if (!empty($this->data)) {
                $this->loadModel('LeadsContact');

                $this->Lead->id = $id;
                $history = unserialize($this->Lead->field('history'));
                $history[time()] = array(
                    'user' => $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.lastname'),
                    'message' => "Remove contact from lead"
                );

                $contact['Lead']['history'] = serialize($history);

                $this->LeadsContact->begin();
                if (!$this->LeadsContact->delete($this->data['LeadsContact']['id']) || !$this->Lead->save($contact)) {
                    throw new Exception("Cannot remove contact from lead");
                }
                $this->LeadsContact->commit();
                $message = $id;
            }
        } catch (Exception $e) {
            $this->LeadsContact->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    function contactsdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('LeadsContact');
        $option = array(
            'fields' => array('Contacts.*', 'LeadsContact.id'),
            'conditions' => array('leads_id' => $id)
        );
        $this->set('contacts', $this->LeadsContact->find('all', $option));
        $this->set('token', strtoupper(substr(sha1($id), 0, 5)));
        $this->render('/leads/view/contacts');
    }

    function documentsdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('Document');
        $option = array(
            'fields' => array('Document.*'),
            'conditions' => array('lead_id' => $id)
        );
        $this->set('documents', $this->Document->find('all', $option));
        $this->render('/leads/view/documents');
    }

    function history($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->Lead->id = $id;
        $this->set('history', $this->Lead->field('history'));

        $this->render('/leads/view/history');
    }

    function licensesdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('LicensesLead');

        $this->LicensesLead->bindModel(
                array('belongsTo' => array(
                        'Product' => array(
                            'className' => 'Product',
                            'foreignKey' => false,
                            'conditions' => 'Licenses.product_id = Product.id',
                        )
                    )
                )
        );

        $option = array(
            //'fields' => array('Licenses.*'),
            'conditions' => array('leads_id' => $id)
        );
        $this->set('licenses', $this->LicensesLead->find('all', $option));

        $this->render('/leads/view/licenses');
    }
    
    function offlinesdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('OfflineLic');
        $option = array(
            'conditions' => array('lead_id' => $id)
        );
        $this->set('licenses', $this->OfflineLic->find('all', $option));
        
        $this->loadModel('LeadsContact');
        $option = array(
            'conditions' => array('leads_id' => $id)
        );
        $this->LeadsContact->recursive = 1;
        $lcontacts = $this->LeadsContact->find('all', $option);
        $contacts = array();
        foreach ($lcontacts as $ctc) {
            $contacts[$ctc['Contacts']['id']] = $ctc['Contacts']['firstname']. ' ' .$ctc['Contacts']['lastname'];
        }
        $this->set('contacts', $contacts);

        $this->render('/leads/view/offlines');
    }

    function pluginsdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('PluginsLead');
        $this->PluginsLead->recursive = 1;

        $option = array(
            'conditions' => array('lead' => $id)
        );
        $this->set('plugins', $this->PluginsLead->find('all', $option));

        $this->render('/leads/view/plugins');
    }

    function notesdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('EventsLead');
        $option = array(
            'fields' => array('Events.*'),
            'conditions' => array('leads_id' => $id)
        );
        $this->set('events', $this->EventsLead->find('all', $option));
        $this->render('/leads/view/notes');
    }

    function ordersdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('Offer');
        $option = array(
            'fields' => array('Offer.*'),
            'conditions' => array('lead_id' => $id)
        );
        $this->set('offers', $this->Offer->find('all', $option));

        $this->render('/leads/view/offers');
    }

    function requestsdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('LeadsRequest');
        $option = array(
            'fields' => array('Requests.*'),
            'conditions' => array('leads_id' => $id)
        );
        $this->set('requests', $this->LeadsRequest->find('all', $option));
        //$this->set('lead', $this->Lead->read(null, $id));
        $this->set('users', $this->Lead->User->find('list', array('fields' => 'fullname')));
        $this->render('/leads/view/requests');
    }

    function emailsdetails($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $this->loadModel('Email');
        $option = array(
            'conditions' => array('lead_id' => $id)
        );
        $this->set('emails', $this->Email->find('all', $option));
        $this->render('/leads/view/emails');
    }

    function addRequest($id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            if (!empty($this->data)) {
                $this->loadModel('LeadsRequest');
                $this->loadModel('LeadsContact');
                $this->loadModel('Request');

                $this->Lead->id = $id;
                $this->Request->id = $this->data['LeadsRequest']['requests_id'];


                $history = unserialize($this->Lead->field('history'));
                $history[time()] = array(
                    'user' => $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.lastname'),
                    'message' => "Add a request to the lead"
                );

                $contact['Lead']['history'] = serialize($history);

                $this->data['LeadsRequest']['leads_id'] = $id;

                $this->LeadsRequest->begin();
                $this->LeadsRequest->create();

                $this->LeadsContact->id = $this->LeadsContact->find('first', array('conditions' => array('leads_id' => $id, 'contacts_id' => $this->Request->field('contact'))));
                if ($this->LeadsContact->id == NULL) {
                    $this->data['LeadsContact']['leads_id'] = $id;
                    $this->data['LeadsContact']['contacts_id'] = $this->Request->field('contact');
                    $this->LeadsContact->create();
                }

                if (!$this->LeadsRequest->save($this->data) || !$this->Lead->save($contact) || !$this->LeadsContact->save($this->data)) {
                    throw new Exception("Cannot add the request to the lead");
                }
                $this->LeadsRequest->commit();
                $message = $id;
            }
        } catch (Exception $e) {
            $this->LeadsRequest->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    function merge($from = null, $to = null) {
        if (!empty($this->data)) {
            $message = 'false';
            try {
                $this->Lead->begin();
                $this->Lead->id = $to;

                $history = unserialize($this->Lead->field('history'));
                $history[time()] = array(
                    'user' => $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.lastname'),
                    'message' => "Merge lead $to with lead $from"
                );
                $this->data['To']['Lead']['history'] = serialize($history);
                if (!$this->Lead->save($this->data['To'])) {
                    throw new Exception("Cannot save merged data");
                }
                $this->Lead->query("UPDATE leads_contacts SET leads_id = $to WHERE leads_id = $from");
                $this->Lead->query("UPDATE leads_documents SET leads_id = $to WHERE leads_id = $from");
                $this->Lead->query("UPDATE leads_requests SET leads_id = $to WHERE leads_id = $from");
                $this->Lead->query("UPDATE licenses_leads SET leads_id = $to WHERE leads_id = $from");
                $this->Lead->query("UPDATE events_leads SET leads_id = $to WHERE leads_id = $from");
                $this->Lead->query("UPDATE offers SET lead_id = $to WHERE lead_id = $from");

                $this->Lead->delete($from);

                $this->Lead->commit();
                $message = $to;
            } catch (Exception $e) {
                $this->LeadsRequest->rollback();
                $message = 'false : ' . $e->getMessage();
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } elseif (!$from || !$to) {
            $this->set('leads', $this->Lead->find('list', array('order' => 'Lead.name')));
            $this->render('merge1');
        } else {
            $this->set('from', $this->Lead->read(null, $from));
            $this->set('to', $this->Lead->read(null, $to));
            $this->set('companies', $this->Lead->Company->find('list'));
            $this->set('users', $this->Lead->User->find('list'));
            $this->render('merge2');
        }
    }
    
    public function lookup() {
        if (!empty($this->params['url']['q'])) {
            $q = $this->params['url']['q'];
            if (!$this->isPartner()) {
                $results = $this->Lead->find('list', array('conditions' => array('name LIKE' => '%' . $q . '%')));
            } else {
                $results = $this->Lead->find('list', array('conditions' => array('name LIKE' => '%' . $q . '%', 'user_id' => $this->Auth->user('id'))));
            }
            $this->set('results', $results);
            $this->layout = 'no_layout';
            $this->render('/ajax/results');
        } else {
            $this->cakeError('error404');
        }
    }

}
?>