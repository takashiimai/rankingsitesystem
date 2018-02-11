<?php

class config_site_item_model extends db_model {

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
     * @param   string      ID
     * @param   string      キー
     * @return  array       取得した情報
     */
    public function select_by_id($table, $id, $key = 'id') {
        try {
            $params = array(
                ':' . $key => $id,
            );
            return $this->select('SELECT * FROM ' . $table . ' WHERE ' . $key . ' = :' . $key . ' ORDER BY orderby ', $params);
        } catch (PDOException $e) {
            throw new Exception();
        }
    }


}

