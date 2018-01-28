<?php

// コントローラの基底クラス
class app_controller extends core {

    /**
     * construct
     *
     * @param
     * @return
     */
    public function __construct() {
        parent::__construct();
        $this->library('request');
        $this->library('log');
    }

    /**
     * deconstruct
     *
     * @param
     * @return
     */
    public function __destruct() {
        parent::__destruct();
    }
}

