<?php

class category_model extends db_model {

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
     * 種別一覧を取得
     *
     * @param
     * @return
     */
    public function get_list($slug = "") {
        $params = array();
        $query = 'SELECT * FROM category ';
        if (strlen($slug)) {
            $params[':slug'] = $slug;
            $query .= 'WHERE slug = :slug ';
        }
        return $this->select($query, $params);
    }


}

