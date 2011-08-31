<?php

/**
 * Description of tasks_controller
 *
 * @author florian
 */
class DocumentsController extends AppController {

    var $name = 'Documents';
    var $paginate = array(
        'limit' => 15,
        'order' => array(
            'Document.type' => 'asc',
            'Document.name' => 'asc'
        )
    );
    var $helpers = array('Document');

    function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
        $this->Auth->allow(array('add'));
    }

    public function index() {
        if (isset($this->params['url']['category']))
            $category = urldecode($this->params['url']['category']);
        else
            $category = "Personal";
        if (isset($this->params['url']['path']) && $this->params['url']['path'] != "/")
            $path = urldecode($this->params['url']['path']) . '/';
        else
            $path = "/";

        $this->loadModel('Setting');
        $categories = $this->Setting->findByType('DOCCATEGORY');
        $categories = explode("\n", $categories['Setting']['value']);

        $options = array(
            'category' => $category,
            'path' => $path
        );

        switch (strtolower($category)) {
            case "personal" : {
                    $options['shared_group'] = false;
                    $options['shared_public'] = false;
                    $options['Document.user_id'] = $this->Auth->user('id');
                    $options['group_id'] = $this->Auth->user('group_id');
                } break;
            case "public" : {
                    $options['shared_group'] = false;
                    $options['shared_public'] = true;
                } break;
            default : {
                    $options['shared_group'] = true;
                    $options['shared_public'] = false;
                    $options['group_id'] = $this->Auth->user('group_id');
                } break;
        }

        $this->paginate['conditions'] = $options;

        $this->Document->recursive = 1;
        $this->set('documents', $this->paginate());
        $this->set('current_category', $category);
        $this->set('current_path', $path);
        $this->set('categories', $categories);
    }

    public function add() {
        if (!empty($_FILES)) {
            $fileData = fread(fopen($_FILES['Filedata']['tmp_name'], "r"), $_FILES['Filedata']['size']);

            $this->data['Document']['name'] = $_FILES['Filedata']['name'];
            $this->data['Document']['type'] = 'file';
            $this->data['Document']['mime'] = $_FILES['Filedata']['type'];
            $this->data['Document']['size'] = $_FILES['Filedata']['size'];
            $this->data['Document']['date'] = date('Y-m-d H:i:s', time());
            $this->data['Document']['data'] = gzcompress($fileData, 9);
            $this->data['Document']['isPublic'] = 1;
            $this->data['Document']['user_id'] = $this->Auth->user('id');
            $this->data['Document']['group_id'] = $this->Auth->user('group_id');

            switch (strtolower($this->data['Document']['category'])) {
                case "personal" : {
                        $this->data['Document']['shared_group'] = false;
                        $this->data['Document']['shared_public'] = false;
                    } break;
                case "public" : {
                        $this->data['Document']['shared_group'] = false;
                        $this->data['Document']['shared_public'] = true;
                    } break;
                default : {
                        $this->data['Document']['shared_group'] = true;
                        $this->data['Document']['shared_public'] = false;
                    } break;
            }

            $this->Document->begin();
            try {
                $this->Document->create();
                $this->Document->save($this->data);

                $this->set('message', '1');
                $this->Document->commit();
            } catch (Exception $e) {
                $this->Document->rollback();
                $this->set('message', '0');
            }
        } else {
            $this->set('message', '0');
        }
        $this->layout = 'no_layout';
        $this->render('/ajax/action');
    }

    public function addfolder() {
        if (!empty($this->data)) {
            $this->Document->begin();
            try {
                $this->data['Document']['type'] = 'dir';
                $this->data['Document']['date'] = date('Y-m-d H:i:s', time());
                $this->data['Document']['user_id'] = $this->Auth->user('id');
                $this->data['Document']['group_id'] = $this->Auth->user('group_id');
                switch (strtolower($this->data['Document']['category'])) {
                    case "personal" : {
                            $this->data['Document']['shared_group'] = false;
                            $this->data['Document']['shared_public'] = false;
                        } break;
                    case "public" : {
                            $this->data['Document']['shared_group'] = false;
                            $this->data['Document']['shared_public'] = true;
                        } break;
                    default : {
                            $this->data['Document']['shared_group'] = true;
                            $this->data['Document']['shared_public'] = false;
                        } break;
                }
                $this->Document->create();
                $this->Document->save($this->data);

                $this->set('message', '1');
                $this->Document->commit();
            } catch (Exception $e) {
                $this->Document->rollback();
                $this->set('message', '0');
            }
            $this->layout = 'no_layout';
            $this->render('/ajax/action');
        }
    }

    public function view($id = null) {
        if ($id != null && $id != '') {
            Configure::write('debug', 0);
            $this->Document->recursive = -1;
            $file = $this->Document->findById($id);
            $this->set('file', $file);

            $this->render(null, 'file');
        } else {
            $this->cakeError('error404');
        }
    }

    public function delete($id = null) {
        if ($id != null && $id != '') {
            try {
                $this->Document->begin();
                $path = $this->Document->read(array('name', 'path', 'category', 'type'), $id);
                $category = $path['Document']['category'];
                if ($path['Document']['type'] == "dir") {
                    if ($path['Document']['path'] != "/")
                        $path = $path['Document']['path'] . "/" . $path['Document']['name'];
                    else
                        $path = "/" . $path['Document']['name'];
                    if (!$this->Document->deleteAll(array('path LIKE' => $path . '%', 'category' => $category), true)) {
                        throw new Exception("Cannot delete folder");
                    }
                }
                if (!$this->Document->delete($id)) {
                    throw new Exception("Cannot delete document");
                }
                $this->Document->commit();
                $this->set('message', $id);
                $this->render('/ajax/action');
            } catch (Exception $e) {
                $this->Document->rollback();
                $this->cakeError('error404');
            }
        } else {
            $this->cakeError('error404');
        }
    }

    function link2lead($document = NULL, $lead = NULL) {
        if ($document != NULL && $lead != NULL) {
            try {
                $this->Document->begin();
                $this->Document->id = $document;
                if ($lead != 0)
                    $this->data['Document']['lead_id'] = $lead;
                else
                    $this->data['Document']['lead_id'] = NULL;

                if (!$this->Document->save($this->data)) {
                    throw new Exception("Cannot delete document");
                }
                $this->Document->commit();
                $this->set('message', '1');
                $this->render('/ajax/action');
            } catch (Exception $e) {
                $this->Document->rollback();
                $this->cakeError('error404');
            }
        } elseif ($document != NULL) {
            $this->set('lead', $this->Document->read(array('document.id', 'lead.id', 'lead.name'), $document));
        } else {
            $this->cakeError('error404');
        }
    }

    function move($type, $source, $dest) {
        if ($type == "folder") {
            try {
                $file = $this->Document->read(array('name', 'path'), $source);
                $destination = $this->Document->read(array('name', 'path'), $dest);

                $file['Document']['path'] = $destination['Document']['path'] . $destination['Document']['name'] . '/';
                $file['Document']['user_id'] = $this->Auth->user('id');
                $file['Document']['group_id'] = $this->Auth->user('group_id');

                $this->Document->id = $source;
                if (!$this->Document->save($file)) {
                    throw new Exception("Cannot move document");
                }
                $this->Document->commit();
                $this->set('message', '1');
                $this->render('/ajax/action');
            } catch (Exception $e) {
                $this->Document->rollback();
                $this->set('message', '0');
            }
            $this->render('/ajax/action');
        } else if ($type == "category") {
            try {
                $file = $this->Document->read(array('name', 'path', 'category'), $source);

                $file['Document']['path'] = '/';
                $file['Document']['category'] = $dest;

                switch (strtolower($dest)) {
                    case "personal" : {
                            $file['Document']['shared_group'] = false;
                            $file['Document']['shared_public'] = false;
                            $file['Document']['user_id'] = $this->Auth->user('id');
                            $file['Document']['group_id'] = $this->Auth->user('group_id');
                        } break;
                    case "public" : {
                            $file['Document']['shared_group'] = false;
                            $file['Document']['shared_public'] = true;
                        } break;
                    default : {
                            $file['Document']['shared_group'] = true;
                            $file['Document']['shared_public'] = false;
                            $file['Document']['group_id'] = $this->Auth->user('group_id');
                        } break;
                }

                $this->Document->id = $source;
                if (!$this->Document->save($file)) {
                    throw new Exception("Cannot move document");
                }
                $this->Document->commit();
                $this->set('message', '1');
                $this->render('/ajax/action');
            } catch (Exception $e) {
                $this->Document->rollback();
                $this->set('message', '0');
            }
            $this->render('/ajax/action');
        } else {
            $this->cakeError('error404');
        }
    }

    public function treeview($category = NULL) {
        if ($category != NULL) {
            $category = strtolower($category);
            $directories = $this->Document->find(
                'all', array(
                    'conditions' => array(
                        'category' => $category,
                        'type' => 'dir'
                    ),
                    'fields' => array(
                        'Document.id','Document.name', 'Document.path'
                    ),
                    'order' => array('Document.path')
                )
            );
            $this->set('directories', $directories);
        } else {
            $this->cakeError('error404');
        }
    }

}

?>