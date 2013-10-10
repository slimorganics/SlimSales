<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function createOrder($orderData) {
       return $this->db->insert('order', $orderData); 
    }

    public function getOrders(){

    }

    //Retrieve product cost from db
    public function itemCost($pid){
        return $this->db->query("SELECT price FROM product where id = $pid")->row_array();
    }
}