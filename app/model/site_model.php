<?php

class site_model extends db_model {

    /**
     * construct
     *
     * @param
     * @return
     */
    public function __construct() {
		parent::__construct();

        $this->connect();
    }


}

