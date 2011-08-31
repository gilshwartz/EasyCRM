<?php

class GroupsController extends AppController {

    var $name = 'Groups';
    var $components = array('RequestHandler');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }
    
    function index() {
        $this->Group->recursive = 0;
        $this->paginate['conditions'] = array('Group.id >' => '1');
        $this->set('groups', $this->paginate());
    }

    function add() {
        if (!empty($this->data)) {
            $message = 'false';
            try {
                // Create the new Group
                $this->Group->begin();
                $this->Group->create();

                // save Group details
                if ($this->Group->save($this->data)) {
                    $this->Group->commit();
                    $message = $this->Group->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Group->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $parentGroups = $this->Group->find('list', array('conditions' => array('Group.id >' => '1')));
            $this->set(compact('parentGroups'));
        }
    }

    function edit($id = null) {
        if ($id == null)
            $this->cakeError('error404');
        $message = 'false';
        if (!empty($this->data)) {
            try {
                // Create the new Event
                $this->Group->begin();
                $this->Group->id = $id;

                // save company details
                if ($this->Group->save($this->data)) {
                    $this->Group->commit();
                    $message = $this->Group->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Group->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $group = $this->Group->read(null, $id);
            $parentGroups = $this->Group->find('list', array('conditions' => array('Group.id >' => '1')));
            $this->set(compact('parentGroups', 'group'));
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->cakeError('error404');
        }
        $message = 'false';
        try {
            $this->Group->begin();
            if (!$this->Group->delete($id, true)) {
                throw new Exception("Cannot delete user");
            }
            $this->Group->commit();
            $message = $id;
        } catch (Exception $e) {
            $this->Group->rollback();
            $message = $e->getMessage();
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }
}

?>