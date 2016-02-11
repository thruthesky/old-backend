<?php
namespace core\model\database;


/**
 *
 *
 * @attention This class can instantiate the class as much as it can be.
 *
 *      - so the code must be prepared for multiple instantiation.
 *
 *
 */
class Database extends DatabaseLayer {


    public function __construct()
    {
        parent::__construct();
    }

    public function createTable($table_name) {
        $q = "CREATE TABLE $table_name ( id INTEGER PRIMARY KEY AUTOINCREMENT)";
        $re = $this->exec($q);
        if ( ! $re ) {
            $path = path_run();
            die("<hr>Database::createTable() : failed on create table $table_name. $path");
        }
        return $this;
    }
    public function dropTable($table_name) {
        $q = "DROP TABLE $table_name";
        $re = $this->exec($q);
        if ( ! $re ) {
            $path = path_run();
            die("<hr>Database::createTable() : failed on create table $table_name. $path");
        }
        return $this;
    }



    /**
     * @param $table_name
     * @return bool
     *
     */
    public function tableExists($table_name) {
        $rows = $this->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table_name'");
        if ( $rows === FALSE ) return FALSE;
        return count($rows) > 0;
    }


    /**
     * @param $table_name
     * @param null $field_name
     * @return bool - TRUE if the field exists on the table
     *
     * @code
     *      Database::load()->columnExists('temp', 'idx')
     * @endcode
     */
    public function columnExists($table_name, $field_name=null) {
        //$rows = $this->query("SHOW COLUMNS FROM `$table_name` LIKE '$field_name';");

        $rows = $this->query("PRAGMA table_info('$table_name')");
        if ( $rows ) {
            foreach ( $rows as $row ) {
                if ( $row['name'] == $field_name ) return TRUE;
            }
        }
        return FALSE;
    }


    /**
     * @param $table
     * @param array $keys_and_values
     * @return string
     *
     * - If error, returns FALS
     *
     */
    public function insert($table, array $keys_and_values)
    {
        $key_list = array();
        $value_list = array();
        foreach($keys_and_values as $k => $v ) {
            $key_list[] = "`$k`";

            if ( $v === NULL ) {
                $value_list[] = "NULL";
            }
            else {
                $value_list[] = $this->quote($v);
            }
        }
        $keys = implode(",", $key_list);
        $values = implode(",", $value_list);
        $q = "INSERT INTO `{$table}` ({$keys}) VALUES ({$values})";
        $re = $this->exec($q);
        if ( $re ) {
            $insert_id = $this->insert_id();
            return $insert_id;
        }
        return FALSE;
    }


    /**
     * @param $table_name
     * @param $fields
     * @param int $cond
     * @return bool
     */
    public function update($table_name, $fields, $cond=null)
    {
        $sets = array();
        foreach($fields as $k => $v) {
            $sets[] = "`$k`=" . $this->quote($v);
        }
        $set = implode(", ", $sets);
        $where = null;
        if ( $cond ) $where = "WHERE $cond";
        $q = "UPDATE $table_name SET $set $where";

        if ( $this->exec($q) ) return TRUE;
        else die( $this->getErrorString() );
    }


    /**
     * @param $table_name
     * @param $cond
     * @return int
     */
    public function delete($table_name, $cond)
    {
        $q = "DELETE FROM $table_name WHERE $cond";
        return $this->exec($q);
    }







    public function count($table_name, $cond=null)
    {
        if ( $cond ) $where = "WHERE $cond";
        else $where = null;
        $q = "SELECT COUNT(*) AS cnt FROM $table_name $where";
        return $this->result($q);
    }

    public function rows($q) {
        return $this->query($q);
    }

    public function row($q) {
        $rows = $this->rows($q);
        if ( $rows ) return $rows[0];
        else return array();
    }
    public function result($q) {
        $row = $this->row($q);
        if ( $row ) {
            foreach( $row as $k=>$v ) {
                return $v;
            }
        }
        return FALSE;
    }

    /**
     * Adds a field to the table
     * @param $table_name
     * @param $field
     * @param $type
     * @param int $size
     * @return bool
     *
     * @code
     *
            $this->init();
            $this->addColumn($table_name, 'position', 'varchar', 64);
            $this->addColumn($table_name, 'fid', 'int unsigned');
            $this->addColumn($table_name, 'active', 'char');
            $this->addColumn($table_name, 'date_from', 'int unsigned');
            $this->addColumn($table_name, 'date_to', 'int unsigned');
            $this->addColumn($table_name, 'subject', 'varchar');
            $this->addColumn($table_name, 'content', 'text');
            $this->addColumn($table_name, 'list_order', 'int');
     *
     * @endcode
     */
    public function addColumn( $table_name, $field, $type, $size=0, $default='') {

        if ( empty($size) ) {
            if ( $type == 'varchar' ) $size = 255;
            else if ( $type == 'char' ) $size = 1;
        }

        if ( empty($default) ) {
            if ( stripos($type, 'int') !== false ) $default = 0;
            else if ( stripos($type, 'text') !== false ) $default = 'NULL';
            else $default = "'$default'";
        }

        if ( $size ) $type = "$type($size)";
        $q = "ALTER TABLE `$table_name` ADD COLUMN `$field` $type DEFAULT $default";
        return $this->exec($q);

    }

    /**
     * @param $table_name
     * @param $field
     * @return $this|bool
     */
    public function deleteColumn($table_name, $field) {
        $q = "ALTER TABLE $table_name DROP $field;";
        return $this->exec($q);
    }


    public function addUniqueKey($table_name, $fields)
    {
        $keyname = str_replace(',', '_', $fields);
        $keyname = str_replace(' ', '_', $fields);
        $q = "CREATE UNIQUE INDEX IF NOT EXISTS $keyname ON $table_name ($fields)";
        return $this->exec($q);
    }



    /**
     *
     *
     * @param $table_name
     * @param $fields
     * @return bool
     * @code
            $db->addColumn($name, 'created', 'int unsigned');
            $db->addIndex($name, 'created');
     * @endcode
     * @code indexing on two column
            $db->addIndex($name, 'created,updated');
     * @endcode
     *
     */
    public function addIndex($table_name, $fields) {
        $keyname = str_replace(',', '_', $fields);
        $keyname = str_replace(' ', '_', $keyname);
        $q = "CREATE INDEX IF NOT EXISTS $keyname ON $table_name ($fields)";
        return $this->exec($q);
    }



}
