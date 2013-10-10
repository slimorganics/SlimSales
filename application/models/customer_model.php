<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function createCustomer($customer) {
       return $this->db->insert('customer', $customer);
    }
}