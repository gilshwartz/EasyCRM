<?php

class EmailsShell extends Shell {

    var $uses = array('Document', 'Contact', 'Email', 'EmailsCc', 'EmailsTo', 'Lead', 'Setting');
    var $config;
    var $connection;

    function main() {
        $settings = $this->Setting->findByType('EMAIL');
        $this->config = unserialize($settings['Setting']['value']);
        
        $this->connection = $this->connect($this->config);

        for ($i = 1; $i <= $this->countMessages($this->connection); $i++) {
            $message = $this->fetchMessage($this->connection, $i);

            // Save Sender
            $email['Email']['from'] = $this->saveContact($message['header']['from'][0]);

            // Find Lead ID :
            $leads = $this->Lead->find('list', array('fields' => array ('Lead.id')));
            $email['Email']['lead_id'] = NULL;
            foreach ($leads as $value){
                $token = strtoupper(substr(sha1($value), 0, 5));
                $inSubject = stripos($message['header']['subject'], $token);
                if ($inSubject !== false) {
                    $email['Email']['lead_id'] = $value;
                    break;
                }
            }

            // Store Email into the DB

            $email['Email']['date'] = date('Y-m-d H:i:s', strtotime($message['header']['date']));
            $email['Email']['subject'] = imap_utf8($message['header']['subject']);
            $email['Email']['message'] = base64_encode($message['body']['message']);
            $email['Email']['msgid'] = sha1($message['header']['msgid']);
            if (isset($message['header']['replyto'])) {
                $email['Email']['replyto'] = sha1($message['header']['replyto']);
            }

            try {
                $this->Email->create();
                $this->Email->save($email);

                $this->contacts($message['header']['to'], "EmailsTo", $this->Email->id);

                if (isset($message['header']['cc']))
                    $this->contacts($message['header']['cc'], "EmailsCc", $this->Email->id);

                if (!empty($message['body']['attachment']))
                    $this->attachements($message['body']['attachment'], $this->Email->id);


                $this->Email->commit();
            } catch (Exception $e) {
                $this->Email->rollback();
            }
        }

        $this->disconnect($this->connection);
    }

    private function connect($settings) {
        $ssl = ($settings['ssl']) ? ("/ssl/novalidate-cert") : ("/novalidate-cert");
        $host = "{" . $settings['server'] . ":" . $settings['port'] . "/" . $settings['protocol'] . $ssl . "}" . $settings['folder'];
        $connection = imap_open($host, $settings['username'], $settings['password']) or die("FAILLED! " . imap_last_error());
        return $connection;
    }

    private function disconnect($connection) {
        imap_expunge($connection);
        return imap_close($connection);
    }

    private function countMessages($connection) {
        return imap_num_msg($connection);
    }

    private function fetchMessage($connection, $id) {
        $email['header'] = $this->fetchHeader($connection, $id);
        $email['body'] = $this->fetchBody($connection, $id);
        imap_delete($connection, $id);
        return $email;
    }

    private function fetchHeader($connection, $id) {
        $tmp = imap_headerinfo($connection, $id);
        foreach ($tmp->from as $t) {
            $name = explode(" ", $t->personal);
            $from['firstname'] = $name[0];
            $from['lastname'] = $name[1];
            $from['email'] = $t->mailbox . "@" . $t->host;
            $header['from'][] = $from;
        }
        foreach ($tmp->to as $t) {
            if (isset($t->personal)) {
                $name = explode(" ", $t->personal);
                $to['firstname'] = $name[0];
                if (isset($name[1]))
                    $to['lastname'] = $name[1];
            }
            $to['email'] = $t->mailbox . "@" . $t->host;
            $header['to'][] = $to;
        }
        if (isset($tmp->cc)) {
            foreach ($tmp->cc as $t) {
                $name = explode(" ", $t->personal);
                $cc['firstname'] = $name[0];
                $cc['lastname'] = $name[1];
                $cc['email'] = $t->mailbox . "@" . $t->host;
                $header['cc'][] = $cc;
            }
        }
        $header['subject'] = $tmp->subject;
        $header['date'] = $tmp->date;
        $header['msgid'] = $tmp->message_id;
        if (isset($tmp->in_reply_to)) {
            $header['reply'] = $tmp->in_reply_to;
        }
        return $header;
    }

    private function fetchBody($connection, $id) {
        $body['message'] = $this->retrieve_message($connection, $id);
        $body['attachment'] = $this->retrieve_attachment($connection, $id);
        return $body;
    }

    private function retrieve_message($mbox, $messageid) {
        $structure = imap_fetchstructure($mbox, $messageid);
        if ($structure->type == 1) {
            $message = imap_fetchbody($mbox, $messageid, "1.1"); ## GET THE BODY OF MULTI-PART MESSAGE
            if (!$message) {
                $message = imap_fetchbody($mbox, $messageid, "1"); ## GET THE BODY OF MULTI-PART MESSAGE
                if (!$message) {
                    $message = '[NO TEXT ENTERED INTO THE MESSAGE]\n\n';
                }
            }
        } else {
            $message = imap_body($mbox, $messageid);
            if (!$message) {
                $message = '[NO TEXT ENTERED INTO THE MESSAGE]\n\n';
            }
        }

        return utf8_encode(imap_qprint($message));
    }

    private function retrieve_attachment($connection, $messageid) {
        $struct = imap_fetchstructure($connection, $messageid);
        $contentParts = count($struct->parts);
        $selectBoxDisplay = array();

        if ($contentParts >= 2) {
            for ($i = 2; $i <= $contentParts; $i++) {
                $bodystruct = imap_bodystruct($connection, $messageid, $i);

                $doc['name'] = '';
                if ($bodystruct->ifdparameters) {
                    if ($bodystruct->dparameters[0]->value == "us-ascii" || $bodystruct->dparameters[0]->value == "US-ASCII") {
                        if ($bodystruct->dparameters[1]->value != "") {
                            $doc['name'] = $bodystruct->dparameters[1]->value;
                        }
                    } elseif ($bodystruct->dparameters[0]->value != "iso-8859-1" && $bodystruct->dparameters[0]->value != "ISO-8859-1") {
                        $doc['name'] = $bodystruct->dparameters[0]->value;
                    }
                } elseif ($bodystruct->ifparameters){
                    if ($bodystruct->parameters[0]->value == "us-ascii" || $bodystruct->parameters[0]->value == "US-ASCII") {
                        if ($bodystruct->parameters[1]->value != "") {
                            $doc['name'] = $bodystruct->parameters[1]->value;
                        }
                    } elseif ($bodystruct->parameters[0]->value != "iso-8859-1" && $bodystruct->parameters[0]->value != "ISO-8859-1") {
                        $doc['name'] = $bodystruct->parameters[0]->value;
                    }
                }
                $doc['type'] = $bodystruct->subtype;
                $doc['size'] = $bodystruct->bytes;

                $doc['data'] = imap_fetchbody($connection, $messageid, $i);
                if ($bodystruct->encoding == 3) { // 3 = BASE64 
                    $doc['data'] = base64_decode($doc['data']);
                } elseif ($bodystruct->encoding == 4) { // 4 = QUOTED-PRINTABLE 
                    $doc['data'] = quoted_printable_decode($doc['data']);
                }

                $att[$i - 2] = $doc;
            }
        }
        return $att;
    }

    private function contacts($contacts, $type, $msgID) {
        $save[$type]['emails_id'] = $msgID;
        foreach ($contacts as $contact) {
            $save[$type]['contacts_id'] = $this->saveContact($contact);

            $this->$type->create();
            $this->$type->save($save);
        }
    }

    private function saveContact($email) {
        $contact = $this->Contact->find('first', array('conditions' => array('emails LIKE' => '%' . $email['email'] . '%')));
        if (empty($contact)) {
            $newContact['Contact']['registration'] = date('Y-m-d H:i:s');
            $newContact['Contact']['firstname'] = $email['firstname'];
            $newContact['Contact']['lastname'] = $email['lastname'];
            $newContact['Contact']['email'] = serialize(array($email['email']));
            $this->Contact->create();
            if (!$this->Contact->save($newContact))
                throw new Exception("Cannot create contact");
            return $this->Contact->id;
        } else {
            return $contact['Contact']['id'];
        }
    }

    private function attachements($attachments, $msgID) { 
        foreach ($attachments as $doc) {
            $document['Document'] = $doc;
            $document['Document']['email_id'] = $msgID;
            $document['Document']['data'] = gzcompress($document['Document']['data'], 9);
            $document['Document']['date'] = date('Y-m-d H:i:s');
            $document['Document']['isPublic'] = false;
            
            $document['Document']['group_id'] = 2;

            $document['Document']['category'] = "Group";
            $document['Document']['shared_group'] = true;
            $document['Document']['shared_public'] = false;                    

            $this->Document->create();
            $this->Document->save($document);
            $docid = $this->Document->id;
        }
    }

}
?>