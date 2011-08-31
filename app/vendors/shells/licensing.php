<?php

class LicensingShell extends Shell {

    var $tasks = array('Email');
    var $uses = array('License');
    var $monitoring = array(
        'HA7N4-JD86X-AP4DV-E7FP4-BGMRJ',
        'DTEMD-XAVU9-EMRA7-GK3RY-HJTQA',
        'HQWC9-7FNBG-KQ6F3-8TQXA-VW9EN',
        'DW94D-WCU7F-NHJCP-YHJ8T-Q6VW9',
        'J8V94-K3MHJ-DU938-U9EFN-P4K3Y',
        'YAWPK-HJDQ6-XNPJA-P4BK3-MRJDQ',
        'J6FG8-AVW9E-7RYVN-P4BK3-MRJDQ',
        'HJAFB-D8TXA-V4B8C-9E7NP-4B3MH',
        'YHTU4-GK3YH-JCUK8-6XAWC-U9FNB',
        'QC4KQ-CU9FN-PJDUB-MRYJD-8TXAC',
        'J8QE7-4BGK3-YHJAC-P4BK3-MRJDX'
    );

    function main() {
        $this->syncActivations();
        $this->syncActivationTries();
    }

    private function syncActivations() {
        $unactivated = $this->License->getUnactivated();
        $query = $this->License->buildPullQuery($unactivated);
        $activations = $this->License->getActivations($query);

        foreach ($activations as $license) {
            if (in_array(strtoupper($license[0]['number']), $this->monitoring)){
                $this->notifyActivation($license[0]['number']);
            }
            $this->License->pushActivation($license[0]['number'], $license[0]['date']);
        }
    }

    private function syncActivationTries() {
        $tries = $this->License->getTries();
        foreach ($tries as $license) {
            $this->License->pushActivationTries($license[0]['license'], $license[0]['date'], $license[0]['nbTries']);
        }
    }

    private function notifyActivation($licence) {
        $this->Email->set('licence', $licence);
        $this->Email->send(array(
            'from' => 'isabelle.lepez@planningforce.com',
            'to' => 'sales@planningforce.com',
            'subject' => 'Activation Notification',
            'template' => 'notify_activation'
        ));
    }

}

?>
