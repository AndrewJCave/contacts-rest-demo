<?php

/**
 * Model class for Contacts data
 * Gets Contact Data based on a contact ID or listd contacts for a user
 * @author Andrew Cave andrew@wpdevhouse.com
 * @copyright (c) 2016, Andrew Cave
 */
class Contact_Model extends DBTable {

    protected $_name = 'contacts';
    
    protected $_select_prefix = "select * from contacts ";
    protected $_contacts_notes_select_prefix = "select * from contacts_notes ";
    protected $_insert_statement = "INSERT INTO rest_demo.contacts (user_id, title, first_name, last_name, company, job_title, email, phone, mobile, web_site) 
	VALUES (:user_id, :title, :first_name, :last_name, :company, :job_title, :email, :phone, :mobile, :web_site)";
    protected $_insert_note_statement = "INSERT INTO rest_demo.contacts_notes (contact_id, note) 
	VALUES (:contact_id, :note);";
    protected $_delete_statement = "DELETE From contacts where ";


    /**
     * Gets contact by contact id
     *
     * @param mixed $userInfo ($getByField value)
     * @param string $getByField
     * @return array
     */
    public function getContact($getByField = 'id', $contactInfo, $apiKey) {
        error_log("Contacts_Model :: GetContact , apiKey : " . $apiKey . " , contactInfo : " . $contactInfo . " , Params : " .$getByField);
        $sql = $this->_select_prefix . "where id = $contactInfo";

        if ($sql === null) {
            return array('Invalid request');
        }
        
        $rs = $this->executeQuery($sql);
        $contact = null;
        if($rs != null) {
            error_log("RS is not null");
            $contact = $rs[0];
        } else {
            error_log("RS is null");
            return null;
        }
           
        $contactData = array();
        $contactData['contact'] = $contact;
        return $contactData;
    }
    
     
    /**
     * Get a contact using id of supplied or lists
     * all contacts if no id supplied.
     * 
     * @param type $id
     * @return array containing contact data
     */    
    public function get($id) {
        error_log("Contacts_Model :: Get method , contact id : " . $id);
        if ($id != null) {
            $sql = $this->_select_prefix . "where id = '$id'";
        } else {
            $sql = $this->_select_prefix;
        }
        
        if ($sql === null) {
            return array('Invalid request');
        }
                
        $rs = $this->executeQuery($sql);
        $contact = null;
        if($rs != null) {
            error_log("RS is not null");
            //$contact = $rs[0];
            $contact = $rs;
        } else {
            error_log("RS is null");
            return null;
        }
           
        $contactData = array();
        if(sizeof($contact) === 1) {
            $notes_data = $this->getContactNotes($id);
            if($notes_data != null) {
                $contact[0]['notes'] = $notes_data;
            }
            $contactData['contact'] = $contact;
        } else if (sizeof($contact) >1) {
            //Get the contact id
            error_log($contact[0]['id']);
            foreach ($contact as $key => $value) {
                $currentId = $value['id'];
                $notes_data = $this->getContactNotes($currentId);
                if($notes_data != null) {
                    $contact[$key]['notes'] = $notes_data;
                }
            }
            $contactData['contacts'] = $contact;
        }
        return $contactData;
    }
    
    private function getContactNotes($contactId) {
        if ($contactId != null) {
            $sql = $this->_contacts_notes_select_prefix . "where contact_id = '$contactId'";
        } else {
            return null;
        }
        error_log($sql);                
        $rs = $this->executeQuery($sql);
        $notes = null;
        if($rs != null) {
            error_log("RS is not null");
            //$contact = $rs[0];
            $notes = $rs;
        } else {
            error_log("RS is null");
            return null;
        }
        APICommon::log_array($notes);   
        return $notes;
    }
    
    
    /**
     * Inserts a new contact record using supplied data
     */
    
    public function add($contactInfo) {
        error_log("Contacts_Model :: Add method");
        APICommon::log_array($contactInfo);
        
        $sql = $this->_insert_statement;
               
        //For this demo, assume all contacts belong to the same user
        $user_id = 1;
        try {
           $stmt = $this->getDBConn()->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $contactInfo['title']);
        $stmt->bindParam(':first_name', $contactInfo['first_name']);
        $stmt->bindParam(':last_name', $contactInfo['last_name']);
        $stmt->bindParam(':company', $contactInfo['company']);
        $stmt->bindParam(':job_title', $contactInfo['job_title']);
        $stmt->bindParam(':email', $contactInfo['email']); 
        $stmt->bindParam(':phone', $contactInfo['phone']);
        $stmt->bindParam(':mobile', $contactInfo['mobile']);
        $stmt->bindParam(':web_site', $contactInfo['web_site']);
        
        $stmt->execute(); 
        $rowId = $this->getDBConn()->lastInsertId();
        error_log("Row Id -> $rowId");
        $this->addContactNote($rowId, $contactInfo['notes']);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
                
    }
    
    /**
     * Adds a not for a contact record identified by provided contact id
     */ 
    private function addContactNote($contactId,$note) {
        error_log("Contacts_Model :: Add Contacts Note method");
               
        $sql = $this->_insert_note_statement;
                
        try {
           $stmt = $this->getDBConn()->prepare($sql);
        $stmt->bindParam(':contact_id', $contactId, PDO::PARAM_INT);
        $stmt->bindParam(':note', $note, PDO::PARAM_STR);
        
        $stmt->execute(); 
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        
    }
    
    
    /**
     * Removes a contact record identified by provided contact id
     */    
    public function delete($id) {
        error_log("Contacts_Model :: Delete method");                
        $sql = $this->_delete_statement . "id = '$id'";
        
        try {
            $stmt = $this->getDBConn()->prepare($sql);
            $stmt->execute(); 
            error_log(APICommon::log_array($stmt->errorInfo()));
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
                
    }
}