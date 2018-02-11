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
    public function get_list($slug = "") {
        $params = array();
        $query  = 'SELECT s.*, c.name AS category_name FROM site s ';
        $query .= 'LEFT JOIN category c ON c.id = s.category_id ';
        if ($slug) {
            $params[':slug'] = $slug;
            $query .= 'WHERE c.slug = :slug ';
        }
        $query .= 'ORDER BY s.orderby ';
        return $this->select($query, $params);
    }

    /**
     * サイト一覧を項目を付けて取得
     *
     * @param
     * @return
     */
    public function get_list_with_items($slug = "") {
        $this->model("site_item_model");
        $tmp = $this->get_list($slug);
        $lists = array();
        foreach ($tmp as $key => $value) {
            $lists[ $key ] = $value;

            $params = array(
                ':site_id' => $value['id'],
            );
            $query  = 'SELECT si.*, csi.name, csi.orderby FROM site_item si ';
            $query .= 'LEFT JOIN config_site_item csi ON csi.slug = si.tag ';
            $query .= 'WHERE si.site_id = :site_id ';
            $query .= 'ORDER BY csi.orderby ';
            $items = $this->select($query, $params);
            $lists[ $key ]['items'] = array_column($items, NULL, 'tag');
        }
        return $lists;
    }

}

