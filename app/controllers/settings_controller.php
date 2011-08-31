<?php

class SettingsController extends AppController {

    var $name = 'Settings';
    var $components = array('RequestHandler');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
    }

    public function index() {
        // Load data from DB
        $email = $this->Setting->findByType('EMAIL');
        $theme = $this->Setting->findByType('THEME');
        $ogone = $this->Setting->findByType('OGONE');
        $doc_categories = $this->Setting->findByType('DOCCATEGORY');

        // Adapt data for the view
        $ogone = unserialize($ogone['Setting']['value']);
        $this->loadModel('Theme');
        $available_themes = $this->Theme->getThemes();
        $email = unserialize($email['Setting']['value']);

        // Send data to the view
        $this->set(compact('email', 'available_themes', 'theme', 'ogone', 'doc_categories'));
    }

    public function products($action = null, $id = null) {
        $this->loadModel('Product');
        switch ($action) {
            case null : {
                    $this->Product->recursive = 0;
                    $this->set('products', $this->paginate('Product'));
                    $this->render('products/products');
                };
                break;
            case 'add' : {
                    if (!empty($this->data)) {
                        try {
                            // Create the new Event
                            $this->Product->begin();
                            $this->Product->create();

                            // save product details
                            if ($this->Product->save($this->data)) {
                                $this->Product->commit();
                                $message = $this->Product->id;
                            } else {
                                throw new Exception("Cannot save");
                            }
                        } catch (Exception $e) {
                            $this->Product->rollback();
                            $this->cakeError('error500');
                        }
                        $this->set(compact('message'));
                        $this->render('/ajax/action');
                    } else {
                        $this->render('products/product_add');
                    }
                }
                break;
            case 'edit' : {
                    if ($id != null) {
                        if (!empty($this->data)) {
                            try {
                                // Create the new Event
                                $this->Product->begin();
                                $this->Product->id = $id;

                                if (isset($this->data['Product']['active']) && $this->data['Product']['active'] != '') {
                                    if ($this->data['Product']['active'] != '-1') {
                                        $this->data['Product']['active'] = 1;
                                    } else {
                                        unset($this->data['Product']['active']);
                                    }
                                } else {
                                    $this->data['Product']['active'] = 0;
                                }

                                // save product details
                                if ($this->Product->save($this->data)) {
                                    $this->Product->commit();
                                    $message = $this->Product->id;
                                } else {
                                    throw new Exception("Cannot save");
                                }
                            } catch (Exception $e) {
                                $this->Product->rollback();
                                $this->cakeError('error500');
                            }
                            $this->set(compact('message'));
                            $this->render('/ajax/action');
                        } else {
                            $product = $this->Product->read(null, $id);
                            $this->set(compact('product'));
                            $this->render('products/product_edit');
                        }
                    };
                }
                break;
            case 'delete' : {
                    try {
                        // Create the new Event
                        $this->Product->begin();
                        // save product details
                        if ($this->Product->delete($id)) {
                            $this->Product->commit();
                            $message = $id;
                        } else {
                            throw new Exception("Cannot save");
                        }
                    } catch (Exception $e) {
                        $this->Product->rollback();
                        $this->cakeError('error500');
                    }
                    $this->set(compact('message'));
                    $this->render('/ajax/action');
                }
                break;
        }
    }

    public function pluginstore($action = null, $id = null) {
        $this->loadModel('Plugin');
        switch ($action) {
            case null : {
                    $this->Product->recursive = 0;
                    $this->set('plugins', $this->paginate('Plugin'));
                    $this->render('plugins/plugins');
                };
                break;
            case 'add' : {
                    if (!empty($this->data)) {
                        try {
                            // Create the new Plugin
                            $this->Plugin->begin();
                            $this->Plugin->create();

                            // save Plugin details
                            if ($this->Plugin->save($this->data)) {
                                $this->Plugin->commit();
                                $message = $this->Plugin->id;
                            } else {
                                throw new Exception("Cannot save");
                            }
                        } catch (Exception $e) {
                            $this->Plugin->rollback();
                            $this->cakeError('error500');
                        }
                        $this->set(compact('message'));
                        $this->render('/ajax/action');
                    } else {
                        $this->render('plugins/plugin_add');
                    }
                }
                break;
            case 'edit' : {
                    if ($id != null) {
                        if (!empty($this->data)) {
                            try {
                                // Create the new Plugin
                                $this->Plugin->begin();
                                $this->Plugin->id = $id;

                                // save Plugin details
                                if ($this->Plugin->save($this->data)) {
                                    $this->Plugin->commit();
                                    $message = $this->Plugin->id;
                                } else {
                                    throw new Exception("Cannot save");
                                }
                            } catch (Exception $e) {
                                $this->Plugin->rollback();
                                $this->cakeError('error500');
                            }
                            $this->set(compact('message'));
                            $this->render('/ajax/action');
                        } else {
                            $plugin = $this->Plugin->read(null, $id);
                            $this->set(compact('plugin'));
                            $this->render('plugins/plugin_edit');
                        }
                    };
                }
                break;
            case 'delete' : {
                    try {
                        // Create the new Plugin
                        $this->Plugin->begin();
                        // save product details
                        if ($this->Plugin->delete($id)) {
                            $this->Plugin->commit();
                            $message = $id;
                        } else {
                            throw new Exception("Cannot save");
                        }
                    } catch (Exception $e) {
                        $this->Plugin->rollback();
                        $this->cakeError('error500');
                    }
                    $this->set(compact('message'));
                    $this->render('/ajax/action');
                }
                break;
        }
    }

    public function repositories($action = null, $id = null) {
        $this->loadModel('Repository');
        switch ($action) {
            case null : {
                    $this->Product->recursive = 0;
                    $this->set('repositories', $this->paginate('Repository'));
                    $this->render('repositories/repositories');
                };
                break;
            case 'add' : {
                    if (!empty($this->data)) {
                        try {
                            // Create the new Repository
                            $this->Repository->begin();
                            $this->Repository->create();

                            // save Repository details
                            if ($this->Repository->save($this->data)) {
                                $this->Repository->commit();
                                $message = $this->Repository->id;
                            } else {
                                throw new Exception("Cannot save");
                            }
                        } catch (Exception $e) {
                            $this->Repository->rollback();
                            $this->cakeError('error500');
                        }
                        $this->set(compact('message'));
                        $this->render('/ajax/action');
                    } else {
                        $this->render('repositories/repository_add');
                    }
                }
                break;
            case 'edit' : {
                    if ($id != null) {
                        if (!empty($this->data)) {
                            try {
                                // Create the new Repository
                                $this->Repository->begin();
                                $this->Repository->id = $id;

                                // save Repository details
                                if ($this->Repository->save($this->data)) {
                                    $this->Repository->commit();
                                    $message = $this->Repository->id;
                                } else {
                                    throw new Exception("Cannot save");
                                }
                            } catch (Exception $e) {
                                $this->Repository->rollback();
                                $this->cakeError('error500');
                            }
                            $this->set(compact('message'));
                            $this->render('/ajax/action');
                        } else {
                            $repository = $this->Repository->read(null, $id);
                            $this->set(compact('repository'));
                            $this->render('repositories/repository_edit');
                        }
                    };
                }
                break;
            case 'delete' : {
                    try {
                        // Create the new Repository
                        $this->Repository->begin();
                        // save product details
                        if ($this->Repository->delete($id)) {
                            $this->Repository->commit();
                            $message = $id;
                        } else {
                            throw new Exception("Cannot save");
                        }
                    } catch (Exception $e) {
                        $this->Repository->rollback();
                        $this->cakeError('error500');
                    }
                    $this->set(compact('message'));
                    $this->render('/ajax/action');
                }
                break;
        }
    }

    public function countries($id = null) {
        
    }

    public function licensing($action = null) {
        $this->loadModel('License');
        if ($action != null) {
            if (!empty($this->data)) {
                $message = 0;
                try {
                    $this->License->begin();
                    $this->data['License']['date'] = date('Y-m-d H:i:s');
                    $this->data['License']['is_used'] = 0;
                    $this->data['License']['is_activated'] = 0;
                    $this->data['License']['is_revoked'] = 0;

                    $licenses = explode("\n", trim($this->data['License']['serials']));

                    foreach ($licenses as $lic) {
                        $this->data['License']['serialkey'] = trim($lic);
                        $this->License->create();
                        if (!$this->License->save($this->data))
                            throw new Exception("Cannot create ne lic");
                    }
                    $this->License->commit();
                    $message = $this->License->id;
                } catch (Exception $e) {
                    $this->License->rollback();
                    $this->cakeError('error500');
                }
                $this->set(compact('message'));
                $this->render('/ajax/action');
            } else {
                $groups = $this->License->Group->find('list');
                $products = $this->License->Product->find('list');
                $this->set(compact('groups', 'products'));
                $this->render('licenses_add');
            }
        } else {
            $this->License->recursive = -1;
            //$lic = $this->License->find('count', array());
            $licenses = $this->License->query(
                            "SELECT group_id, product_id, is_trial, is_used, count(*)
                    FROM licenses
                    GROUP BY group_id, product_id, is_trial, is_used");
            $products = $this->License->Product->find('list');
            $groups = $this->License->Group->find('list');

            $this->set(compact('licenses', 'products', 'groups'));
        }
    }

    public function ogone() {
        $ogone = $this->Setting->findByType('OGONE');
        if (!empty($this->data)) {
            $message = 0;
            try {
                $this->Setting->begin();
                $this->Setting->id = $ogone['Setting']['id'];

                $this->data['Setting']['value'] = serialize($this->data['Setting']['OGONE']);
                if (!$this->Setting->save($this->data))
                    throw new Exception("Cannot save ogone prefs");

                $this->Setting->commit();
                $message = $this->Setting->id;
            } catch (Exception $e) {
                $this->Setting->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $ogone = unserialize($ogone['Setting']['value']);
            $this->set(compact('ogone'));
        }
    }

    public function mail() {
        $email = $this->Setting->findByType('EMAIL');
        if (!empty($this->data)) {
            $message = 0;
            try {
                $this->Setting->begin();
                $this->Setting->id = $email['Setting']['id'];
                $this->data['Setting']['type'] = "EMAIL";

                if ($this->data['Setting']['EMAIL']['folder'] == "")
                    $this->data['Setting']['EMAIL']['folder'] = "INBOX";
                if (!isset($this->data['Setting']['EMAIL']['ssl']) || $this->data['Setting']['EMAIL']['ssl'] == "")
                    $this->data['Setting']['EMAIL']['ssl'] = false;

                $this->data['Setting']['value'] = serialize($this->data['Setting']['EMAIL']);
                if (!$this->Setting->save($this->data))
                    throw new Exception("Cannot save ogone prefs");

                $this->Setting->commit();
                $message = $this->Setting->id;
            } catch (Exception $e) {
                $this->Setting->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->cakeError('error500');
        }
    }
    
    public function docsettings() {
        $categories = $this->Setting->findByType('DOCCATEGORY');
        if (!empty($this->data)) {
            $message = 0;
            try {
                $this->Setting->begin();
                $this->Setting->id = $categories['Setting']['id'];
                $this->data['Setting']['type'] = "DOCCATEGORY";

                $this->data['Setting']['value'] = $this->data['Setting']['DOCCATEGORY'];
                if (!$this->Setting->save($this->data))
                    throw new Exception("Cannot save documents settings");

                $this->Setting->commit();
                $message = $this->Setting->id;
            } catch (Exception $e) {
                $this->Setting->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->cakeError('error500');
        }
    }

    public function themes() {
        $theme = $this->Setting->findByType('THEME');
        if (!empty($this->data)) {
            $message = 0;
            try {
                $this->Setting->begin();
                $this->Setting->id = $theme['Setting']['id'];
                $this->data['Setting']['type'] = "THEME";

                $this->data['Setting']['value'] = $this->data['Setting']['THEME'];
                if (!$this->Setting->save($this->data))
                    throw new Exception("Cannot save themes settings");

                $this->Setting->commit();
                $message = $this->Setting->id;
            } catch (Exception $e) {
                $this->Setting->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->cakeError('error500');
        }
    }

    public function clean($type = NULL) {
        if ($type == "bounces" && !empty($this->params['form']['emails'])) {
            $emails = explode("\n", $this->params['form']['emails']);
            $this->loadModel('Contact');
            $success = 0;
            foreach ($emails as $email) {
                try {
                    $this->Contact->begin();
                    $contact = $this->Contact->find('first', array('conditions' => array('emails LIKE' => '%' . $email . '%')));
                    if (!$this->Contact->delete($contact['Contact']['id'])) {
                        throw new Exception("Cannot delete contact");
                    }
                    $this->Contact->commit();
                    $success++;
                } catch (Exception $e) {
                    $this->Contact->rollback();
                    $message = 'false : ' . $e->getMessage();
                }
            }

            $message = $success . ' / ' . count($emails) . ' deleted';
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } elseif ($type == "newsletter" && !empty($this->params['form']['emails'])) {
            $emails = explode("\n", $this->params['form']['emails']);
            $this->loadModel('Contact');
            $success = 0;
            foreach ($emails as $email) {
                try {
                    $this->Contact->begin();
                    $contact = $this->Contact->find('first', array('conditions' => array('emails LIKE' => '%' . $email . '%')));
                    $contact['Contact']['newsletter'] = 0;
                    if (!$this->Contact->save($contact)) {
                        throw new Exception("Cannot update contact");
                    }
                    $this->Contact->commit();
                    $success++;
                } catch (Exception $e) {
                    $this->Contact->rollback();
                    $message = 'false : ' . $e->getMessage();
                }
            }

            $message = $success . ' / ' . count($emails) . ' unsubscribed';
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } elseif ($type == "community" && !empty($this->params['form']['emails'])) {
            $emails = explode("\n", $this->params['form']['emails']);
            $this->loadModel('Contact');
            $success = 0;
            foreach ($emails as $email) {
                try {
                    $this->Contact->begin();
                    $contact = $this->Contact->find('first', array('conditions' => array('emails LIKE' => '%' . $email . '%')));
                    $contact['Contact']['community_newsletter'] = 0;
                    if (!$this->Contact->save($contact)) {
                        throw new Exception("Cannot update contact");
                    }
                    $this->Contact->commit();
                    $success++;
                } catch (Exception $e) {
                    $this->Contact->rollback();
                    $message = 'false : ' . $e->getMessage();
                }
            }

            $message = $success . ' / ' . count($emails) . ' unsubscribed';
            $this->set(compact('message'));
            $this->render('/ajax/action');
        }
    }

}

?>
