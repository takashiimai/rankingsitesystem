<?php

class request {

    /**
     * construct
     *
     * @param
     * @return
     */
    public function __construct() {
    }

    /**
     * deconstruct
     *
     * @param
     * @return
     */
    public function __destruct () {
    }

    /**
     * GET取得
     *
     * @param
     * @return
     */
    public function get($key = '') {
        if (!strlen($key) && isset($_GET)) {
            return $_GET;
        }
        if (isset($_GET[ $key ])) {
            return $_GET[ $key ];
        } else {
            return NULL;
        }
    }

    /**
     * POST取得
     *
     * @param
     * @return
     */
    public function post($key = '') {
        if (!strlen($key) && isset($_POST)) {
            return $_POST;
        }
        if (isset($_POST[ $key ])) {
            return $_POST[ $key ];
        } else {
            return NULL;
        }
    }

    /**
     * POST取得
     *
     * @param
     * @return
     */
    public function get_post($key = '') {
        if (!strlen($key) && isset($_REQUEST)) {
            return $_REQUEST;
        }
        if (isset($_REQUEST[ $key ])) {
            return $_REQUEST[ $key ];
        } else {
            return NULL;
        }
    }
}

