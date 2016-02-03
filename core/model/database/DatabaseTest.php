<?php
namespace core\model\database;

class DatabaseTest extends Database {


    public function run() {
        $this->test_database_connection();
        $this->crudTable();
        $this->editTable();
        $this->crudRecord();
        $this->test_table_list();
    }

    private function crudTable()
    {

        //
        $name = "test_table_1";
        test( database()->tableExists($name) == false, '', "$name Table exists.");
        $this->test_createTable($name);
        test( database()->tableExists($name), '', "$name Table does not exist.");
        database()->dropTable($name);
        test( ! database()->tableExists($name) );

        //
        $name = 'test_table_crud';
        $db = $this->test_createTable($name);
        $re = $db->tableExists($name);
        test($re, "OK - $name exists.", "FAILURE - $name was not created.");
        $db->dropTable($name);
        $re = $db->tableExists($name);
        test($re == FALSE, "OK - $name does not exists", "ERROR - $name exists.");

    }

    private function editTable()
    {
        $name = 'test_edit_table';
        $db = $this->test_createTable($name);

        $db->addColumn($name, 'created', 'int unsigned');
        test( $db->columnExists($name, 'created'), 'OK - column exists.', 'ERROR - column does not exist.' );

        $db->addIndex($name, 'created');
        $db->addColumn($name, 'updated', 'int unsigned');
        $db->addUniqueKey($name, 'updated');

        //
        $db->addColumn($name, 'name', 'varchar', 255);
        test( $db->columnExists($name, 'name') );

        //
        test( ! $db->columnExists($name, 'address') );
        $db->addColumn($name, 'address', 'varchar', 255);
        test( $db->columnExists($name, 'address') );


        $db->dropTable($name);
    }

    private function crudRecord()
    {
        $name = 'test_record_crud';
        $db = $this->test_createTable($name);


        $db->insert($name, array('id' => 100));
        $id = $db->insert_id();
        test( $id == 100, "OK", "ERROR - id is not 100");


        $db->insert($name, array('id' => 200));
        $cnt = $db->count($name);
        test( $cnt == 2, "OK", "ERROR - no. of record is not 1. it's $cnt");

        //
        $cnt = $db->count($name, 'id=100');
        test( $cnt == 1, "OK", 'ERROR - no id=100');
        $db->update($name, array('id'=>111), 'id=100');

        $cnt = $db->count($name, 'id=100');
        test( $cnt == 0, "OK", 'ERROR - there is id=100');

        $cnt = $db->count($name, 'id=111');
        test( $cnt, "OK", 'ERROR - there is no id=111');

        //
        $db->insert($name, array('id'=>300));
        test($db->count($name) == 3, "OK", 'ERROR - the no of record is not 3');

        $db->delete($name, 'id=200');
        test($db->count($name) == 2, "OK", 'ERROR - the no of record is not 3');


        $db->dropTable($name);


        // new test set
        $db = $this->test_createTable($name);

        //
        test ( $db->columnExists($name, 'num') == false, 'Ok. num field does not exists.', 'num field exists.' );
        $db->addColumn($name, 'num', 'int');
        $db->addUniqueKey($name, 'num');
        test ( $db->columnExists($name, 'num'), 'Ok. num field exists.', "num field does not exists in $name table." );

        //
        $db->insert($name, array('num'=>500));
        test($db->count($name) == 1, "OK", 'ERROR - the no of record is not 1');

        //$db->insert($name, array('num'=>500));
        //test($db->count($name) == 1, "OK", 'ERROR - the no of record is not 1');




        $db->dropTable($name);
    }

    private function test_createTable($name)
    {
        $db = database();
        $re = $db->tableExists($name);
        if ( $re ) $db->dropTable($name);
        $db->createTable($name);
        return $db;
    }

    /**
     * database connection test.
     */
    public function test_database_connection()
    {
        $name = "test_database_connection";
        $db = new Database();

        test($db->getDatabaseObject());


        if ( $db->tableExists($name) ) {
            $db->dropTable($name);
        }

        // table exists
        test($db->tableExists($name) == FALSE);
        $db->createTable($name);
        test($db->tableExists($name));

        // quote
        // @Attention 반드시 아래의 quote 가 통과를 해야 한다.
        $ret_str = $db->quote("str");
        test( $ret_str == "'str'", '', 'Quote failed...' );
        $ret_str = $db->quote("st'r");
        test( $ret_str == "'st''r'", '', 'Quote failed...' );

        // table drop
        $db->dropTable($name);
        test($db->tableExists($name) == FALSE);

    }

    private function test_table_list()
    {
        if ( database()->tableExists('abcdef') ) database()->dropTable('abcdef');
        database()->createTable('abcdef');
        $tables = database()->getTables();
        test( in_array('abcdef', $tables) );
        database()->dropTable('abcdef');
        $tables = database()->getTables();
        test( ! in_array('abcdef', $tables) );
    }

}
