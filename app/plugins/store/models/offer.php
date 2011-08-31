<?php

class Offer extends StoreAppModel {

    var $name = 'Offer';
    var $belongsTo = array(
        'Lead' => array(
            'className' => 'Lead',
            'foreignKey' => 'lead_id'
        ),
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'billing_country_id'
        ),
        'Contact' => array(
            'className' => 'Contact',
            'foreignKey' => 'contact'
        )
    );
    var $hasMany = array(
        'OffersDetail' => array(
            'className' => 'OffersDetail',
            'foreignKey' => 'offer_id'
        )
    );

    public function getVat() {
        if ($this->id == null)
            throw new Exception("no Offer ID");

        $this->Country->id = $this->field('billing_country_id');
        $ue = $this->Country->field('uevat');
        $name = $this->Country->field('name');
        $isPro = $this->field('is_pro');

        if ($ue) {
            if (strtolower($name) == 'belgium') {
                $vat = 21;
            } else {
                if ($isPro) {
                    $vat = 0;
                } else {
                    $vat = 21;
                }
            }
        } else {
            $vat = 0;
        }
        return $vat;
    }

    public function getAmount() {
        if ($this->id == null)
            throw new Exception("no Offer ID");

        $details = $this->OffersDetail->find('all', array('conditions' => array('offer_id' => $this->id)));

        $amount = 0;
        foreach ($details as $line) {
            $amount = $amount + $line['OffersDetail']['amount'];
        }

        return $amount;
    }

    public function createOrderRef($offerID) {
        $md5 = md5($offerID);
        $ref = "PFC." . substr($md5, 0, 3) . "/" . $offerID . "/" . substr($md5, -3, 3);
        return strtoupper($ref);
    }

}

?>