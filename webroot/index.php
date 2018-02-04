<?php
session_start();

define('SYSTEM_PATH',   dirname(__FILE__) . '/../core');
define('APP_PATH',      dirname(__FILE__) . '/../app');

require_once(SYSTEM_PATH . '/core_class.php');
require_once(SYSTEM_PATH . '/db_model.php');
require_once(APP_PATH .    '/controller/app_controller.php');
require_once(APP_PATH .    '/model/app_model.php');

new main();

class main {
    protected $directory = '';
    protected $controller = '';
    protected $action = '';
    protected $params = array();

    public function __construct() {
        $this->_set_env();
        $this->_routes();
        $this->_controller();
    }

    //--------------------------------
    // 環境設定
    //--------------------------------
    protected function _set_env() {
        $fn = APP_PATH . "/config/environment.php";
        if (file_exists($fn)) {
            require_once($fn);
        }
        if (!defined('ENVIRONMENT')) {
            define('ENVIRONMENT', 'production');
        }
    }

    //--------------------------------
    // ルーティング
    //--------------------------------
    protected function _routes() {
        if (isset($_GET['url'])) {
            $this->params = explode('/', $_GET['url']);
            if (file_exists(APP_PATH . '/controller/' . $this->params[0])) {
                $this->directory   = array_shift($this->params) . '/';
            }
            $this->controller   = array_shift($this->params);
            $this->action       = array_shift($this->params);
        } else {
            $this->controller = 'index';
            $this->params = array();
        }
        if (!$this->action) $this->action = 'index';
    }

    //--------------------------------
    // コンフィグ（共通）
    //--------------------------------
    protected function _config_common() {
        // 共通コンフィグ読み込み
        $fn = APP_PATH . "/config/config.php";
        if (file_exists($fn)) {
            require_once($fn);
        } else {
            $this->_show_error('500');
        }
        return $config;
    }

    //--------------------------------
    // コンフィグ（環境ごと）
    //--------------------------------
    protected function _config_env() {
        $fn = APP_PATH . "/config/" . ENVIRONMENT . '/config.php';
        if (file_exists($fn)) {
            require_once($fn);
        } else {
            $this->_show_error('500');
        }
        return $config;
    }

    //--------------------------------
    // データベース設定
    //--------------------------------
    protected function _database() {
        $fn = APP_PATH . "/config/" . ENVIRONMENT . '/database.php';
        if (file_exists($fn)) {
            require_once($fn);
        } else {
            $this->_show_error('500');
        }
    }

    //--------------------------------
    // ユーザーコントローラ読み込み＆実行
    //--------------------------------
    protected function _controller() {
        $directory = $this->directory;
        $controller = $this->controller . '_controller';
        $action = $this->action;

        // コントローラーファイル読み込み
        $fn = APP_PATH . "/controller/{$directory}{$controller}.php";
        if (file_exists($fn)) {
            require_once($fn);
        } else {
            $this->_show_error('403');
        }

        // コントローラ インスタンス作成
        $ctrl = new $controller;

        // クラスメソッドがなければエラー
        if (!method_exists($ctrl, $action)) {
            $this->_show_error('403');
        }

        // 設定読み込み
        $config_common = $this->_config_common();
        $config_env    = $this->_config_env();
        $ctrl->config = array_merge($config_common, $config_env);
        $GLOBALS['config'] = $ctrl->config;

        // ライブラリロード
        if (isset($ctrl->config['library']) && is_array($ctrl->config['library'])) {
            foreach ($ctrl->config['library'] as $libary) {
                $ctrl->library($libary);
            }
        }

        // データベース設定
        $this->_database();

        //
        $ctrl->directory = $this->directory;
        $ctrl->controller = $this->controller;
        $ctrl->action = $this->action;

        // メソッド実行
        call_user_func_array(array($ctrl, $action), $this->params);
    }

    //--------------------------------
    // エラー
    //--------------------------------
    protected function _show_error($code = '404') {
        echo "Not Found.";
        exit;
    }
}

