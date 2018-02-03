<?php

class site_item_model extends db_model {

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

    /**
     * 
     *
     * @param
     * @return
     */
    public function delete($site_id) {
        $params = array(
            ':site_id' => $site_id,
        );
        $query = 'DELETE FROM site_item WHERE site_id = :site_id';
        $this->query($query, $params);
    }
    

}

