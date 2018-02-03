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
    public function get_list() {
        $query = 'SELECT * FROM category';
        return $this->select($query);
    }


}

