<?php

class category_model extends app_model {

    /**
     * construct
     *
     * @param
     * @return
     */
    public function __construct() {
		parent::__construct();

        $this->library('db');
        $this->db->connect();
    }


    /**
     * construct
     *
     * @param
     * @return
     */
    public function get_list() {
		return $this->db->select('SELECT * FROM category');
    }

}

