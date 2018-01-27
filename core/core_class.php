<?php

// コントローラの基底クラス
class core {

    public $config = array();
    public $directory = '';
    public $controller = '';
    public $action = '';

    /**
     * construct
     *
     * @param
     * @return
     */
    public function __construct() {
//        $this->library('log');
    }

    /**
     * deconstruct
     *
     * @param
     * @return
     */
    public function __destruct() {
    }

    /**
     * モデルロード
     *
     * @param
     * @return
     */
    public function model($model) {
        $fn = APP_PATH . "/model/{$model}.php";
        if (file_exists($fn)) {
            require_once($fn);
            if (strpos($model, '/') !== FALSE) {
                $model = preg_replace("/.*\//", '', $model);
            }
            $this->{$model} = new $model;
        }
    }

    /**
     * ライブラリロード
     *
     * @param
     * @return
     */
    public function library($library, $alias = NULL) {
        if (is_null($alias)) {
            $alias = $library;
        }
        $fn = APP_PATH . "/library/{$library}.php";
        if (file_exists($fn)) {
            require_once($fn);
            $this->{$alias} = new $library;
        } else {
            $fn = SYSTEM_PATH . "/library/{$library}.php";
            if (file_exists($fn)) {
                require_once($fn);
                $this->{$alias} = new $library;
            }
        }
    }

    /**
     * ビューロード
     *
     * @param
     * @return
     */
    public function view($view, $params = array()) {
        $fn = APP_PATH . "/view/{$view}.php";
        if (file_exists($fn)) {
            extract($params);
            include_once($fn);
        }
    }
}

