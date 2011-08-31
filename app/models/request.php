<?php

class Request extends AppModel {

    var $name = 'Request';
    var $actsAs = array('Logable');
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Contact' => array(
            'className' => 'Contact',
            'foreignKey' => 'contact',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'partner',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EventsRequest' => array(
            'className' => 'EventsRequest',
            'foreignKey' => 'requests_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => true
        )
    );
    var $hasOne = array(
        'RequestTrial' => array(
            'className' => 'RequestTrial',
            'foreignKey' => 'request',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'RequestQuote' => array(
            'className' => 'RequestQuote',
            'foreignKey' => 'request',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'RequestContact' => array(
            'className' => 'RequestContact',
            'foreignKey' => 'request',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'RequestConsulting' => array(
            'className' => 'RequestConsulting',
            'foreignKey' => 'request',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'RequestDemo' => array(
            'className' => 'RequestDemo',
            'foreignKey' => 'request',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'LeadsRequest' => array(
            'className' => 'LeadsRequest',
            'foreignKey' => 'requests_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public function getNbNewRequest($group_id, $isPartner = false) {
        if (!$isPartner) {
            $requestsOptions['conditions'] = array(
                'lower(status)' => 'open',
                'user' => null,
                'or' => array(
                    array('partner' => null),
                    array('partner' => $group_id)
                )
            );
        } else {
            $requestsOptions['conditions'] = array(
                'lower(status)' => 'open',
                'user' => null,
                'partner' => $group_id
            );
        }
        return $this->find('count', $requestsOptions);
    }
    
    public function getNbPendingRequest($user_id) {
        $requestsOptions['conditions'] = array(
            'accepted' => 0,
            'user' => $user_id
        );

        return $this->find('count', $requestsOptions);
    }

}

?>