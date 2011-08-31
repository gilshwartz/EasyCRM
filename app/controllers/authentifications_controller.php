<?php

class AuthentificationsController extends AppController {

    var $name = 'Authentifications';
    var $uses = array();
    var $components = array('RequestHandler', 'Openid' => array('accept_google_apps' => true));

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('login', 'logout', 'openid'));
    }

    public function index() {
        $this->redirect(array("action" => "login"));
    }

    public function openid() {
        $returnTo = $this->curPageURL();
        if ($this->Session->read('Auth.User')) {
            $this->Session->setFlash('You are logged in!');
            $this->redirect('/', null, false);
        }

        if ($this->isOpenIDResponse()) {
            $this->handleOpenIDResponse($returnTo);
        } else {

            if (isset($this->params['url']['domain']) && $this->params['url']['domain'] != "")
                $this->makeOpenIDRequest($this->params['url']['domain'], $returnTo);

            //$this->data['User']['openid'] = "planningforce.com";
            if ($this->RequestHandler->isPost()) {
                $this->makeOpenIDRequest($this->data['User']['openid'], $returnTo);
            }
        }
    }

    public function login($url = null) {
        if ($this->Session->read('Auth.User')) {
            $this->Session->setFlash('You are logged in!');
            $this->redirect('/', null, false);
        }

        if (isset($this->data['User']['password']) && $this->data['User']['password'] != "") {            
            $this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
            if ($this->Auth->login($this->data)) {
                $this->redirect('/', null, false);
            }
        }
    }

    private function makeOpenIDRequest($openid, $returnTo) {
        try {
            $this->Openid->authenticate($openid, $returnTo, 'https://' . $_SERVER['SERVER_NAME']);
        } catch (Exception $e) {
            // empty
        }
    }

    private function isOpenIDResponse() {
        return (count($_GET) > 2);
    }

    private function handleOpenIDResponse($returnTo) {
        $response = $this->Openid->getResponse($returnTo);
        if ($response->status == Auth_OpenID_SUCCESS) {
            $this->loadModel('User');
            $user = $this->User->findByOpenid($response->identity_url);
            if ($user) {
                $this->data['User']['username'] = $user['User']['username'];
                $this->data['User']['password'] = $user['User']['password'];
                if ($this->Auth->login($this->data)) {
                    $this->redirect('/', null, false);
                }
            } else {
                $error = "You are not allowed to access this site! <br/>";
                $error .= "Error code : ".$response->identity_url;
                $this->Session->setFlash($error);
            }
        }
    }

    function logout() {
        $this->Session->destroy();
        $this->flash("You are logged out", $this->Auth->logout(), 5);
    }

    function alive($ping = null) {
        if (!isset($ping)) {
            $lastAccess = $this->Session->read('last_access');
            if (($lastAccess + 60 * 10) < time())
                echo 'expired';
            else
                echo 'ok';
            die();
        } else {
            $this->Session->write('last_access', time());
            echo 'ok';
            die();
        }
    }

    private function curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}
?>
