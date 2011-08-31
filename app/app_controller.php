<?php

class AppController extends Controller {

    var $components = array('Acl', 'Auth', 'Session');
    var $uses = array('User', 'Role');
    var $view = 'Theme';
    var $theme = 'default';

    function __construct() {
        parent::__construct();
    }

    function beforeFilter() {
        $ua = $this->getBrowser();
        if ($ua['name'] == "Internet Explorer" && $this->params['url'] != 'browser'){
            $this->redirect('/browser.php');
        }

        //Configure AuthComponent
        $this->Auth->userScope = array('User.active' => true);
        $this->Auth->authorize = 'actions';
        $this->Auth->loginAction = array('controller' => 'authentifications', 'action' => 'login');
        $this->Auth->logoutRedirect = array('controller' => 'authentifications', 'action' => 'login');
        $this->Auth->loginRedirect = array('/', null, false);

        // Im alive
        if ((($this->params['controller'] != 'authentifications' && $this->params['action'] != 'alive') &&
                ($this->params['controller'] != 'requests' && $this->params['action'] != 'pending') &&
                ($this->params['controller'] != 'requests' && $this->params['action'] != 'newest')) ||
                $_SERVER['REMOTE_ADDR'] == "80.169.61.202") {
            $this->Session->write('last_access', time());
        }

        $activeUser = array('User' => array('id' => $this->Session->read('Auth.User.id'), 'username' => $this->Session->read('Auth.User.username')));
        if (sizeof($this->uses) && $this->{$this->modelClass}->Behaviors->attached('Logable')) {
            $this->{$this->modelClass}->setUserData($activeUser);
        }

        // Desable ACL for Dev
        //$this->Auth->allow(array('*'));
        // Set the application theme
        $this->theme = $this->getActiveTheme();

        // Define Global Variable
        $this->set('is_admin', $this->isAdmin());
        $this->set('is_partner', $this->isPartner());
        $this->set('is_company', $this->isCompany());

	if ($this->params['controller'] != 'services') {
        	$this->Role->recursive = -1;
        	$role = $this->Role->read('accronyme', $this->Auth->user('role_id'));
        	$this->set('role_accro', $role['Role']['accronyme']);
	}
        
    }

    function isPartner() {
        if ($this->Auth->user('role_id') == 4) {
            return true;
        } else {
            return false;
        }
    }

    function isAdmin() {
        if ($this->Auth->user('role_id') == 1) {
            return true;
        } else {
            return false;
        }
    }

    function isCompany() {
        if ($this->Auth->user('group_id') == 3) {
            return true;
        } else {
            $this->loadModel('Group');
            $companies = $this->Group->children(3);
            foreach ($companies as $company) {
                if ($company['Group']['id'] == $this->Auth->user('group_id'))
                    return true;
            }
            return false;
        }
    }

    function _getSubGroups($parent) {
        $this->loadModel('Group');
        $groups = array($parent);
        $childrens = $this->Group->children($parent);
        foreach ($childrens as $child)
            array_push($groups, $child['Group']['id']);

        // Retrieve parnter groups if Admin
        if (!$this->isPartner()) {
            $childrens = $this->Group->children(1);
            foreach ($childrens as $child)
                array_push($groups, $child['Group']['id']);
        }
        return $groups;
    }

    private function getActiveTheme() {
        $this->loadModel('Setting');
        $theme = $this->Setting->findByType('THEME');
        return $theme['Setting']['value'];
    }

    function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }

}

?>