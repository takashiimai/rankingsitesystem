<?php

class file_model extends app_model {

    /**
     * construct
     *
     * @param
     * @return
     */
    public function __construct() {
		parent::__construct();
    }

    
    /**
     * 
     *
     * @param
     * @return
     */
    public function index() {
        global $config;
        var_dump($config);exit;
    }

    /**
     * ファイルのアップロード
     *
     * @param
     * @return
     */
    public function upload($file, $upload_dir = '') {
        global $config;

        $this->library("log");        
        $this->log->info($upload_dir);
        if (!is_uploaded_file($file['tmp_name'])) {
            return NULL;
        }

        if (!strlen($upload_dir)) {
            $upload_dir = $config['user_dir'];
        }

        $fileinfo = explode(".", $file['name']);
        $md5 = md5(microtime() . ' ' . rand(1,10000));
        $path_dir = sprintf("%s%s/", $upload_dir, substr($md5, 0, 2));
        $path_fn  = sprintf("%s.%s", $md5, end($fileinfo));

        $this->log->info($_SERVER['DOCUMENT_ROOT'] . $path_dir);
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $path_dir)) {
            $r = @mkdir($_SERVER['DOCUMENT_ROOT'] . $path_dir);
            $this->log->info("結果:".$r);
            
        }

        if (!move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $path_dir . $path_fn)) {
            return NULL;
        } else {
            @chmod($_SERVER['DOCUMENT_ROOT'] . $path_dir . $path_fn, 0666);
            return $path_dir . $path_fn;
        }
    }

}

