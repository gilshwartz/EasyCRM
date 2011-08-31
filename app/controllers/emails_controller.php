<?php

/**
 * Description of tasks_controller
 *
 * @author florian
 */
class EmailsController extends AppController {

    var $name = 'Emails';
    var $paginate = array(
        'limit' => 50,
        'order' => array(
            'Email.id' => 'desc'
        )
    );

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    public function index() {
        $this->Email->recursive = 1;
        $this->set('emails', $this->paginate());
    }

    public function view($id = null) {
        if ($id != null && $id != '') {
            $this->Email->recursive = 1;
            $email = $this->Email->read(NULL, $id);
            $documents = $this->Email->Document->find('list', array('conditions' => array('email_id' => $id)));
            $leads = $this->Email->Lead->find('list');
            $this->set(compact('email', 'documents', 'leads'));
        } else {
            $this->cakeError('error404');
        }
    }

    public function delete($id = null) {
        if ($id != null) {
            try {
                $this->Email->begin();
                if (!$this->Email->delete($id)) {
                    throw new Exception("Cannot delete email");
                }
                $this->Email->commit();
                $this->set('message', $id);
                $this->render('/ajax/action');
            } catch (Exception $e) {
                $this->Email->rollback();
                $this->cakeError('error404');
            }
        } else {
            $this->cakeError('error404');
        }
    }

    function link2lead($email = null, $lead = null) {
        if ($email == NULL || $lead == NULL)
            $this->cakeError('error404');
        try {
            $this->Email->begin();
            $this->Email->id = $email;
            $save['Email']['lead_id'] = $lead;

            if (!$this->Email->save($save)) {
                throw new Exception("Cannot link email to lead");
            }
            $this->Email->commit();
            $name = $this->Email->Lead->read('name', $lead);
            $this->set('message', $name['Lead']['name']);
            $this->render('/ajax/action');
        } catch (Exception $e) {
            $this->Email->rollback();
            $this->cakeError('error404');
        }
    }

    public function fetch() {
        $result = exec('php '.CAKE_CORE_INCLUDE_PATH.'/cake/console/cake.php emails');
        $this->set('message', '1');
        $this->render('/ajax/action');
    }

}
?>
