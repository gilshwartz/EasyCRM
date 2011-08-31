<?php

class Contact extends AppModel {

    var $name = 'Contact';
    var $actsAs = array('Logable');
    var $displayField = 'fullname';

    public $filterArgs = array(
        array('name' => 'firstname', 'type' => 'like'),
        array('name' => 'lastname', 'type' => 'like')
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EventsContact' => array(
            'className' => 'EventsContact',
            'foreignKey' => 'contacts_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => true
        ),
        'LeadsContact' => array(
            'className' => 'LeadsContact',
            'foreignKey' => 'contacts_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => true
        ),
        'Offer' => array(
            'className' => 'Offer',
            'foreignKey' => 'contact',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => true
        )
    );

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['fullname'] = sprintf('CONCAT(%s.firstname, " ", %s.lastname)', $this->alias, $this->alias);
    }

    function _createContact($newContact) {
        if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $newContact['Contact']['emails'])) {
            throw new Exception("Invalid email adress");
        }
        $contact = $this->find('first', array('conditions' => array('emails LIKE' => '%' . $newContact['Contact']['emails'] . '%')));
        if (empty($contact)) {
            if ($newContact['Contact']['country'] == 68)
                $newContact['Contact']['language'] = 'French';
            else
                $newContact['Contact']['language'] = 'English';
            $newContact['Contact']['registration'] = date('Y-m-d H:i:s');
            $this->create();
            if (!$this->save($newContact))
                throw new Exception("Cannot create contact");
            return $this->id;
        } else {
            return $contact['Contact']['id'];
        }
    }
}

?>