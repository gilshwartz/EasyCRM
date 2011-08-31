<?php

class OffersController extends AppController {

    var $name = 'Offers';
    var $components = array('RequestHandler');
    var $uses = array('Offer', 'OffersDetail');

    function  beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
        $this->Auth->allow(array('payment', 'accept', 'error', 'cancelled', 'invoice'));
    }

    function index() {

    }

    function add($lead = null) {
        if ($lead != null) {
            $message = false;
            try {
                $offer['Offer']['lead_id'] = $lead;
                $offer['Offer']['status'] = 'creation';
                $offer['Offer']['date'] = date('Y-m-d H:i:s');
                $this->Offer->begin();
                $this->Offer->create();
                if (!$this->Offer->save($offer))
                    throw new Exception("Cannot create Offer");
                $message = $this->Offer->id;
                $this->Offer->commit();
            } catch (Exception $e) {
                $this->Offer->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        }
    }

    function add1($id = null) {
        if ($id == null)
            $this->cakeError('error404');
        if (!empty($this->data)) {
            $message = 'false';
            try {
                // Create the new Group
                $this->Offer->begin();
                $this->Offer->id = $id;

                if (empty($this->data['Offer']['is_pro']) || $this->data['Offer']['is_pro'] == "")
                    $this->data['Offer']['is_pro'] = 0;

                // save Group details
                if ($this->Offer->save($this->data)) {
                    $this->Offer->commit();
                    $message = $this->Offer->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Offer->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $offer = $this->Offer->read(null, $id);
            $countries = $this->Offer->Country->find('list');
            $this->set(compact('offer', 'countries'));
            $this->set('orderRef', $this->Offer->createOrderRef($id));
        }
    }

    function add2($id = null) {
        if ($id == null)
            $this->cakeError('error404');
        try {
            $xml = new SimpleXMLElement(file_get_contents("php://input"));
        } catch (Exception $e) {
            $xml = null;
        }
        if (!empty($xml)) {
            $message = 'false';
            try {
                // Create the new Group
                $this->Offer->begin();
                $this->Offer->id = $id;

                $offer['Offer']['currency'] = (string) $xml['currency'];
                if (!$this->Offer->save($offer)) {
                    throw new Exception("Cannot save");
                }

                foreach ($xml->children() as $product) {
                    $details['OffersDetail']['offer_id'] = $id;
                    $details['OffersDetail']['product_name'] = (string) $product->name;
                    $details['OffersDetail']['unit_price'] = (string) $product->price;
                    $details['OffersDetail']['quantity'] = (string) $product->qty;
                    $details['OffersDetail']['discount'] = (string) $product->discount;

                    $this->Offer->OffersDetail->create();
                    if (!$this->Offer->OffersDetail->save($details)) {
                        throw new Exception("Cannot save");
                    }
                }
                // save Group details

                $this->Offer->commit();
                $message = $this->Offer->id;
            } catch (Exception $e) {
                $this->Offer->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->Offer->id = $id;
            $vat = $this->Offer->getVat();
            $this->loadModel('Product');
            $this->Product->recursive = -1;
            $products = $this->Product->find('list', array('fields' => array('Product.price', 'Product.name'), 'conditions' => array('Product.active' => '1')));
            $this->set(compact('vat', 'products'));
            $this->set('orderRef', $this->Offer->createOrderRef($id));
        }
    }

    function add3($id = null) {
        if ($id == null)
            $this->cakeError('error404');
        if (!empty($this->data)) {
            $message = 'false';
            try {
                // Create the new Group
                $this->Offer->begin();
                $this->Offer->id = $id;

                $this->data['Offer']['status'] = 'pending';
                $this->data['Offer']['order_id'] = $this->Offer->createOrderRef($id);

                // save Group details
                if ($this->Offer->save($this->data)) {
                    $this->Offer->commit();
                    $message = $this->Offer->id;
                } else {
                    throw new Exception("Cannot save");
                }
            } catch (Exception $e) {
                $this->Offer->rollback();
                $this->cakeError('error500');
            }
            $this->set(compact('message'));
            $this->render('/ajax/action');
        } else {
            $this->set('orderRef', $this->Offer->createOrderRef($id));
            $offer = $this->Offer->read(null, $id);
            $countries = $this->Offer->Country->find('list');
            $this->set(compact('offer', 'countries'));
        }
    }

    function view($id = null) {
        if ($id == null)
            $this->cakeError('error404');
        $this->Offer->id = $id;

        $this->set('offer', $this->Offer->read(null, $id));
        $this->set('has_vat', $this->Offer->getVat());
    }

    function delete($id = null) {
        if ($id == null)
            $this->cakeError('error404');
        $this->Offer->begin();
        if (!$this->Offer->delete($id)) {
            $this->Offer->rollback();
            $message = "Error : cannot delete offer";
        } else {
            $this->Offer->commit();
            $message = $id;
        }
        $this->set(compact('message'));
        $this->render('/ajax/action');
    }
}
?>