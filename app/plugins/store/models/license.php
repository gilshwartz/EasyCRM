<?php

class License extends StoreAppModel {

    var $name = 'License';
    var $displayField = 'serialkey';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'product_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'LicensesLead' => array(
            'className' => 'LicensesLead',
            'foreignKey' => 'licenses_id'
        ),
        'LicensesOperation' => array(
            'className' => 'LicensesOperation',
            'foreignKey' => 'licenses_id',
        )
    );

    public function resetLicense($license, $reason = "Reset from backend") {
        $this->useDbConfig = "mssql";
        try {
            $this->query('EXEC resetLicense "' . $license . '", "' . $reason . '";');
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }
        $this->useDbConfig = "default";
        return $success;
    }

    public function revokeLicense($license, $reason = "Revoke from backend") {
        $this->useDbConfig = "mssql";
        try {
            $this->query('EXEC blockLicense "' . $license . '", "' . $reason . '";');
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }
        $this->useDbConfig = "default";
        return $success;
    }

    public function assignLicense($lead, $product, $trial = true) {
        $this->lockTable(
                array(
                    'write',
                    array(
                        'licenses as License',
                        'licenses',
                        'licenses_leads',
                        'licenses_operations'
                    )
                )
        );
        try {
            $this->begin();
            $this->recursive = -1;

            // Retrieve an unused license
            $option['conditions'] = array(
                'and' => array(
                    'product_id' => $product,
                    'is_trial' => $trial,
                    'is_used' => 0,
                    'is_revoked' => 0,
                )
            );
            $license = $this->find('first', $option);

            // update the license status
            $update['License'] = array(
                'is_used' => 1,
            );
            $this->id = $license['License']['id'];
            if (!$this->save($update)) {
                throw new Exception("Cannot change license status");
            }

            // link the license to a lead
            $data['LicensesLead'] = array(
                'licenses_id' => $license['License']['id'],
                'leads_id' => $lead,
                'issue_date' => date('Y-m-d H:i:s'),
            );
            $this->LicensesLead->create();
            if (!$this->LicensesLead->save($data)) {
                throw new Exception("Cannot link license to a lead");
            }

            // log action
            $log['LicensesOperation'] = array(
                'licenses_id' => $license['License']['id'],
                'date' => date('Y-m-d H:i:s'),
                'operation' => 'New lic assigned'
            );
            $this->LicensesOperation->create();
            if (!$this->LicensesOperation->save($log)) {
                throw new Exception("Cannot record action");
            }

            $message = $this->id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $message = 'false : ' . $e->getMessage();
        }
        $this->unlockTable();
        return $message;
    }

}

?>