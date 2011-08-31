<?php

class EventsController extends AppController {

    var $name = 'Events';
    var $components = array('RequestHandler');
    var $types = array('Event', 'Call', 'Task', 'Note');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    function mytasks() {
        $this->loadModel('EventsUser');
        $this->EventsUser->bindModel(
                array('hasOne' => array(
                'EventsLead' => array(
                    'className' => 'EventsLead',
                    'foreignKey' => false,
                    'conditions' => 'EventsLead.events_id = EventsUser.events_id',
                    'fields' => '',
                    'order' => ''
                ),
                'Lead' => array(
                    'className' => 'Lead',
                    'foreignKey' => false,
                    'conditions' => 'Lead.id = EventsLead.leads_id',
                    'fields' => '',
                    'order' => ''
                ),
                'Event' => array(
                    'className' => 'Event',
                    'foreignKey' => false,
                    'conditions' => 'Event.id = EventsUser.events_id',
                    'fields' => '',
                    'order' => ''
                )
            )
                ), false
        );

        $this->paginate = array(
            'conditions' => array(
                'EventsUser.users_id' => $this->Session->read('Auth.User.id')
            ),
            'limit' => 50,
            'order' => array(
                'Event.id' => 'desc'
            ),
            'recursive' => 1
        );

        $this->EventsUser->recursive = 1;
        $this->set('events', $this->paginate('EventsUser'));
    }

    function add() {
        $message = 'false';
        if (!empty($this->data)) {
            try {
                $this->data['Event']['user_id'] = $this->Session->read('Auth.User.id');
                $this->data['Event']['date'] = date('Y-m-d H:i:s');

                // Create the new Event
                $this->Event->begin();
                $this->Event->create();

                $this->data['Event']['start_date'] = $this->data['Event']['start_date'] . ' ' . $this->data['Event']['start_hour'] . ':00';
                $this->data['Event']['end_date'] = $this->data['Event']['end_date'] . ' ' . $this->data['Event']['end_hour'] . ':00';

                // save lead details
                if ($this->Event->save($this->data)) {
                    if (isset($this->data['EventsUser']) && !empty($this->data['EventsUser'])) {
                        foreach ($this->data['EventsUser'] as $user) {
                            $user['events_id'] = $this->Event->id;
                            $this->Event->EventsUser->create();
                            if (!$this->Event->EventsUser->save($user))
                                throw new Exception("Cannot save");
                        }
                    }
                    if (isset($this->data['EventsRequest']) && !empty($this->data['EventsRequest'])) {
                        $this->data['EventsRequest']['events_id'] = $this->Event->id;
                        $this->Event->EventsRequest->create();
                        if (!$this->Event->EventsRequest->save($this->data))
                            throw new Exception("Cannot save");
                    }
                    if (isset($this->data['EventsLead']) && !empty($this->data['EventsLead'])) {
                        $this->data['EventsLead']['events_id'] = $this->Event->id;
                        $this->Event->EventsLead->create();
                        if (!$this->Event->EventsLead->save($this->data))
                            throw new Exception("Cannot save");
                    }
                    if (isset($this->data['EventsContact']) && !empty($this->data['EventsContact']) && $this->data['EventsContact']['contacts_id'] != NULL) {
                        $this->data['EventsContact']['events_id'] = $this->Event->id;
                        $this->Event->EventsContact->create();
                        if (!$this->Event->EventsContact->save($this->data))
                            throw new Exception("Cannot save");
                    }
                    $this->Event->commit();
                    $message = $this->Event->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Event->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->loadModel('User');
            if (!$this->isPartner()) {
                $this->set('users', $this->User->find('list', array('fields' => array('fullname'))));
            } else {
                $groups = array(2, $this->Auth->user('group_id'));
                $this->set('users', $this->User->find('list', array(
                            'fields' => array('fullname'),
                            'conditions' => array ('group_id' => $groups, 'active' => 1)
                                )
                        )
                );
            }
            $this->set('types', $this->types);
        }
    }

    function edit($id = null) {
        $message = 'false';
        if (!empty($this->data)) {
            try {
                $this->data['Event']['user_id'] = $this->Session->read('Auth.User.id');

                // Create the new Event
                $this->Event->begin();
                $this->Event->id = $id;

                $this->data['Event']['start_date'] = $this->data['Event']['start_date'] . ' ' . $this->data['Event']['start_hour'] . ':00';
                $this->data['Event']['end_date'] = $this->data['Event']['end_date'] . ' ' . $this->data['Event']['end_hour'] . ':00';

                // save lead details
                if ($this->Event->save($this->data)) {
                    if (isset($this->data['EventsUser']) && !empty($this->data['EventsUser'])) {
                        $this->Event->EventsUser->deleteAll(array('events_id' => $this->Event->id));
                        foreach ($this->data['EventsUser'] as $user) {
                            $user['events_id'] = $this->Event->id;
                            $this->Event->EventsUser->create();
                            if (!$this->Event->EventsUser->save($user))
                                throw new Exception("Cannot save");
                        }
                    }
                    if (isset($this->data['EventsRequest']) && !empty($this->data['EventsRequest'])) {
                        $this->data['EventsRequest']['events_id'] = $this->Event->id;
                        $this->Event->EventsRequest->create();
                        if (!$this->Event->EventsRequest->save($this->data))
                            throw new Exception("Cannot save");
                    }
                    if (isset($this->data['EventsLead']) && !empty($this->data['EventsLead'])) {
                        $this->data['EventsLead']['events_id'] = $this->Event->id;
                        $this->Event->EventsLead->create();
                        if (!$this->Event->EventsLead->save($this->data))
                            throw new Exception("Cannot save");
                    }
                    if (isset($this->data['EventsContact']) && !empty($this->data['EventsContact']) && $this->data['EventsContact']['contacts_id'] != NULL) {
                        $this->data['EventsContact']['events_id'] = $this->Event->id;
                        $this->Event->EventsContact->create();
                        if (!$this->Event->EventsContact->save($this->data))
                            throw new Exception("Cannot save");
                    }
                    $this->Event->commit();
                    $message = $this->Event->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Event->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->loadModel('User');
            $this->loadModel('Contact');
            if (!$this->isPartner()) {
                $this->set('users', $this->User->find('list', array('fields' => array('fullname'))));
            } else {
                $groups = array(2, $this->Auth->user('group_id'));
                $this->set('users', $this->User->find('list', array(
                            'fields' => array('fullname'),
                            'conditions' => array ('group_id' => $groups, 'active' => 1)
                                )
                        )
                );
            }
            $event = $this->Event->read(null, $id);
            $types = $this->types;
            $contact = $this->Contact->read(null, $event['EventsContact'][0]['contacts_id']);
            $this->set(compact('users', 'event', 'contact', 'types'));
        }
    }

    function setdone($id = null) {
        $message = 'false';
        try {
            // Create the new Event
            $this->Event->begin();
            $this->Event->id = $id;

            $this->data['Event']['closed'] = !$this->Event->field('closed');

            // save lead details
            if ($this->Event->save($this->data)) {


                $this->Event->commit();
                $message = $this->Event->id;
            } else {
                throw new Exception("Cannot save");
            }
        } catch (Exception $e) {
            $this->Event->rollback();
            $this->cakeError('error500');
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
            $this->Event->begin();
            if (!$this->Event->delete($id)) {
                throw new Exception("Cannot delete lead");
            }
            $this->Event->commit();
            $message = $id;
        } catch (Exception $e) {
            $this->Event->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

}

?>