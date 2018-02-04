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

    /**
     * サイト一覧を取得
     *
     * @param
     * @return
     */
    public function get_list() {
        $query  = 'SELECT s.*, c.name AS category_name FROM site s ';
        $query .= 'LEFT JOIN category c ON c.id = s.category_id ';
        return $this->select($query);
    }


}

