<?php

/**
 * Model class for User data
 * Gets User Data based on a user ID, email or API Key
 * @author Andrew Cave andrew@wpdevhouse.com
 * @copyright (c) 2016, Andrew Cave
 */
class User_Model extends DBTable {

    /**
     * spc_users table
     *
     * @var string
     */
    protected $_name = 'users';
    
    protected $_select_prefix = "select * from users ";
    protected $_select_suffix = '' ;

    
    /**
     * Gets user by userId, email, access key
     *
     * @param mixed $userInfo ($getByField value)
     * @param string $getByField - field to search by
     * @param string $apiKey - access key
     * @return array
     */
    public function getUser($getByField = 'id', $userInfo, $apiKey) {
        error_log("GetUser , apiKey : " . $apiKey . " , userInfo : " . $userInfo . " , Params : " .$getByField);
        $sql = $this->_select_prefix;

        switch ($getByField) {
            case 'id':
                $sql = $sql . "where id = $userInfo";
                break;
            
            case 'email':
                $sql = $sql . "where email = '$userInfo'";
                break;
            
            case 'access_key':
                $sql = $sql . "where access_key = '$userInfo'";
                break;
             
            default : 
               $sql = null;
                                     
        }
        
        if ($sql === null) {
            return array('Invalid request');
        }
        
        error_log($sql);
        
        $rs = $this->executeQuery($sql);
        $user = null;
        if($rs != null) {
            error_log("RS is not null");
            $user = $rs[0];
        } else {
            error_log("RS is null");
            return null;
        }
                
        //add admin info
        $sql = $this->_select_prefix . "where id = {$user['admin_id']}";
        $adminRS = $this->executeQuery($sql);
        if ($adminRS != null) {
            error_log("Admin RS is not null");
            $admin = $adminRS[0];
        } else {
            error_log("Admin RS is null");
            return null;
        }
        //Check that the retrieved user is either the caller or 
        //belongs to the caller as admin user
        error_log("Test 1 - " . ($userInfo === $apiKey));
        error_log("Test 2 - " . ($admin['access_key']));
        if ($getByField === 'access_key') {
            if ($userInfo === $apiKey) {
                error_log("Requesting own record - allowed");
            } else if (($apiKey === $admin['access_key'])) {
                error_log("Requesting a staff record - allowed");
            } else {
                return array('Invalid request, access to this user record is not allowed.');
            }
        }
        
        $user['admin'] = $admin;
    
        $userData = array();
        $userData['user'] = $user;
        return $userData;
    }
    
    /**
     * Gets all users belong to an admin or get all users for superuser
     *
     * @return string
     */
    public function getUsers($role = null, $userId = null) {
		if (!$role) {
            $role = SPC_USER_ROLE;
        }

		if (!$userId) {
            $userId = SPC_USERID;
        }

		$users = array();

        //get all users grouped with their group-managers (admins)
		if ($role == 'super') {
            $select = $this->select()
                            ->where('role = "admin"')
                            ->order('username');

            $admins = $this->fetchAll($select);

            foreach ($admins as $admin) {
                $adminUsername = $admin['username'];
				$users[$adminUsername] = $admin;
				$users[$adminUsername]['users'] = array();

                $select = $this->select()
                                ->where('role = "user"')
                                ->where("admin_id = {$admin['id']}")
                                ->order('username');

                $adminUsers = $this->fetchAll($select);
                foreach ($adminUsers as $user) {
                    $users[$adminUsername]['users'][] = $user;
                }
            }

         //get group-manager's (admin's) users
		} else if ($role == 'admin') {
            $select = $this->select()
                            ->where('role = "user"')
                            ->where("admin_id = $userId")
                            ->order('username');

            $users = $this->fetchAll($select);
		}
        
        
        foreach ($users as &$user) {
            $this->addUserProperties($user);
            if (isset($user['users'])) {
                foreach ($user['users'] as &$_user) {
                    $this->addUserProperties($_user);
                }
            }
        }

        return $users;
    }
     
    public function searchUser($term) {
        $select = $this
                    ->select()
                    ->where("username LIKE '%{$term}%' 
                             OR CONCAT(first_name, ' ', last_name) LIKE '%{$term}%'
                             OR email LIKE '%{$term}%'");
        
        if (SPC_USER_ROLE == 'admin') {
            $select->where('admin_id = ' . SPC_USERID);
        }
        
        $users = $this->fetchAll($select);
        
        foreach ($users as &$user) {
            $this->addUserProperties($user);
        }
        
        return $users;
    }
}