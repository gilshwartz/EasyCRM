<?php

/**
 * Description of SetupController
 *
 * @author ffurlanetto
 */
class RequestsController extends AppController {

    var $name = 'Requests';
    var $components = array('RequestHandler', 'Email');
    var $helpers = array('xml', 'csv');
    var $paginate = array(
        'limit' => 50,
        'order' => array(
            'Request.id' => 'desc'
        )
    );

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    public function newrequests() {
        $this->loadModel('Country');
        $this->loadModel('User');
        $this->loadModel('Group');

        // Load New Request data
        $options = Array();
        if (!$this->isPartner()) {
            $options['conditions'] = array(
                'lower(status)' => 'open',
                'user' => null,
                'or' => array(
                    array('partner' => null),
                    array('partner' => $this->Auth->user('group_id'))
                )
            );
        } else {
            $options['conditions'] = array(
                'and' => array(
                    'lower(status)' => 'open',
                    'user' => null,
                    'partner' => $this->Auth->user('group_id')
                )
            );
        }

        $newrequests = $this->Request->find('all', $options);

        // Load Pending Approuvals requests
        $options = Array();
        if (!$this->isPartner()) {
            $options['conditions'] = array(
                'lower(status)' => 'open',
                'user NOT' => null,
                'accepted' => 0,
                'or' => array(
                    array('partner' => null),
                    array('partner' => $this->Auth->user('group_id'))
                )
            );
        } else {
            $options['conditions'] = array(
                'and' => array(
                    'lower(status)' => 'open',
                    'user NOT' => null,
                    'accepted' => 0,
                    'partner' => $this->Auth->user('group_id')
                )
            );
        }

        $this->paginate['conditions'] = $options['conditions'];
        $this->paginate['limit'] = 20;
        $pendingrequests = $this->paginate('Request');

        $this->set('newrequests', $newrequests);
        $this->set('pendingrequests', $pendingrequests);
        $this->set('countries', $this->Country->find('list'));
    }

    public function myrequests() {
        $this->loadModel('Country');
        $this->loadModel('User');
        $this->loadModel('Group');

        $options = Array();
        $options['conditions'] = array(
            'user' => $this->Session->read('Auth.User.id')
        );
        $this->Request->recursive = 1;
        $this->paginate['conditions'] = $options['conditions'];
        $requests = $this->paginate('Request');

        $this->set('requests', $requests);
        $this->set('countries', $this->Country->find('list'));
        $this->set('groups', $this->Group->find('list', array('conditions' => array('id !=' => 1))));
        $this->set('users', $this->User->find('list'));
    }

    public function view($id = null, $readonly = false) {
        if ($id != null && $id != '') {
            $request = $this->Request->findById($id);
            if (!empty($request)) {
                $this->loadModel('Lead');
                $this->loadModel('Country');
                $this->loadModel('Event');
                $this->loadModel('LeadsRequest');
                $this->loadModel('Event');
                $this->set('linkedlead', $this->LeadsRequest->find(
                                'first', array(
                            'conditions' => array(
                                'requests_id' => $id
                            ),
                            'fields' => array('LeadsRequest.leads_id')
                                )
                        )
                );
                $this->set('request', $request);
                $this->set('countries', $this->Country->find('list'));
                if (!$this->isPartner()) {
                    $this->set('leads', $this->Lead->find('list', array('order' => array('name' => 'asc'))));
                } else {
                    $this->loadModel('User');
                    $users = $this->User->find('list', array('conditions' => array('group_id' => $this->Auth->user('group_id'))));
                    $this->set('leads', $this->Lead->find('list', array(
                                'order' => array('name' => 'asc'),
                                'conditions' => array('user_id' => array_keys($users))
                                    )
                            )
                    );
                }
                $this->Event->recursive = -1;
                $tmp = $this->Event->find('all', array('fields' => array('Event.id', 'Event.subject', 'Event.message', 'Event.type', 'Event.date')));
                $events = array();
                foreach ($tmp as $val) {
                    $events[$val['Event']['id']] = array(
                        'subject' => $val['Event']['subject'],
                        'message' => $val['Event']['message'],
                        'type' => $val['Event']['type'],
                        'date' => $val['Event']['date'],
                    );
                }
                $this->set('events', $events);
            } else {
                $this->cakeError('error404');
            }
        } else {
            $this->cakeError('error404');
        }
    }

    public function delete($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->Request->begin();
            if (!$this->Request->delete($id)) {
                throw new Exception("Cannot delete request");
            }
            $this->Request->commit();
            $message = $id;
        } catch (Exception $e) {
            $this->Request->rollback();
            $this->cakeError('error404');
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    public function assign() {
        if (!$this->isPartner()) {
            $this->loadModel('User');
            $this->loadModel('Group');
            $this->set('groups', $this->Group->find('list', array('conditions' => array('id !=' => 1))));
            $this->set('users', $this->User->find('list'));
        } else {
            $this->set('users', $this->User->find('list', array('conditions' => array('group_id' => $this->Auth->user('group_id')))));
        }
    }

    public function accept($id = false) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->Request->id = $id;
            $this->Request->begin();
            $this->data['Request']['accepted'] = true;
            if (!$this->Request->save($this->data)) {
                throw new Exception("Cannot save request");
            }

            // check if linked to an opportunity and take the opportunity ownership.
            $this->loadModel('LeadsRequest');
            $this->loadModel('Lead');
            $leadrequest = $this->LeadsRequest->find(
                            'first', array(
                        'conditions' => array(
                            'requests_id' => $id
                        )
                            )
            );
            if (!empty($leadrequest)) {
                $this->Lead->id = $leadrequest['LeadsRequest']['leads_id'];
                $lead['user_id'] = $this->Auth->user('id');
                if (!$this->Lead->save($lead)) {
                    throw new Exception("Cannot take opportunity ownership");
                }
            }
            $this->Request->commit();
            $message = $this->Request->id;

            // Load required models
            $this->loadModel('Country');
            $this->loadModel('User');

            // retrieve to user
            $user = $this->User->findById($this->Session->read('Auth.User.id'));

            // Prepare to send email notification
            $this->Email->from = "PlanningForce Team <administrator@planningforce.com>";
            $this->Email->to = "crm@planningforce.com";
            $this->Email->subject = "Accepted Request";
            $this->Email->template = "assign_request";
            $this->Email->layout = "notify";
            $this->Email->sendAs = "html";
            $this->Email->smtpOptions = array(
                'port' => '465',
                'timeout' => '30',
                'host' => 'ssl://smtp.gmail.com',
                'username' => 'administrator@planningforce.com',
                'password' => 'yRP24%f4X&?',
            );
            $this->Email->delivery = 'smtp';

            // retrieve request details to attach in message
            $request = $this->Request->findById($id);
            $this->set('request', $request);
            $this->set('countries', $this->Country->find('list'));
            $this->set('subject', "The following request has been accepted by " . $user['User']['fullname']);

            // send email
            //$this->Email->send();
        } catch (Exception $e) {
            $this->Request->rollback();
            $this->cakeError('error404');
        }

        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    public function refuse($id = false) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->Request->id = $id;
            $this->Request->begin();
            $this->data['Request']['accepted'] = false;
            $this->data['Request']['user'] = NULL;
            if (!$this->Request->save($this->data)) {
                throw new Exception("Cannot save request");
            }
            $this->Request->commit();
            $message = $this->Request->id;

            // Load required models
            $this->loadModel('Country');
            $this->loadModel('User');

            // retrieve to user
            $user = $this->User->findById($this->Session->read('Auth.User.id'));

            // Prepare to send email notification
            $this->Email->from = "PlanningForce Team <administrator@planningforce.com>";
            $this->Email->to = "crm@planningforce.com";
            $this->Email->subject = "Declined Request";
            $this->Email->template = "assign_request";
            $this->Email->layout = "notify";
            $this->Email->sendAs = "html";
            $this->Email->smtpOptions = array(
                'port' => '465',
                'timeout' => '30',
                'host' => 'ssl://smtp.gmail.com',
                'username' => 'administrator@planningforce.com',
                'password' => 'yRP24%f4X&?',
            );
            $this->Email->delivery = 'smtp';

            // retrieve request details to attach in message
            $request = $this->Request->findById($id);
            $this->set('request', $request);
            $this->set('countries', $this->Country->find('list'));
            $this->set('subject', "The following request has been declined by " . $user['User']['fullname']);

            // send email
            $this->Email->send();
        } catch (Exception $e) {
            $this->Request->rollback();
            $this->cakeError('error404');
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    public function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->Request->id = $id;
            if (!empty($this->data)) {
                $this->Request->begin();
                if (isset($this->data['Request']['status']) && strtolower($this->data['Request']['status']) == 'close')
                    $this->data['Request']['dateOut'] = date('Y-m-d H:i:s');
                if (!$this->Request->save($this->data)) {
                    throw new Exception("Cannot save request");
                }
                $this->Request->commit();
                $message = $this->Request->id;

                if (isset($this->data['Request']['user'])) {
                    // Load required models
                    $this->loadModel('Country');
                    $this->loadModel('User');

                    // retrieve to user
                    $user = $this->User->findById($this->data['Request']['user']);

                    // Prepare to send email notification
                    $this->Email->from = "PlanningForce Team <administrator@planningforce.com>";
                    $this->Email->to = $user['User']['email'];
                    $this->Email->subject = "New Request Assigned";
                    $this->Email->template = "assign_request";
                    $this->Email->layout = "notify";
                    $this->Email->sendAs = "html";
                    $this->Email->smtpOptions = array(
                        'port' => '465',
                        'timeout' => '30',
                        'host' => 'ssl://smtp.gmail.com',
                        'username' => 'administrator@planningforce.com',
                        'password' => 'yRP24%f4X&?',
                    );
                    $this->Email->delivery = 'smtp';

                    // retrieve request details to attach in message
                    $request = $this->Request->findById($id);
                    $this->set('request', $request);
                    $this->set('countries', $this->Country->find('list'));
                    $this->set('subject', "New Request Assigned");

                    // send email
                    $this->Email->send();
                }
            }
        } catch (Exception $e) {
            $this->Request->rollback();
            $this->cakeError('error404');
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    public function newest() {
        if ($this->Auth->user('role_id') < 3)
            $this->set('request', $this->Request->getNbNewRequest($this->Auth->user('group_id'), $this->isPartner()));
        else
            $this->set('request', 0);
    }

    public function pending() {
        $this->set('request', $this->Request->getNbPendingRequest($this->Session->read('Auth.User.id')));
    }

}

?>
