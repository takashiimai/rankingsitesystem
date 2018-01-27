<?php

class session {

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
     * セッション保存
     *
     * @param
     * @return
     */
    public function get($key) {
        if (isset($_SESSION[ $key ])) {
            return $_SESSION[ $key ];
        } else {
            return NULL;
        }
    }

    /**
     * セッション保存
     *
     * @param
     * @return
     */
    public function set($key, $data) {
        $_SESSION[ $key ] = $data;
    }

    /**
     * セッション保存
     *
     * @param
     * @return
     */
    public function delete($key) {
        if (isset($_SESSION[ $key ])) {
            unset($_SESSION[ $key ]);
        }
    }

    /**
     * セッション全削除
     *
     * @param
     * @return
     */
    public function destory() {
        session_destory();
    }
}

