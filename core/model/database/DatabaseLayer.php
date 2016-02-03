<?php
namespace core\model\database;


use Exception;

class DatabaseLayer extends \SQLite3
{

    private $db = null;

    public function __construct()
    {
        $this->open(PATH_SQLITE);
    }

    public function getDatabaseObject() {
        return $this;
    }

    /**
     * This method executes a query which is 'write' type - create, insert, update, delete
     *
     * @note This routine is used to execute a result-less query.
     *
     * @attention this method return TRUE or FALSE depending on the query execution.
     *
     *      - if you need to get records, use query()
     *
     * @attention Do not use this methods on
     *
     *      SELECT, SHOW, DESCRIBE or EXPLAIN queries
     *
     * @param $q
     * @return boolean
     *
     *      - Return TRUE on success.
     * @Attention it dies on Failure.
     *
     */
    public function exec($q) {
        sys()->log($q);

        $re = parent::exec($q);
        if ( $re === FALSE ) {
            $code = $this->lastErrorCode();
            $message = $this->lastErrorMsg();
            die( "SQLite3::exec() failed. ERROR ($code) : $message");
        }
        else return $re;

    }


    /**
     * @param $q
     * @return bool | array
     *
     *      - if it has empty table, it returns empty array()
     *      - if it has records, it returns in assoc-array.
     *
     * @code
    $rows = $db->query("SELECT id FROM abc");
    print_r($rows);
     * @endcode
     *
     */
    public function query($q) {
        sys()->log($q);
        $ret = parent::query($q);
        $rows = array();
        if ( $ret ) {
            while ( $row = $ret->fetchArray(SQLITE3_ASSOC) ) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * @param $str
     * @return mixed
     */
    public function quote($str) {
        $str = $this->escapeString($str);
        return "'$str'";
    }

    public function insert_id() {
        return $this->lastInsertRowID();
    }


    public function getErrorString()
    {
        if ( sys()->isSapcms1() ) {
            $str = $this->db->error;
            $str .= "<hr>";
            $str .= get_backtrace();
            return $str;
        }
        else {
            return $this->db->error;
        }
    }

    public function getTables() {
        $rets = array();
        $rows = $this->query("SELECT name FROM sqlite_master WHERE type='table'");
        if ( empty($rows) ) return $rets;
        foreach( $rows as $row ) {
            $rets[] = $row['name'];
        }
        return $rets;
    }

}
