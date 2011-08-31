<?php

class TrialShell extends Shell {

    var $tasks = array('Email');
    var $uses = array('LicensesOperation');

    function main() {
        $this->mediumTrial();
        $this->endTrial();
    }

    private function mediumTrial() {
        $this->LicensesOperation->bindModel(
                array(
                    'belongsTo' => array(
                        'Contact' => array(
                            'className' => 'Contact',
                            'foreignKey' => '',
                            'conditions' => 'License.contact_id = Contact.id',
                            'fields' => '',
                            'order' => ''
                        )
                    )
                )
        );
        $conditions = array(
            'DATE(LicensesOperation.date)' => date('Y-m-d', time() - (60 * 60 * 24 * 5)),
            'LicensesOperation.operation' => 'Activate',
            'License.is_trial' => 1,
            'License.manual_mailing' => 0,
            'License.contact_id NOT' => NULL,
        );
        $trials = $this->LicensesOperation->find('all', array('conditions' => $conditions));

        foreach ($trials as $trial) {
            $emails = unserialize($trial['Contact']['emails']);
            if ($emails === false) {
                $emails[0] = $trial['Contact']['emails'];
            }
            if ($trial['Contact']['language'] == "French") {
                $subject = "Besoin d'aide?";
                $template = 'trial_medium_fr';
            } else {
                $subject = "Need some help?";
                $template = 'trial_medium_en';
            }

            $this->Email->set('firstname', $trial['Contact']['firstname']);
            $this->Email->send(array(
                'to' => $emails[0],
                'subject' => $subject,
                'template' => $template
            ));
        }
    }

    private function endTrial() {
        $this->LicensesOperation->bindModel(
                array(
                    'belongsTo' => array(
                        'Contact' => array(
                            'className' => 'Contact',
                            'foreignKey' => '',
                            'conditions' => 'License.contact_id = Contact.id',
                            'fields' => '',
                            'order' => ''
                        )
                    )
                )
        );
        $conditions = array(
            'DATE(LicensesOperation.date)' => date('Y-m-d', time() - (60 * 60 * 24 * 30)),
            'LicensesOperation.operation' => 'Activate',
            'License.is_trial' => 1,
            'License.manual_mailing' => 0,
            'License.contact_id NOT' => NULL,
        );
        $trials = $this->LicensesOperation->find('all', array('conditions' => $conditions));

        foreach ($trials as $trial) {
            $emails = unserialize($trial['Contact']['emails']);
            if ($emails === false) {
                $emails[0] = $trial['Contact']['emails'];
            }
            if ($trial['Contact']['language'] == "French") {
                $subject = "Votre Trial a expiré";
                $template = 'trial_end_fr';
            } else {
                $subject = "Your trial has expired";
                $template = 'trial_end_en';
            }

            $this->Email->set('firstname', $trial['Contact']['firstname']);
            $this->Email->send(array(
                'to' => $emails[0],
                'subject' => $subject,
                'template' => $template
            ));
        }
    }

}
?>