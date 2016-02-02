<?php

namespace core\model\entity;

class Entity {
    private $table;
    private $db = null;
    protected $record = array();

    public function __construct()
    {
        $this->db = database();
    }

    /**
     * @param $name
     * @return $this|Entity
     */
    public function setTableName($name) {
        $this->table = $this->adjustTableName($name);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTableName() {
        return $this->table;
    }

    final protected function adjustTableName($name) {
        return $name . '_entity';
    }


    /**
     * @return Entity|$this
     */
    public function init() {
        $name = $this->getTableName();
        $db = $this->db->createTable( $name );
        $db->addColumn($name, 'created', 'int unsigned');
        $db->addIndex($name, 'created');
        $db->addColumn($name, 'updated', 'int unsigned');
        $db->addIndex($name, 'updated');
        return $this;
    }

    public function uninit() {
        return $this->db->dropTable($this->getTableName());
    }

    /**
     * Return true if the Entity table exists.
     * @param null $tablename
     * @return bool
     */
    public function exists( $tablename = null ) {
        if ( empty($tablename ) ) $tablename = $this->getTableName();
        return $this->db->tableExists( $tablename );
    }

    /**
     *
     * Returns TRUE if the entity item is exists. or FALSE.
     * @return bool|mixed
     *
     *
     *
     */
    public function is() {
        return self::get('id') > 0;
    }





    /**
     *
     *
     *
     * @note Use this method when you cannot use $this->set() directly for some inheritance reason.
     * @note 여기서 escape 하지 않는다.
     *
     * @param $field
     * @param $value
     * @return $this|Entity
     */
    public function set($field, $value) {
        if ( ! is_string($field) ) die("<hr>field [ $field ] of entity()->set() field must be string. " . __FILE__ . ' at line ' . __LINE__);
        $this->record[$field] = "$value";
        return $this;
    }


    /**
     *
     * It gets assoc-array to set $this->record
     *
     * @param $fields
     * @return $this
     *
     * @code
        return data()->create()
            ->sets($record)
            ->set('name', $info['client_name'])
            ->save();
     * @endcode
     */
    public function sets( array $fields ) {
        $this->record = array_merge($this->record, $fields);
        return $this;
    }


    /**
     * Returns the value of the field in the item record.
     *
     *
     * @param $field
     * @return mixed|bool
     *      - returns FALSE if the field is not set.
     */
    public function get($field) {
        if ( isset($this->record[$field]) ) {
            return $this->record[$field];
        }
        else {
            return FALSE;
        }
    }
    public function getRecord() {
        return $this->record;
    }


    /**
     * @return Entity|$this
     */
    public function create() {
        return $this->reset();
    }


    /**
     * Deletes the current Entity
     */
    public function delete() {
        $this->db->delete($this->getTableName(), "id=" . self::get('id'));
        $this->record = array();
    }
    public function deleteEntities( array $entities = array () ) {
        if ( empty($entities ) ) return;
        foreach( $entities as $entity ) {
            $entity->delete();
        }
    }


    /**
     * Resets the entity
     *
     * @return $this
     */
    private function reset() {
        $this->record = array();
        self::set('created', time());
        self::set('updated', 0);
        return $this;
    }


    /**
     *
     * Saves or Updates an item.
     *
     * @note if it has value in $this->get('id'), then it updates.
     * @note when it updates, it updates 'updated' field.
     *
     * @return Entity|boolean
     *
     *      - FALSE if $this->record is empty.
     *
     * @example EntityTest.php 를 본다.
     *
     * @code 입력(생성)에서 에러가 잇는 경우 확인하는 방법.
     *
    $entity = $this
    ->create()
    ->save();
    if ( $entity ) {
    json_success( $entity->getRecord() );
    }
    else {
    json_error(-40041, "Failed to create entity");
    }
     *
     * @endcode
     */
    public function save() {
        if ( empty($this->record) ) return FALSE;

        if ( $id = self::get('id') ) {
            self::set('updated', time());
            $this->db->update(
                $this->getTableName(),
                $this->record,
                "id=$id"
            );
        }
        else {
            $re = $this->db->insert(
                $this->getTableName(),
                $this->record
            );
            if ( $re === FALSE ) return FALSE;
            $this->record['id'] = $this->db->insert_id();
        }
        return $this;
    }


    /**
     *
     * 하나의 필드를 entity()->set()->save() 를 하지 않고 곧 바로 데이터베이스에 저장한다.
     *
     * entity()->set()->save() 를 통해서 하면 모든 레코드를 다 저장해야하지만,
     *
     * put() 을 통해서 하면 하나의 레코드만 저장하면 되다.
     *
     * @param $field
     * @param $value
     * @return bool
     */
    public function put($field, $value) {
        if ( $id = self::get('id') ) {
            $this->db->update(
                $this->getTableName(),
                array($field => $value),
                "id=$id"
            );
            $this->record[$field] = $value;
            return $this;
        }
        else return FALSE;
    }


    /**
     * @param null $cond - same as database->count()
     * @return mixed
     *
     * @code
     *  $no = data()->count("gid='philgo-banner'");
     * @endcode
     */
    public function count($cond=null)
    {
        return $this->db->count($this->getTableName(), $cond);
    }



    /**
     * Returns a new object of the Entity of input - $id
     *
     * @note it loads a record into $this->record and returns in a new object.
     *
     * @attention The calling object's $record is changed.
     *
     * @note if the input $id is 0, then it loads the entity of id with 0.
     *
     * @param $id
     *      - if it is numeric, then it is the id of the entity item.
     *      - if it is string, then it is the SQL Where condition
     * @param string $fields
     *      - fields to retrieve.
     *      - by default, it gets all fields.
     * @return FALSE|Entity - if there is no record, then it returns FALSE
     * - if there is no record, then it returns FALSE
     * @code
     *      $this->load(1)                                 // load by id
     * @endcode
     *
     * @code Load with condition
     *      entity($name)->load("name='jaeho'");
     * @endcode
     * @code Load with condition and check the result
        $entity = $this->load("user_id=$id AND date=$date");
        if ( ! $entity ) json_error(-40445, "You have attended already on $date");
     * @endcode
     */
    public function load($id, $fields='*') {
        sys()->log("Entity::load($id, $fields)");
        if ( is_numeric($id) ) $where = "WHERE id=$id";
        else $where = "WHERE $id";
        if ( empty($fields) ) $fields = '*';
        $row = $this->db->row("SELECT $fields FROM " . $this->getTableName() . " $where");
        //sys()->log($row);
        $this->record = $row;
        if ( $this->record ) {
            return clone $this;
            //$obj = entity($this->getTableName());
            //$obj->record = $this->record;
            //return $obj;
        }
        else {
            //di('load() no entity');
            return FALSE;
        }
    }


    /**
     *
     * Returns an array of Entity object based on the input ID array()
     *
     * @param array $ids - array() of ID(s) to retrieve.
     * @param string $fields
     * @return array|bool
     */
    public function loads(array $ids, $fields='*') {
        if ( empty($ids) ) return FALSE;
        $these = array();
        foreach ($ids as $id) {
            $these[] = self::load($id, $fields);
        }
        return $these;
    }

    /**
     * Loads all entity of the table.
     * @param string $fields
     * @return array
     */
    public function loadAll($fields='*') {
        return $this->loadQuery(null, $fields);
    }


    /**
     * Returns an array of Entity object based on the Query.
     *
     * @flowchart
     *  - first, queries
     *  - and get the 'id's of the item
     *  - and loads it into array and return.
     *
     * @param string $where - condition
     * @param string $fields
     * @return array
     * @attention the input $where can have extra SQL clause like 'LIMIT', 'ORDER BY'
     * @code
     *      return $this->loadQuery("id_root=$id_root AND id_parent>0 ORDER BY order_list ASC");
     * @endcode
     */
    public function loadQuery($where=null, $fields='*') {
        if ( $where ) $where = "WHERE $where";
        $rows = $this->db->query("SELECT id FROM " . $this->getTableName() . " $where");
        return self::loads( $this->getIDs($rows), $fields );
    }


    /**
     * This is a wrapper of loadQuery() to make it easy to use.
     *
     * @param array $o
     *      - $o['where'] is SQL WHERE condition
     *      - $o['limit'] is the number of records to retrieve.
     *          If there is no limit, then it will just pull out all entity.
     *
     *      - $o['offset'] is the offset to retrieve records from.
     *      - $o['page'] is the page number to retrieve the block of record.
     *
     *      - $o['order_by'] is the ORDER clause
     *      - $o['fields'] is the fields to retrieve. for instance, 'id, created'
     *
     * @note
     *  - limit 과 page 를 같이 쓰는 경우는 page 별로 레코드를 추출하는 경우이다.
     *  - limit 과 offset 을 쓰는 경우는 특정 위치 부터 몇 개의 레코드를 추출하는 경우이다.
     *  - limit 만 쓰는 경우는 맨 처음 부터 몇 개의 레코드를 추출하는 경우이다.
     * @return array
     */
    public function search( array $o = array() ) {

        $where = $order_by = $limit = $offset = $page = $fields = null;
        if ( isset($o['where']) ) $where = "$o[where]";
        else $where = 1;
        if ( isset($o['order_by']) ) $order_by = "ORDER BY $o[order_by]";
        if ( isset($o['fields']) ) $fields = $o['fields'];

        if ( isset($o['limit']) ) {
            if ( isset($o['offset']) ) {
                $limit = "LIMIT $o[offset], $o[limit]";
            }
            else if ( isset($o['page'] ) ) {
                $offset = $o['limit'] * (page_no($o['page']) - 1);
                $limit = "LIMIT $offset, $o[limit]";
            }
            else $limit = "LIMIT $o[limit]";
        }

        return $this->loadQuery("$where $order_by $limit", $fields);
    }

    public function page($page_no, $limit)
    {
        return $this->search( array( 'page' => $page_no, 'limit' => $limit ) );
    }




    public function addColumn( $field, $type, $size=0, $default='') {
        $this->db->addColumn( $this->getTableName(), $field, $type, $size, $default);
        return $this;
    }


    /**
     * @param $fields
     * @return $this
     * @todo check error
     */
    public function addUniqueKey($fields)
    {
        $re = $this->db->addUniqueKey( $this->getTableName(), $fields );
        return $this;
    }


    /**
     * @param $fields
     * @return $this
     *
     * @todo check error
     */
    public function addIndex($fields) {
        $re = $this->db->addIndex( $this->getTableName(), $fields );
        return $this;
    }



    public function columnExists($field_name)
    {
        return $this->db->columnExists($this->getTableName(), $field_name);
    }

    private function getIDs($rows)
    {
        $ids = array();
        if ( empty($rows) ) return $ids;
        foreach ( $rows as $row ) {
            $ids[] = $row['id'];
        }
        return $ids;
    }




    /**
     * @param $field
     * @param $where
     * @return null
     *
     */
    public function result($field, $where) {
        $table = $this->getTableName();
        $q = "SELECT $field FROM $table WHERE $where";
        return $this->db->result($q);
    }

    /**
     * @param $where
     * @param string $fields
     * @return null
     * @code Getting the first row.
     *      $query = $entity->row();
     *      di($this->db->last_query());
     * @endcode
     */
    public function row($where=null, $fields='*') {

        $rows = $this->rows($where . ' LIMIT 1', $fields);
        if ( $rows ) {
            return $rows[0];
        }
        return array();
    }

    /**
     * @param null $where
     *      - 'code'='abc' LIMIT 3
     * @param string $fields
     *      - name,address
     * @return mixed
     *
     */
    public function rows($where=null, $fields='*') {
        $table = $this->getTableName();
        if ( $where ) $where = "WHERE $where";
        return $this->db->rows("SELECT $fields FROM $table $where");
    }


}


