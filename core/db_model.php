<?php

class db_model extends core {

    protected $db = NULL;
    protected $last_id = NULL;
    protected $found_rows = NULL;

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
     * データベース接続
     *
     * @param
     * @return
     */
    public function connect($key = 'default') {
        try {
            $dsn  = sprintf("mysql:dbname=%s;host=%s;charset=%s",
                        $GLOBALS['databases'][ $key ]['database'],
                        $GLOBALS['databases'][ $key ]['hostname'],
                        $GLOBALS['databases'][ $key ]['char_set']
                     );
            $user = $GLOBALS['databases'][ $key ]['username'];
            $pwd  = $GLOBALS['databases'][ $key ]['password'];
            $this->db = new PDO($dsn, $user, $pwd);
            $this->db->query('SET NAMES ' . $GLOBALS['databases'][ $key ]['char_set']);
        } catch (PDOException $e) {
            throw new Exception();
        }
    }

    /**
     * テーブルの全件数を返却する
     *
     * @param   
     * @param   
     * @return  integer
     */
    public function get_found_rows() {
        return $this->found_rows;
    }

    /**
     * autoincrement IDを返却する
     *
     * @param   
     * @param   
     * @return  integer
     */
    public function get_last_insert_id() {
        return $this->last_id;
    }

    /**
     * トランザクション開始
     *
     * @param   
     * @param   
     * @return  integer
     */
    public function trans_start() {
        $this->query('START TRANSACTION');
    }

    /**
     * トランザクションコミット
     *
     * @param   
     * @param   
     * @return  integer
     */
    public function trans_commit() {
        $this->query('COMMIT');
    }

    /**
     * トランザクションロールバック
     *
     * @param   
     * @param   
     * @return  integer
     */
    public function trans_rollback() {
        $this->query('ROLLBACK');
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
            return $this->select('SELECT * FROM ' . $table . ' WHERE ' . $key . ' = :' . $key, $params);
        } catch (PDOException $e) {
            throw new Exception();
        }
    }

    /**
     * 
     *
     * @param   string  クエリー
     * @param   array   WHEREのパラメータ ※キーはプレースホルダで指定
     * @return  array
     */
    public function query($sql = NULL, $parameters = array()) {
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($parameters);
        } catch (PDOException $e) {
            throw new Exception();
        }
    }


    /**
     * データベースからSELECTする
     *
     * @param   string  クエリー
     * @param   array   WHEREのパラメータ ※キーはプレースホルダで指定
     * @return  array
     */
    public function select($sql = NULL, $parameters = array()) {
        try {
            $stmt = $this->db->prepare(str_replace(array("SELECT ", "select "), "SELECT SQL_CALC_FOUND_ROWS ", $sql));
            $ret = $stmt->execute($parameters);
            if ($ret) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $sql = 'SELECT FOUND_ROWS() AS count';
                $stmt = $this->db->query($sql);
                $result2 = $stmt->fetch();
                $this->found_rows = $result2['count'];

                return $result;
            } else {
                return array();
            }
        } catch (PDOException $e) {
            throw new Exception();
        }
    }


    /**
     * データベースにINSERTする
     *
     * @param   string  テーブル名
     * @param   array   INSERTする内容 ※キーは ":".カラム名 の形式
     * @return
     */
    public function insert($tblname = NULL, $parameters = array()) {
        try {
            $fmt = "INSERT INTO %s (%s) VALUES(%s);";
            $sql = sprintf(
                    $fmt,
                    $tblname,
                    implode(",", array_map(create_function('$e', 'return "`".trim($e, ":")."`";'), array_keys($parameters))),
                    implode(",", array_keys($parameters))
            );
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute($parameters)) {
                throw new Exception();
            }

            $sql = 'SELECT last_insert_id() AS id';
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();
            $this->last_id = $result['id'];
        } catch (PDOException $e) {
            throw new Exception();
        }
    }

    /**
     * データベースをUPDATEする
     *
     * @param   string  テーブル名
     * @param   array   UPDATEする時の条件 ※キーは ":".カラム名 の形式
     * @param   array   UPDATEする内容     ※キーは ":".カラム名 の形式
     * @return
     */
    public function update($tblname = NULL, $key = array(), $parameters = array()) {
        try {
            $fmt = "UPDATE %s SET %s WHERE %s;";
            $sql = sprintf(
                    $fmt,
                    $tblname,
                    implode(",", array_map(create_function('$e', 'return "`".trim($e, ":")."`" . "=" . $e;'), array_keys($parameters))),
                    implode(",", array_map(create_function('$e', 'return "`".trim($e, ":")."`" . "=" . $e;'), array_keys($key)))
            );
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_merge($key, $parameters));
        } catch (PDOException $e) {
            throw new Exception();
        }
    }
}

