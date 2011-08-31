<?php

class OffersController extends StoreAppController {

    var $name = 'Offers';
    var $components = array('RequestHandler');
    var $uses = array('Store.Offer', 'Store.OffersDetail');

    function payment() {
        $id = $this->params['url']['ref'];
        if ($id == null)
            $this->cakeError('error404');
        $offer = $this->Offer->find('first', array('conditions' => array('secure_code' => $id)));
        if (empty($offer) || $offer['Offer']['status'] != 'pending')
            $this->cakeError('error404');
        $this->loadModel('Store.Setting');
        $setting = $this->Setting->find('first', array('conditions' => array('type' => 'OGONE')));
        $this->set('ogone', unserialize($setting['Setting']['value']));
        $this->set('offer', $offer);
        $this->Offer->id = $offer['Offer']['id'];
        $this->set('has_vat', $this->Offer->getVat());
    }

    function accept() {
        $ogoneReturns = $this->params['url'];
        $offer = $this->Offer->find('first', array('conditions' => array('order_id' => $ogoneReturns['orderID'])));

        if (empty($offer) || $offer['Offer']['status'] != 'pending')
            $this->cakeError('error404');

        try {
            // Create the new Group
            $this->Offer->begin();
            $this->Offer->id = $offer['Offer']['id'];

            $data['Offer']['date_paid'] = date('Y-m-d H:i:s');
            $data['Offer']['status'] = 'paid';
            $data['Offer']['transaction_log'] = $ogoneReturns['PAYID'];
            $data['Offer']['payment_method'] = $ogoneReturns['PM'];
            $data['Offer']['remarques'] = 'BRAND = ' . $ogoneReturns['BRAND'] . ' / CARDNO. = ' . $ogoneReturns['CARDNO'];

            if ($offer['Offer']['auto_invoice']) {
                $this->Offer->recursive = -1;
                $invoice_count = $this->Offer->find('first', array('conditions' => array('YEAR(Offer.date_paid) = YEAR(NOW())'), 'fields' => array('max(cast(substring(Offer.invoice_id,13) as SIGNED)) AS INVID')));
                $data['Offer']['invoice_id'] = 'ISC/OG/' . date('Y') . '-' . ($invoice_count[0]['INVID'] + 1);
            }

            if (!$this->Offer->save($data)) {
                throw new Exception("Cannot save");
            }

            if ($offer['Offer']['auto_licenses']) {
                $this->loadModel('Store.License');
                $this->loadModel('Store.Product');

                $products = $this->Product->find('list');

                foreach ($offer['OffersDetail'] as $detail) {
                    $key = array_search($detail['product_name'], $products);
                    if ($key) {
                        for ($i = 0; $i < $detail['quantity']; $i++)
                            $this->License->assignLicense($offer['Offer']['lead_id'], $key, false);
                    }
                }
            }

            if ($offer['Offer']['has_plugins']) {
                $this->loadModel('Plugin');
                $this->loadModel('PluginsLead');

                $plugins = $this->Plugin->find('list');

                foreach ($offer['OffersDetail'] as $detail) {
                    $key = array_search($detail['name'], $plugins);
                    if ($key) {
                        $pluginsLead = $this->PluginsLead->find(
                                        'first', array(
                                    'conditions' => array(
                                        'lead' => $offer['Offer']['lead_id'],
                                        'plugin' => $key,
                                    )
                                        )
                        );
                        $currentPlugin = $this->Plugin->findById($key);
                        if (isset($pluginsLead['PluginsLead']['installation_id']) && !empty($pluginsLead['PluginsLead']['installation_id'])) {
                            $pluginsLead['PluginsLead']['activation_code'] =
                                    $this->PluginsLead->generateActivationKey($pluginsLead['PluginsLead']['installation_id'], $currentPlugin['Plugin']['plugin_id']);

                            $this->PluginsLead->id = $pluginsLead['PluginsLead']['id'];

                            // save product details
                            if (!$this->PluginsLead->save($pluginsLead)) {
                                throw new Exception("Cannot save");
                            }
                            // TODO Notify license by email
                        } else {
                            // TODO Notify activation process by email
                        }
                    }
                }
            }

            $this->Offer->commit();
            //$this->render('success');
            $this->redirect('/success');
        } catch (Exception $e) {
            $this->Offer->rollback();
            $this->redirect('/exception');
        }
    }

    function error($id = null) {
        $ogoneReturns = $this->params['url'];
        $offer = $this->Offer->find('first', array('conditions' => array('order_id' => $ogoneReturns['orderID'])));

        if (empty($offer) || $offer['Offer']['status'] != 'pending')
            $this->cakeError('error404');

        try {
            // Create the new Group
            $this->Offer->begin();
            $this->Offer->id = $offer['Offer']['id'];
            $data['Offer']['status'] = 'error';

            if (!$this->Offer->save($data)) {
                throw new Exception("Cannot save");
            }
            $this->Offer->commit();
            //$this->render('success');
            $this->redirect('/error');
        } catch (Exception $e) {
            $this->Offer->rollback();
            $this->redirect('/exception');
        }
    }

    function cancel($id = null) {
        $ogoneReturns = $this->params['url'];
        $offer = $this->Offer->find('first', array('conditions' => array('order_id' => $ogoneReturns['orderID'])));

        if (empty($offer) || $offer['Offer']['status'] != 'pending')
            $this->cakeError('error404');

        try {
            // Create the new Group
            $this->Offer->begin();
            $this->Offer->id = $offer['Offer']['id'];
            $data['Offer']['status'] = 'cancel';

            if (!$this->Offer->save($data)) {
                throw new Exception("Cannot save");
            }
            $this->Offer->commit();
            //$this->render('success');
            $this->redirect('/error');
        } catch (Exception $e) {
            $this->Offer->rollback();
            $this->redirect('/exception');
        }
    }

    function invoice($id = null) {
        if ($this->RequestHandler->accepts('pdf')) {
            $this->RequestHandler->setContent('pdf', 'application/pdf');
            Configure::write('debug',0);
        } else {
            $this->RequestHandler->setContent('html');
        }
        if ($id == null)
            $this->cakeError('error404');
        $offer = $this->Offer->find('first', array('conditions' => array('secure_code' => $id)));
        if (empty($offer) || $offer['Offer']['invoice_id'] == "")
            $this->cakeError('error404');
        $this->set('offer', $offer);
        $this->Offer->id = $offer['Offer']['id'];
        $this->set('has_vat', $this->Offer->getVat());
    }

    /**
     * Plugin Store : this method generate an offer for the given plugin id
     *
     * @param string URL $plugin Plugin ID.
     * @param string URL $id Installation ID generated by the PFC installation.
     * @access public
     */
    function plugins() {
        if (!empty($this->data)) {
            try {
                $this->Offer->begin();

                // Create contact
                $this->loadModel('Contact');
                $createContact['Contact'] = $this->data['Contact'];
                $createContact['Contact']['country'] = $this->data['Offer']['billing_country_id'];
                $createContact['Contact']['company_name'] = $this->data['Offer']['billing_company'];
                $contact_id = $this->Contact->_createContact($createContact);

                // Create request
                $request['Request']['contact'] = $contact_id;
                $request['Request']['type'] = 'Plugin Web Store';
                $request['Request']['status'] = 'Open';
                $request_id = $this->_createRequest($request);

                // Create lead
                $this->loadModel('Lead');
                $this->loadModel('LeadsContact');
                $lead['Lead']['name'] = 'Plugin License : ' . $this->data['Contact']['firstname'] . ' ' . $this->data['Contact']['lastname'];
                $lead['Lead']['status'] = "client";
                $lead['LeadsContact']['contacts_id'] = $contact_id;
                $lead['LeadsRequest']['requests_id'] = $request_id;
                $lead_id = $this->_createLead($lead);

                // Create offer
                $this->loadModel('Offer');
                $offer['Offer'] = $this->data['Offer'];
                if (empty($offer['Offer']['is_pro']) || $offer['Offer']['is_pro'] == "")
                    $offer['Offer']['is_pro'] = 0;
                $offer['Offer']['lead_id'] = $lead_id;
                $offer['Offer']['status'] = 'creation';
                $offer['Offer']['expiring_date'] = date('Y-m-d H:i:s', time() + 3600 * 24);
                $offer['Offer']['auto_invoice'] = true;
                $offer['Offer']['date'] = date('Y-m-d H:i:s');
                $offer['Offer']['billing_name'] = $this->data['Contact']['firstname'] . ' ' . $this->data['Contact']['lastname'];
                $offer['Offer']['has_plugins'] = 1;
                $this->Offer->create();
                if (!$this->Offer->save($offer))
                    throw new Exception("Cannot create Offer");
                $offer_id = $this->Offer->id;

                // Create Offer Details
                $this->loadModel('Plugin');
                $plugin_details = $this->Plugin->findById($this->data['PluginsLead']['plugin']);
                $details['OffersDetail']['offer_id'] = $offer_id;
                $details['OffersDetail']['product_name'] = $plugin_details['Plugin']['name'];
                $details['OffersDetail']['unit_price'] = $plugin_details['Plugin']['price'];
                $details['OffersDetail']['quantity'] = "1";
                $details['OffersDetail']['discount'] = "0";
                $this->Offer->OffersDetail->create();
                if (!$this->Offer->OffersDetail->save($details)) {
                    throw new Exception("Cannot save");
                }

                // flag order as pending
                $this->data['Offer']['status'] = 'pending';
                $this->data['Offer']['secure_code'] = sha1($offer_id);
                $this->data['Offer']['order_id'] = $this->Offer->createOrderRef($offer_id);
                if (!$this->Offer->save($this->data)) {
                    throw new Exception("Cannot save");
                }

                // Pre assign the plugin to the lead
                $this->loadModel('PluginsLead');
                $plugin['PluginsLead']['lead'] = $lead_id;
                $plugin['PluginsLead']['date'] = date('Y-m-d H:i:s');
                $plugin['PluginsLead']['contact'] = $contact_id;
                $plugin['PluginsLead']['plugin'] = $this->data['PluginsLead']['plugin'];
                $this->PluginsLead->create();
                if (!$this->PluginsLead->save($plugin)) {
                    throw new Exception("Cannot save");
                }

                $this->Offer->commit();
                $this->redirect('/store/payment?ref=' . $this->data['Offer']['secure_code']);
            } catch (Exception $e) {
                $this->Offer->rollback();
                $results['data']['success'] = array("false");
                $results['data']['error'] = array($e->getMessage());
            }
        } else {
            $plugin = $this->params['url']['plugin'];
            $installation_id = $this->params['url']['id'];
            if ($plugin == NULL)
                $this->cakeError('error404');

            $this->loadModel('Plugin');
            $plugin_details = $this->Plugin->findByPluginId($plugin);
            // Un peu rude non???
            if (empty($plugin_details))
                $this->cakeError('error404');
            $countries = $this->Offer->Country->find('list');
            $this->set('plugin', $plugin_details);
            $this->set('installation_id', $installation_id);
            $this->set('countries', $countries);
        }
    }

    function success() {

    }

    function exception() {

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

    private function _createRequest($newRequest) {
        $this->loadModel('Request');
        $newRequest['Request']['dateIn'] = date('Y-m-d H:i:s');
        $this->Request->create();
        if (!$this->Request->save($newRequest))
            throw new Exception("Cannot create contact");
        return $this->Request->id;
    }

}

?>