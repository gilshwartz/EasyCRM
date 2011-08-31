<?php

class UsersController extends AppController {

    var $name = 'Users';
    var $components = array('RequestHandler');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    function add() {
        if (!empty($this->data)) {
            $message = 'false';
            try {
                // Create the new User
                $this->User->begin();
                $this->User->create();

                // save lead details
                if ($this->User->save($this->data)) {
                    $this->User->commit();
                    $message = $this->User->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->User->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $groups = $this->User->Group->find('list', array('conditions' => array('Group.id >' => '1')));
            $roles = $this->User->Role->find('list');
            $this->set(compact('groups', 'roles'));
        }
    }

    function edit($id = null, $render = 'profile') {
        if ($id == null)
            $this->cakeError('error404');
        $message = 'false';
        if (!empty($this->data)) {
            try {
                // Create the new Event
                $this->User->begin();
                $this->User->id = $id;

                if (isset($this->data['User']['password']) && $this->data['User']['password'] != '')
                    $this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);

                if (isset($this->data['User']['active']) && $this->data['User']['active'] != '') {
                    if ($this->data['User']['active'] != '-1') {
                        $this->data['User']['active'] = 1;
                    } else {
                        unset ($this->data['User']['active']);
                    }
                } else {
                    $this->data['User']['active'] = 0;
                }

                // save company details
                if ($this->User->save($this->data)) {
                    $this->User->commit();
                    $message = $this->User->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->User->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $user = $this->User->read(null, $id);
            $groups = $this->User->Group->find('list');
            $roles = $this->User->Role->find('list');
            $this->set(compact('groups', 'roles', 'user'));
            $this->render($render);
        }
        
    }

    function delete($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->User->begin();
            if (!$this->User->delete($id)) {
                throw new Exception("Cannot delete user");
            }
            $this->User->commit();
            $message = $id;
        } catch (Exception $e) {
            $this->User->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }

    public function password() {}

}

?>