<?php

class ServicesController extends AppController {

    var $name = 'Services';
    var $uses = array('Country');
    var $components = array('RequestHandler', 'Email' => array('from' => 'il@planningforce.com'));
    var $email = null;
    var $helpers = array('ICal');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
        $this->Auth->allow(array('*'));
    }

// REMARQUE : Modifier l'url dans PFC + .xml
    public function licensing($request = '') {
        try {
            switch (strtolower($request)) {
                case 'delivery' : {
                        $ip = array('83.96.225.130', '83.96.225.131', '83.96.225.136', '83.96.225.137', '80.169.61.202');
                        if (!in_array($_SERVER['REMOTE_ADDR'], $ip, true))
                            throw new Exception("You are not allowed to request this service");

                        $data = $this->params['form'];

                        try {
                            $this->Country->begin();
                            $countries = $this->Country->find('list');
                            $country = 1;

                            foreach ($countries as $key => $value) {
                                if (strtolower($value) == strtolower($data['COUNTRY'])) {
                                    $country = $key;
                                    break;
                                }
                            }

// Create contact
                            $contact['Contact']['firstname'] = $data["FIRSTNAME"];
                            $contact['Contact']['lastname'] = $data["LASTNAME"];
                            $contact['Contact']['emails'] = serialize(array($data["EMAIL"]));
                            $this->email = $data["EMAIL"];
                            $contact['Contact']['country'] = $country;
                            $contact['Contact']['company_name'] = (isset($data["COMPANY"]) ? $data["COMPANY"] : "");
                            $contact_id = $this->_createContact($contact);

// Create request
                            $nrequest['Request']['contact'] = $contact_id;
                            $nrequest['Request']['type'] = 'Avangate Sales';
                            $nrequest['Request']['status'] = 'Open';
                            $request_id = $this->_createRequest($nrequest);

// Create lead
                            $lead['Lead']['name'] = 'AVG_' . $data['REFNO'];
                            $lead['LeadsRequest']['requests_id'] = $request_id;
                            $lead['LeadsContact']['contacts_id'] = $contact_id;
                            $lead_id = $this->_createLead($lead);

// Assign licenses
                            $lic = array();
                            for ($i = 0; $i < $data['QUANTITY']; $i++) {
                                if ($data['TESTORDER'] == 'NO') {
                                    $lic[] = $this->_assignLic($lead_id, $data['PCODE'], 1);
                                } else {
                                    $lic[] = "TEST-TEST-TEST-TEST-TEST";
                                }
                            }
                            $this->Country->commit();
                        } catch (Exception $e) {
                            $this->Country->rollback();
                        }

                        $results = array('data' => array('code' => $lic));
                        $this->set('results', $results);
                        $this->render('/ajax/results_xml');
                    };
                    break;
                case 'trial' : {
                        $results = array();
                        try {
                            $this->Country->begin();
                            $data = $this->params['form'];

                            if (empty($data))
                                throw new Exception("Invalid request attributes");
                            if ($data['firstname'] == '')
                                throw new Exception('Invalid request attributes');
                            if ($data['lastname'] == '')
                                throw new Exception('Invalid request attributes');
                            if ($data['email'] == '')
                                throw new Exception('Invalid request attributes');
                            if ($data['phone'] == '')
                                throw new Exception('Invalid request attributes');
                            if ($data['company'] == '')
                                throw new Exception('Invalid request attributes');
                            if ($data['country'] == '')
                                throw new Exception('Invalid request attributes');
                            if ($data['product'] == '')
                                throw new Exception('Invalid request attributes');
                            if ($data['consulting'] == '')
                                throw new Exception('Invalid request attributes');
                            if ($data['installationid'] == '')
                                throw new Exception('Invalid request attributes');

// retrieve the country id from the country name
                            $countries = $this->Country->find('list');
                            $country = 1;
                            foreach ($countries as $key => $value) {
                                if (strtolower($value) == strtolower($data['country'])) {
                                    $country = $key;
                                    break;
                                }
                            }

// retrieve the product ID
                            $this->loadModel('Product');
                            $products = $this->Product->findByCodename($data['product']);
                            $product = $products['Product']['id'];
                            $this->Product->id = $product;

// Create contact
                            $contact['Contact']['firstname'] = $data["firstname"];
                            $contact['Contact']['lastname'] = $data["lastname"];
                            $contact['Contact']['emails'] = serialize(array($data["email"]));
                            $this->email = $data["email"];
                            $contact['Contact']['phones'] = serialize(array($data["phone"]));
                            $contact['Contact']['country'] = $country;
                            $contact['Contact']['company_name'] = (isset($data["company"]) ? $data["company"] : "");
                            $user = $this->_createContact($contact);

                            if ($data["consulting"] == 'false') {
                                $this->loadModel('Request');
                                $nbTrial = $this->Request->RequestTrial->find('count', array('conditions' => array('installation_id' => $data['installationid'], 'product' => $this->Product->field('name'))));

                                if ($nbTrial > 1) {
                                    $this->notifyFailedActivation($data);
                                    throw new Exception("You already receive a trial period for this product, Please contact PlanningForce if you want a new trial period");
                                } else {
//
                                }


// Create request
                                $nrequest['Request']['contact'] = $user;
                                $nrequest['Request']['type'] = 'trial';
                                $nrequest['Request']['status'] = 'Open';
                                $request_id = $this->_createRequest($nrequest);

// Create trial request
                                $this->loadModel('RequestTrial');
                                $trial['RequestTrial']['request'] = $request_id;
                                $trial['RequestTrial']['product'] = $this->Product->field('name');
                                $trial['RequestTrial']['installation_id'] = $data['installationid'];

                                $this->RequestTrial->create();
                                if (!$this->RequestTrial->save($trial))
                                    throw new Exception("Cannot create Trial Request");

// Create lead
                                $lead['Lead']['name'] = 'TrialRequest no.' . $this->Request->id;
                                $lead['LeadsRequest']['requests_id'] = $this->Request->id;
                                $lead['LeadsContact']['contacts_id'] = $user;
                                $lead_id = $this->_createLead($lead);

                                $license = $this->_assignLic($lead_id, $product, true, $user);
                                $lic[] = $license;

                                $results['data']['success'] = array("true");
                                $results['data']['license'] = $lic;
                                if (strtoupper($data['product']) == 'VEGA')
                                    $this->_notifyTrialRequest($contact);
                            } else {
// Create request
                                $request['Request']['contact'] = $user;
                                $request['Request']['type'] = 'consulting';
                                $request['Request']['status'] = 'Open';
                                $request_id = $this->_createRequest($request);

// Create trial request
                                $this->loadModel('RequestConsulting');
                                $trial['RequestConsulting']['request'] = $request_id;
                                $trial['RequestConsulting']['details'] = $data['expectations'];
                                $this->RequestConsulting->create();
                                if (!$this->RequestConsulting->save($trial))
                                    throw new Exception("Cannot create RequestConsulting");

                                $results['data']['success'] = array("true");
                            }
                            $this->Country->commit();
                        } catch (Exception $e) {
                            $this->Country->rollback();
                            $results['data']['success'] = array("false");
                            $results['data']['error'] = array($e->getMessage());
                        }
                        $this->RequestHandler->renderAs($this, 'xml');
                        $this->set('results', $results);
                        $this->render('/ajax/results_xml');
                    };
                    break;
                default : throw new Exception("Invalid request.");
            }
        } catch (Exception $e) {
            $this->set("img", "/images/no.png");
            $this->set("msg", $e->getMessage());
            $this->render('/ajax/success');
        }
    }

// Don't rename to requests (pb with ACL)
    public function webrequests($type = 'trial') {
        $post = $this->params['form'];
        try {
// Start transaction
            $this->Country->begin();

// Check data validity
            if (empty($post))
                throw new Exception("Invalid request attributes");
            if (!isset($post['firstname']) || $post['firstname'] == '')
                throw new Exception('Firstname may not be empty');
            if (!isset($post['lastname']) || $post['lastname'] == '')
                throw new Exception('Lastname may not be empty');
            if (!isset($post['email']) || $post['email'] == '')
                throw new Exception('Email may not be empty and must have a correct format');
            if (!isset($post['phone']) || $post['phone'] == '')
                throw new Exception('Phone may not be empty');

// Create Contact
            $contact['Contact']['firstname'] = $post['firstname'];
            $contact['Contact']['lastname'] = $post['lastname'];
            $contact['Contact']['emails'] = serialize(array($post['email']));
            $this->email = $post['email'];
            $contact['Contact']['phones'] = serialize(array($post['phone']));
            if (isset($post['position']) && $post['position'] != '')
                $contact['Contact']['position'] = $post['position'];
            if (isset($post['role']) && $post['role'] != '')
                $contact['Contact']['role'] = $post['role'];
            if (isset($post['country']) && $post['country'] != '')
                $contact['Contact']['country'] = $post['country'];
            if (isset($post['company']) && $post['company'] != '')
                $contact['Contact']['company_name'] = $post['company'];
            if (isset($post['webinar_newsletter']) && $post['webinar_newsletter'] != '')
                $contact['Contact']['webinar_newsletter'] = $post['webinar_newsletter'];
            $contact_id = $this->_createContact($contact);

// Create request
            $request['Request']['contact'] = $contact_id;
            if (isset($post['subject']) && $post['subject'] == 'Download Express')
                $request['Request']['type'] = 'express';
            else
                $request['Request']['type'] = strtolower($type);
            $request['Request']['status'] = 'Open';
            $request_id = $this->_createRequest($request);

            switch (strtolower($type)) {
                case "trial" : {
                        if ($post['product'] == '')
                            throw new Exception('You must select a product');

// Create trial request
                        $this->loadModel('RequestTrial');
                        $trial['RequestTrial']['request'] = $request_id;
                        $this->loadModel('Product');
                        $this->Product->id = $post['product'];
                        $trial['RequestTrial']['product'] = $this->Product->field('name');
                        if (isset($post['message']) && $post['message'] != '')
                            $trial['RequestTrial']['message'] = $post['message'];
                        if (isset($post['installation']) && $post['installation'] != '')
                            $trial['RequestTrial']['installation_id'] = $post['installation'];

                        $this->RequestTrial->create();
                        if (!$this->RequestTrial->save($trial))
                            throw new Exception("Cannot create Trial Request");
                    };
                    break;
                case "quote" : {
                        if ($post['product'] == '')
                            throw new Exception('You must select a product');

// Create trial request
                        $this->loadModel('RequestQuote');
                        $quote['RequestQuote']['request'] = $request_id;
                        $this->loadModel('Product');
                        $this->Product->id = $post['product'];
                        $quote['RequestQuote']['product'] = $this->Product->field('name');
                        if (isset($post['usage']) && $post['usage'] != '')
                            $quote['RequestQuote']['license_usage'] = $post['usage'];
                        if (isset($post['amount']) && $post['amount'] != '')
                            $quote['RequestQuote']['amount'] = $post['amount'];
                        if (isset($post['resources']) && $post['resources'] != '')
                            $quote['RequestQuote']['resources'] = $post['resources'];
                        if (isset($post['projects']) && $post['projects'] != '')
                            $quote['RequestQuote']['projects'] = $post['projects'];

                        $this->RequestQuote->create();
                        if (!$this->RequestQuote->save($quote))
                            throw new Exception("Cannot create Quote Request");
                    };
                    break;
                case "contact" : {
                        if ($post['subject'] == '')
                            throw new Exception('You must set a subject');
                        if ($post['message'] == '')
                            throw new Exception('You must enter a message');

// Create contact request
                        $this->loadModel('RequestContact');
                        $contact['RequestContact']['request'] = $request_id;
                        $contact['RequestContact']['subject'] = $post['subject'];
                        $contact['RequestContact']['message'] = $post['message'];
                        $this->RequestContact->create();
                        if (!$this->RequestContact->save($contact))
                            throw new Exception("Cannot create Contact Request");
                    };
                    break;
                default : throw new Exception("Invalid request type");
            }
            $results['data']['success'] = array("true");
            $this->Country->commit();
        } catch (Exception $e) {
            $this->Country->rollback();
            $results['data']['success'] = array("false");
            $results['data']['error'] = array($e->getMessage());
        }
        $this->set('results', $results);
        $this->render('/ajax/results_xml');
    }

    public function calendars($user = null) {
        if ($user == null)
            $this->cakeError('error404');
        $this->loadModel('Event');
        $conditions['UsersNote.alertDate BETWEEN ? AND ?'] = array(date('Y-m-d', strtotime("-1 months")), date('Y-m-d', strtotime("+1 months")));
        $eventsids = $this->Event->EventsUser->find('all', array(
                    'conditions' => array(
                        'sha1(EventsUser.users_id)' => $user,
                        'Events.type' => array('Event', 'Call'),
                        'Events.start_date BETWEEN ? AND ?' => array(date('Y-m-d', strtotime("-1 months")), date('Y-m-d', strtotime("+1 months"))),
                    ),
                    'fields' => array('Events.id')
                        )
        );
        $id = array();
        foreach ($eventsids as $tmp)
            array_push($id, $tmp['Events']['id']);

        $this->Event->recursive = 2;
        $events = $this->Event->find('all', array('conditions' => array('Event.id' => $id)));
        $this->set(compact('events'));
    }

    public function repositories() {
        $code = $this->params['url']['mid'];
        $product = $this->params['url']['product'];
        $this->loadModel('Repository');
        $repo = $this->Repository->find('first', array(
                    'conditions' => array(
                        'product' => $product,
                        'code' => $code,
                    )
                ));
        if (is_null($repo) || empty($repo)) {
            echo "Invalid Request";
            die();
        } else {
            $this->set(compact('repo'));
        }
    }

    public function registration() {
        $results = array();
        try {
            $this->Country->begin();
            $data = $this->params['url'];

            if (empty($data))
                throw new Exception("Invalid request attributes");
            if ($data['firstname'] == '')
                throw new Exception('Invalid request attributes : missing firstname');
            if ($data['lastname'] == '')
                throw new Exception('Invalid request attributes : missing lastname');
            if ($data['email'] == '')
                throw new Exception('Invalid request attributes : missing email');
            if ($data['locale'] == '')
                throw new Exception('Invalid request attributes : missing locale');
            $data['country'] = $this->getRequestLocation();

// retrieve the country id from the country name
            $countries = $this->Country->find('list');
            $country = 1;
            foreach ($countries as $key => $value) {
                if (strtolower($value) == strtolower($data['country'])) {
                    $country = $key;
                    break;
                }
            }

// Create contact
            $contact['Contact']['firstname'] = $data["firstname"];
            $contact['Contact']['lastname'] = $data["lastname"];
            $contact['Contact']['emails'] = serialize(array($data["email"]));
            $this->email = $data["email"];
            $contact['Contact']['country'] = $country;
            $contact['Contact']['language'] = $this->locale[$data['locale']];
            $user = $this->_createContact($contact);

// Create request
            $request['Request']['contact'] = $user;
            $request['Request']['type'] = 'express';
            $request['Request']['status'] = 'Open';
            $request_id = $this->_createRequest($request);

            $results['data']['success'] = array("true");

            $this->Country->commit();

            // Push user to the community
            $URL = "http://community.planningforce-express.com/remote.php";
            $data = array('username' => $data["email"], 'email' => $data["email"]);
            $postdata = http_build_query($data);
            $opts = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                )
            );
            $context = stream_context_create($opts);
            $user_id = file_get_contents($URL, false, $context);

        } catch (Exception $e) {
            $this->Country->rollback();
            $results['data']['success'] = array("false");
            $results['data']['error'] = array($e->getMessage());
        }
        $this->RequestHandler->renderAs($this, 'xml');
        $this->set('results', $results);
        $this->render('/ajax/results_xml');
    }

    private function _createLead($newLead) {
        $this->loadModel('Lead');
        $this->Lead->create();
// save lead details
        if ($this->Lead->save($newLead)) {
// find the contact attach to request and attach it to the lead's contacts
            if (!empty($newLead['LeadsRequest'])) {
                $newLead['LeadsRequest']['leads_id'] = $this->Lead->id;
                $this->Lead->LeadsRequest->create();
                if (!$this->Lead->LeadsRequest->save($newLead)) {
                    throw new Exception("Cannot save request");
                }
            }
            if (!empty($newLead['LeadsContact'])) {
                $newLead['LeadsContact']['leads_id'] = $this->Lead->id;
                $this->Lead->LeadsContact->create();
                if (!$this->Lead->LeadsContact->save($newLead)) {
                    throw new Exception("Cannot save contact");
                }
            }
            return $this->Lead->id;
        } else {
            throw new Exception("Cannot save");
        }
    }

    private function _createContact($newContact) {
        if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->email)) {
            throw new Exception("Invalid email adress");
        }
        $this->loadModel('Contact');
        $contact = $this->Contact->find('first', array('conditions' => array('emails LIKE' => '%' . $this->email . '%')));
        if (empty($contact)) {
            if (isset($newContact['Contact']['country']) && !is_numeric($newContact['Contact']['country'])) {
                $country = $this->Country->findByName($newContact['Contact']['country']);
                $newContact['Contact']['country'] = $country['Country']['id'];
            }
            if ($newContact['Contact']['language'] == "" && $newContact['Contact']['country'] == 68)
                $newContact['Contact']['language'] = 'French';
            else if ($newContact['Contact']['language'] == "")
                $newContact['Contact']['language'] = 'English';
            $newContact['Contact']['registration'] = date('Y-m-d H:i:s');
            $this->Contact->create();
            if (!$this->Contact->save($newContact))
                throw new Exception("Cannot create contact");
            return $this->Contact->id;
        } else {
            return $contact['Contact']['id'];
        }
    }

    private function _createRequest($newRequest) {
        $this->loadModel('Request');
        $newRequest['Request']['dateIn'] = date('Y-m-d H:i:s');
        if (!isset($newRequest['Request']['status']) || $newRequest['Request']['status'] == "")
        $newRequest['Request']['status'] = "Open";
        $this->Request->create();
        if (!$this->Request->save($newRequest))
            throw new Exception("Cannot create contact");
        return $this->Request->id;
    }

    private function _assignLic($leadID, $productID = null, $isTrial = 0, $user = null) {
        $this->loadModel('License');
        if (!isset($productID))
            throw new Exception("Invalid product");
        $this->License->id = $this->License->assignLicense($leadID, $productID, $isTrial, $user);
        return $this->License->field('serialkey');
    }

    private function _notifyTrialRequest($contact) {
//debug($contact);
        $this->Email->smtpOptions = array(
            'port' => '465',
            'timeout' => '30',
            'host' => 'ssl://smtp.gmail.com',
            'username' => 'isabelle.lepez@planningforce.com',
            'password' => 'princesse',
            'client' => 'PlanningForce'
        );

        $this->Email->delivery = 'smtp';

        $emails = unserialize($contact['Contact']['emails']);

        if ($contact['Contact']['country'] == 68) {
            $subject = "Démarrage de trial [PlanningForce]";
            $template = 'trial_start_fr';
        } else {
            $subject = "Following your trial [PlanningForce]";
            $template = 'trial_start_en';
        }

        $this->set('firstname', $contact['Contact']['firstname']);

        $this->Email->from = 'Isabelle Lepez <il@planningforce.com>';
        $this->Email->to = $emails[0];
        $this->Email->subject = $subject;
        $this->Email->template = $template;
        $this->Email->sendAs = 'html';

        $this->Email->send();
    }

    private function notifyFailedActivation($user) {
        $this->loadModel('Setting');
        $settings = $this->Setting->findByType('EMAIL');
        $settings = unserialize($settings['Setting']['value']);
        $this->Email->smtpOptions = array(
            'port' => '465',
            'timeout' => '30',
            'host' => 'ssl://smtp.gmail.com',
            'username' => $settings['username'],
            'password' => $settings['password'],
            'client' => 'PlanningForce'
        );

        $this->Email->delivery = 'smtp';

        $subject = "Notification - Activation Issue";
        $template = 'activation_issue';

        $this->set('data', $data);

        $this->Email->from = 'PlanningForce CRM <'.$settings['username'].'>';
        $this->Email->to = "PlanningForce Sales <sales@planningforce.com>";
        $this->Email->subject = $subject;
        $this->Email->template = $template;
        $this->Email->sendAs = 'html';

        $this->Email->send();
    }

    private static function getRequestLocation($ip = NULL) {
        $url = "http://api.hostip.info/";

        if ($ip == NULL)
            $ip = $_SERVER['REMOTE_ADDR'];
        $data = array('ip' => $ip);

        $postdata = http_build_query($data, '', '&');

        $url = $url . "?" . $postdata;

        $opts = array('http' =>
            array(
                'method' => 'GET',
                'header' => 'Content-type: application/x-www-form-urlencoded',
            )
        );

        $context = stream_context_create($opts);

        $file = file_get_contents($url, false, $context);
        $result = new SimpleXMLElement($file);
        $result->registerXPathNamespace("gml", "http://www.opengis.net/gml");
        $countryName = $result->xpath("//countryName");

        return $countryName[0];
    }

    var $locale = array('aa' => 'Afar',
        'ab' => 'Abkhazian',
        'af' => 'Afrikaans',
        'am' => 'Amharic',
        'ar' => 'Arabic',
        'as' => 'Assamese',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'ba' => 'Bashkir',
        'be' => 'Byelorussian',
        'bg' => 'Bulgarian',
        'bh' => 'Bihari',
        'bi' => 'Bislama',
        'bn' => 'Bengali; Bangla',
        'bo' => 'Tibetan',
        'br' => 'Breton',
        'ca' => 'Catalan',
        'co' => 'Corsican',
        'cs' => 'Czech',
        'cy' => 'Welsh',
        'da' => 'Danish',
        'de' => 'German',
        'dz' => 'Bhutani',
        'el' => 'Greek',
        'en' => 'English',
        'eo' => 'Esperanto',
        'es' => 'Spanish',
        'et' => 'Estonian',
        'eu' => 'Basque',
        'fa' => 'Persian',
        'fi' => 'Finnish',
        'fj' => 'Fiji',
        'fo' => 'Faroese',
        'fr' => 'French',
        'fy' => 'Frisian',
        'ga' => 'Irish',
        'gd' => 'Scots Gaelic',
        'gl' => 'Galician',
        'gn' => 'Guarani',
        'gu' => 'Gujarati',
        'ha' => 'Hausa',
        'he' => 'Hebrew',
        'hi' => 'Hindi',
        'hr' => 'Croatian',
        'hu' => 'Hungarian',
        'hy' => 'Armenian',
        'ia' => 'Interlingua',
        'id' => 'Indonesian',
        'ie' => 'Interlingue',
        'ik' => 'Inupiak',
        'is' => 'Icelandic',
        'it' => 'Italian',
        'iu' => 'Inuktitut',
        'ja' => 'Japanese',
        'jw' => 'Javanese',
        'ka' => 'Georgian',
        'kk' => 'Kazakh',
        'kl' => 'Greenlandic',
        'km' => 'Cambodian',
        'kn' => 'Kannada',
        'ko' => 'Korean',
        'ks' => 'Kashmiri',
        'ku' => 'Kurdish',
        'ky' => 'Kirghiz',
        'la' => 'Latin',
        'ln' => 'Lingala',
        'lo' => 'Laothian',
        'lt' => 'Lithuanian',
        'lv' => 'Latvian',
        'mg' => 'Malagasy',
        'mi' => 'Maori',
        'mk' => 'Macedonian',
        'ml' => 'Malayalam',
        'mn' => 'Mongolian',
        'mo' => 'Moldavian',
        'mr' => 'Marathi',
        'ms' => 'Malay',
        'mt' => 'Maltese',
        'my' => 'Burmese',
        'na' => 'Nauru',
        'ne' => 'Nepali',
        'nl' => 'Dutch',
        'no' => 'Norwegian',
        'oc' => 'Occitan',
        'om' => '(Afan) Oromo',
        'or' => 'Oriya',
        'pa' => 'Punjabi',
        'pl' => 'Polish',
        'ps' => 'Pashto, Pushto',
        'pt' => 'Portuguese',
        'qu' => 'Quechua',
        'rm' => 'Rhaeto-Romance',
        'rn' => 'Kirundi',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'rw' => 'Kinyarwanda',
        'sa' => 'Sanskrit',
        'sd' => 'Sindhi',
        'sg' => 'Sangho',
        'sh' => 'Serbo-Croatian',
        'si' => 'Sinhalese',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'sm' => 'Samoan',
        'sn' => 'Shona',
        'so' => 'Somali',
        'sq' => 'Albanian',
        'sr' => 'Serbian',
        'ss' => 'Siswati',
        'st' => 'Sesotho',
        'su' => 'Sundanese',
        'sv' => 'Swedish',
        'sw' => 'Swahili',
        'ta' => 'Tamil',
        'te' => 'Telugu',
        'tg' => 'Tajik',
        'th' => 'Thai',
        'ti' => 'Tigrinya',
        'tk' => 'Turkmen',
        'tl' => 'Tagalog',
        'tn' => 'Setswana',
        'to' => 'Tonga',
        'tr' => 'Turkish',
        'ts' => 'Tsonga',
        'tt' => 'Tatar',
        'tw' => 'Twi',
        'ug' => 'Uighur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        'vi' => 'Vietnamese',
        'vo' => 'Volapuk',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'za' => 'Zhuang',
        'zh' => 'Chinese',
        'zu' => 'Zulu');

}

?>
