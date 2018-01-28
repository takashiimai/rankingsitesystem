<?php

class log {

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
     * info ログ出力
     *
     * @param
     * @return
     */
    public function info($message) {
        $this->_log('info', $message);
    }


    /**
     * debug ログ出力
     *
     * @param
     * @return
     */
    public function debug($message) {
        $this->_log('debug', $message);
    }

    /**
     * batch ログ出力
     *
     * @param
     * @return
     */
    public function batch($message) {
        $this->_log('batch', $message);
    }


    /**
     * ログ出力
     *
     * @param
     * @return
     */
    protected function _log($type, $message) {
        if (is_array($message)) {
            $message = print_r($message, TRUE);
        }
        $debug_backtrace = debug_backtrace();
        array_shift($debug_backtrace);
        $log = sprintf("%s (PID.%s)%s[L.%s] %s",
                   date("y/m/d H:i:s"),
                   getmypid(),
                   basename($debug_backtrace[0]['file']),
                   $debug_backtrace[0]['line'],
                   $message
               );
        error_log($log . "\n", 3, APP_PATH . '/logs/' . $type . '.log');
    }

}

