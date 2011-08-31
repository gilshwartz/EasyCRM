<?php

class ReportsController extends AppController {

    var $name = 'Reports';
    var $uses = array();
    var $components = array('RequestHandler');
    var $helpers = array('Cache');
    var $report = NULL;

    function beforeFilter() {
        parent::beforeFilter();
        $this->RequestHandler->setContent('xls', 'application/vnd.ms-excel');
    }

    public function index() {

    }

    public function export($report = null, $community = false) {
        $this->report = $report;

        if ($this->RequestHandler->accepts('xls')) {
            $this->RequestHandler->setContent('xls', 'application/vnd.ms-excel');
            Configure::write('debug',0);
        } else if ($this->RequestHandler->accepts('pdf')) {
            $this->RequestHandler->setContent('pdf', 'application/pdf');
            Configure::write('debug',0);
        } else if ($this->RequestHandler->accepts('csv')) {
            $this->RequestHandler->setContent('csv');
        } else {
            $this->RequestHandler->setContent('html');
        }

        if (!isset($this->params['form']['interval']))
            $this->params['form']['interval'] = NULL;
        $results = $this->$report($this->params['form']['interval']);

        if ($this->RequestHandler->isAjax())
            $report = $this->report . '_ajax';
        else
            $report = $this->report;
        $this->set(compact('results'));
        $this->render($report);
    }

    private function newslettercontacts($community) {
        $this->loadModel('Contact');
        $this->loadModel('Country');
        $this->Contact->recursive = -1;
        if ($community === false)
            $results['Contacts'] = $this->Contact->find('all', array('conditions' => array('Contact.newsletter' => 1), 'order' => array('Contact.country')));
        else
            $results['Contacts'] = $this->Contact->find('all', array('conditions' => array('Contact.community_newsletter' => 1), 'order' => array('Contact.country')));
        $results['Countries'] = $this->Country->find('list');
        return $results;
    }

    private function trialrequests($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }
        $this->loadModel('Request');
        $results['Products'] = $this->Request->query('SELECT t.product, count(*) as count FROM requests r JOIN request_trials t on t.request = r.id WHERE (r.dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND r.type = "Trial" GROUP BY t.product ORDER BY count DESC');
        $results['Country'] = $this->Request->query('SELECT p.name, count(*) as count FROM requests r LEFT JOIN contacts c ON r.contact = c.id LEFT JOIN countries p on c.country = p.id WHERE (r.dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND r.type = "Trial" GROUP BY p.name ORDER BY count DESC');
        $results['Date'] = $this->Request->query('SELECT date(dateIn) as date, count(*) as count FROM requests WHERE (dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND type = "Trial" GROUP BY date(dateIn) ORDER BY dateIn');
        $this->set('interval', date('n/d/Y', strtotime($interval[0])) . ' - ' . date('n/d/Y', strtotime($interval[1])));
        return $results;
    }

    private function contactrequests($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }
        $this->loadModel('Request');
        $results['Country'] = $this->Request->query('SELECT p.name, count(*)as count FROM requests r LEFT JOIN contacts c ON r.contact = c.id LEFT JOIN countries p on c.country = p.id WHERE (r.dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND r.type = "Contact" GROUP BY p.name ORDER BY count DESC');
        $results['Date'] = $this->Request->query('SELECT date(dateIn) as date, count(*)as count FROM requests WHERE (dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND type = "Contact" GROUP BY date(dateIn)');
        $this->set('interval', date('n/d/Y', strtotime($interval[0])) . ' - ' . date('n/d/Y', strtotime($interval[1])));
        return $results;
    }

    private function expressrequests($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }
        $this->loadModel('Request');
        $results['Country'] = $this->Request->query('SELECT p.name, count(*)as count FROM requests r LEFT JOIN contacts c ON r.contact = c.id LEFT JOIN countries p on c.country = p.id WHERE (r.dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND r.type = "express" GROUP BY p.name ORDER BY count DESC');
        $results['Date'] = $this->Request->query('SELECT date(dateIn) as date, count(*)as count FROM requests WHERE (dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND type = "express" GROUP BY date(dateIn)');
        $this->set('interval', date('n/d/Y', strtotime($interval[0])) . ' - ' . date('n/d/Y', strtotime($interval[1])));
        return $results;
    }

    private function consultingrequests($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }
        $this->loadModel('Request');
        $results['Country'] = $this->Request->query('SELECT p.name, count(*)as count FROM requests r LEFT JOIN contacts c ON r.contact = c.id LEFT JOIN countries p on c.country = p.id WHERE (r.dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND r.type = "Consulting" GROUP BY p.name ORDER BY count DESC');
        $results['Date'] = $this->Request->query('SELECT date(dateIn) as date, count(*)as count FROM requests WHERE (dateIn BETWEEN "' . $interval[0] . '" AND "' . $interval[1] . '") AND type = "Consulting" GROUP BY date(dateIn)');
        $this->set('interval', date('n/d/Y', strtotime($interval[0])) . ' - ' . date('n/d/Y', strtotime($interval[1])));
        return $results;
    }

    private function leadstrialrequests($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }

        $this->loadModel('Request');
        $this->loadModel('Country');
        $this->loadModel('User');
        $this->loadModel('Group');

        $options = Array();
        if (!$this->isPartner()) {
            $options['conditions'] = array(
                'type LIKE' => 'trial%',
                'user' => null,
                'or' => array(
                    array('partner' => null),
                    array('partner' => $this->Auth->user('group_id'))
                )
            );
        } else {
            $options['conditions'] = array(
                'and' => array(
                    'type LIKE' => 'trial%',
                    'user' => null,
                    'partner' => $this->Auth->user('group_id')
                )
            );
        }

        $this->Request->recursive = 1;
        $this->paginate['conditions'] = $options['conditions'];
        $this->paginate['order'] = array('Request.id' => 'desc');
        if ($this->RequestHandler->accepts(array('xml', 'csv', 'xls')))
            $requests = $this->Request->find('all', $options);
        else
            $requests = $this->paginate('Request');

        $results['requests'] = $requests;
        $results['type'] = "Trial Requests";
        $results['countries'] = $this->Country->find('list');
        $results['groups'] = $this->Group->find('list', array('conditions' => array('id !=' => 1)));
        $results['users'] = $this->User->find('list');
        $this->report = "report";
        return $results;
    }

    private function leadscontactrequests($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }

        $this->loadModel('Request');
        $this->loadModel('Country');
        $this->loadModel('User');
        $this->loadModel('Group');

        $options = Array();
        if (!$this->isPartner()) {
            $options['conditions'] = array(
                'type LIKE' => 'contact%',
                'user' => null,
                'or' => array(
                    array('partner' => null),
                    array('partner' => $this->Auth->user('group_id'))
                )
            );
        } else {
            $options['conditions'] = array(
                'and' => array(
                    'type LIKE' => 'contact%',
                    'user' => null,
                    'partner' => $this->Auth->user('group_id')
                )
            );
        }

        $this->Request->recursive = 1;
        $this->paginate['conditions'] = $options['conditions'];
        $this->paginate['order'] = array('Request.id' => 'desc');
        if ($this->RequestHandler->accepts(array('xml', 'csv', 'xls')))
            $requests = $this->Request->find('all', $options);
        else
            $requests = $this->paginate('Request');

        $results['requests'] = $requests;
        $results['type'] = "Contact Requests";
        $results['countries'] = $this->Country->find('list');
        $results['groups'] = $this->Group->find('list', array('conditions' => array('id !=' => 1)));
        $results['users'] = $this->User->find('list');
        $this->report = "report";
        return $results;
    }

    private function leadsexpressrequests($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }

        $this->loadModel('Request');
        $this->loadModel('Country');
        $this->loadModel('User');
        $this->loadModel('Group');

        $options = Array();
        if (!$this->isPartner()) {
            $options['conditions'] = array(
                'type LIKE' => 'express%',
                'user' => null,
                'or' => array(
                    array('partner' => null),
                    array('partner' => $this->Auth->user('group_id'))
                )
            );
        } else {
            $options['conditions'] = array(
                'and' => array(
                    'type LIKE' => 'express%',
                    'user' => null,
                    'partner' => $this->Auth->user('group_id')
                )
            );
        }

        $this->Request->recursive = 1;
        $this->paginate['conditions'] = $options['conditions'];
        $this->paginate['order'] = array('Request.id' => 'desc');
        if ($this->RequestHandler->accepts(array('xml', 'csv', 'xls')))
            $requests = $this->Request->find('all', $options);
        else
            $requests = $this->paginate('Request');

        $results['requests'] = $requests;
        $results['type'] = "Express Requests";
        $results['countries'] = $this->Country->find('list');
        $results['groups'] = $this->Group->find('list', array('conditions' => array('id !=' => 1)));
        $results['users'] = $this->User->find('list');
        $this->report = "report";
        return $results;
    }

    private function logs($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }

        $this->loadModel('Log');
        if ($this->RequestHandler->accepts(array('xml', 'csv', 'xls')))
            return $this->Log->find('all');
        else
            return $this->paginate('Log');
    }

    private function wons($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }

        $this->loadModel('Lead');
        $this->Lead->recursive = 1;
        $this->loadModel('User');
        $groups = $this->_getSubGroups($this->Auth->user('group_id'));
        $options['conditions'] = array(
            'User.group_id' => $this->_getSubGroups($this->Auth->user('group_id'))
        );
        $users = array_keys($this->User->find('list', $options));
        $options['conditions'] = array(
            'user_id' => $users,
            'status' => array('avangate', 'client')
        );
        $this->paginate['conditions'] = $options['conditions'];
        if ($this->RequestHandler->accepts(array('xml', 'csv', 'xls')))
            return $this->Lead->find('all', $options);
        else
            return $this->paginate('Lead');
    }

    private function losts($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }

        $this->loadModel('Lead');
        $this->Lead->recursive = 1;
        $this->loadModel('User');
        $groups = $this->_getSubGroups($this->Auth->user('group_id'));
        $options['conditions'] = array(
            'User.group_id' => $this->_getSubGroups($this->Auth->user('group_id'))
        );
        $users = array_keys($this->User->find('list', $options));
        $options['conditions'] = array(
            'user_id' => $users,
            'status' => array('lost')
        );
        $this->paginate['conditions'] = $options['conditions'];
        if ($this->RequestHandler->accepts(array('xml', 'csv', 'xls')))
            return $this->Lead->find('all', $options);
        else
            return $this->paginate('Lead');
    }

    private function invoices($interval = NULL) {
        if ($interval == NULL)
            $interval = array(
                date('Y-m-d', time() - (60 * 60 * 24 * 15)),
                date('Y-m-d') . ' 23:59:59'
            );
        else {
            $interval = explode(' - ', $interval);
            $interval[0] = date('Y-m-d', strtotime($interval[0]));
            if (isset($interval[1]))
                $interval[1] = date('Y-m-d', strtotime($interval[1])) . ' 23:59:59';
            else
                $interval[1] = date('Y-m-d', strtotime($interval[0]) + (60 * 60 * 24));
        }

        $this->loadModel('Offer');
        $options = array(
            'fields' => array('Offer.*'),
            'conditions' => array(
                'Offer.status' => 'paid'
            )
        );
        $this->paginate['conditions'] = $options['conditions'];
        if ($this->RequestHandler->accepts(array('xml', 'csv', 'xls', 'pdf')))
            return $this->Offer->find('all', $options);
        else
            return $this->paginate('Offer');
    }
    
    private function expressusers() {
        $this->loadModel('Contact');
        $this->loadModel('Country');
        
        $results['Contacts'] = $this->Contact->query("SELECT Contact.* FROM requests r join contacts AS Contact on r.contact = Contact.id where r.type like \"%Express%\";");
        $results['Countries'] = $this->Country->find('list');
        $this->report = "newslettercontacts";
        return $results;
    }

}

?>