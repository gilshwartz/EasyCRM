<?php

class User extends AppModel {

    var $name = 'User';
    var $displayField = 'username';
    var $actsAs = array('Acl' => array('type' => 'requester'), 'Logable');
    var $virtualFields = array(
        'fullname' => 'CONCAT(User.firstname, " ", User.lastname)'
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'role_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EventsUser' => array(
            'className' => 'EventsUser',
            'foreignKey' => 'users_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => true
        )
    );

    function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['role_id'])) {
            $roleId = $this->data['User']['role_id'];
        } else {
            $roleId = $this->field('role_id');
        }
        if (!$roleId) {
            return null;
        } else {
            return array('Role' => array('id' => $roleId));
        }
    }

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
        if (is_array($conditions) && isset($conditions['`User`.`id`'])) {
            $response = $conditions['`User`.`id`'];

            if ($response->status == Auth_OpenID_SUCCESS) {
                return array('User' => array('openid' => $response->identity_url));
            }

            return array();
        }

        if (is_array($conditions) && isset($conditions['User.username']) && isset($conditions['User.password'])) {
            return array();
        }

        return parent::find($conditions, $fields, $order, $recursive);
    }

}

?>