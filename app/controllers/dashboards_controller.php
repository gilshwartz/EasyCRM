<?php

/**
 * Description of SetupController
 *
 * @author ffurlanetto
 */
class DashboardsController extends AppController {

    var $name = 'Dashboards';
    var $uses = array();
    var $components = array('RequestHandler');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    public function index() {
        $this->loadModel('EventsUser');
        $this->loadModel('Request');
        $this->loadModel('Lead');
        $this->loadModel('Log');

        $LeadOptions['conditions'] = array(
            'user_id' => $this->Session->read('Auth.User.id'),
            'status NOT' => array('avangate', 'client', 'lost')
        );

        $EventOptions['conditions'] = array(
            'users_id' => $this->Session->read('Auth.User.id'),
            'closed' => '0',
            'type <>' => 'Note'
        );
        
        $LogOptions['limit'] = 5;

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
                        )
                    )
                )
        );
        if ($this->Auth->user('role_id') < 3)
            $this->set('newrequests', $this->Request->getNbNewRequest($this->Auth->user('group_id'), $this->isPartner()));
        else
            $this->set('newrequests', 0);
        $this->set('pendingrequests', $this->Request->getNbPendingRequest($this->Session->read('Auth.User.id')));
        $this->set('leads', $this->Lead->find('all', $LeadOptions));
        $this->set('events', $this->EventsUser->find('all', $EventOptions));
        $this->set('logs', $this->Log->find('all', $LogOptions));
    }
}

?>
