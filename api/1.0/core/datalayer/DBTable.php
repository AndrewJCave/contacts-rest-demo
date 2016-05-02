<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBTable
 *
 * @author ajcave
 */
abstract class DBTable extends DBAccess {
    //put your code here
    
    public function __construct($dbParams = null) {
        parent::__construct($dbParams);
        
    }
}
