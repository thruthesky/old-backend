<?php
namespace core\model\entity;

class EntityTest extends Entity
{

    public function run() {
        $this->test_entity_table_name();
        $this->test_entity_init();
        $this->test_crud();
        $this->test_select();
        $this->test_select_fields();
        $this->test_search();
        $this->test_query();
        $this->test_puts();
        $this->test_delete_all();
        $this->test_delete_query();
    }

    public function test_entity_table_name()
    {

        $name = 'abc';

        //
        $entity = new Entity();
        test( $entity instanceof Entity, 'OK', 'ERROR');
        test( entity() instanceof Entity, 'OK', 'ERROR');

        //
        $entity->setTableName($name);

        test($entity->getTableName() == entity()->adjustTableName($name), 'OK', 'ERROR - table name adjusting failed');
        test( $entity->getTableName() == $this->adjustTableName($name), 'OK', 'ERROR');

        //
    }

    private function test_entity_init()
    {
        $name = 'test_entity_init';
        $entity = entity($name);
        if ( $entity->exists() ) $entity->uninit();
        $entity->init();
        test( $entity->exists(), 'OK', 'ERROR');
        $entity->uninit();

        test( $entity->exists() == FALSE, 'OK', 'ERROR');
    }

    private function test_crud()
    {
        $name = 'test_crud';
        $entity = entity($name);
        if ( $entity->exists() ) $entity->uninit();
        $entity
            ->init()
            ->create()
            ->save();

        test( $entity->count() == 1, 'OK', 'ERROR');

        $id = $entity->get('id');

        $new_entity = entity($name)->load($id);
        test($new_entity);
        if ( $new_entity ) {
            test( $new_entity->get('id') == $id, "OK", "ERROR - test_crud() - load failed");
        }

        $entity
            ->create()
            ->save();

        test( $entity->count() == 2, 'OK', 'ERROR');

        $entity->addColumn('name', 'varchar', 32);

        test( $entity->columnExists('name'), 'OK - Entity column added', 'ERROR - failed on adding entity column');

        ///
        $entity->set('name', 'jaeho')->save();

        $e = entity($name);

        $new_entity = $e->load("name='jaeho'");

        test($new_entity);

        if ( $new_entity ) {
            test( $new_entity instanceof Entity, 'OK ', 'ERROR - entity inheritance');
            test( $new_entity->get('name') == 'jaeho', 'OK', 'ERROR - entity update error');
            test( $new_entity->get('id') == $entity->get('id'), 'OK', 'ERROR - entities are not the same');
        }
        else {
            die("<hr>Entity load failed: " . __FILE__ . ' at ' . __LINE__ );
        }


        /// entity count
        entity($name)->set('name', 'eunsu jung')->save();
        test( $entity->count() == 3 );
        test(count(entity($name)->loadAll()) == 3);

        $nobody = entity($name)->set('name', 'nobody')->save();
        test( entity($name)->count() == 4 );

        /// entity delete
        $nobody->delete();
        test( entity($name)->count() == 3 );



        // entity instance check
        $re = true;
        $all = entity($name)->loadAll();
        test( $all );
        if ( $all ) {
            foreach ( $all as $obj ) {
                if ( $obj instanceof Entity ) {
                }
                else {
                    $re = false;
                    break;
                }
            }
        }
        test ( $re );

        $entity->uninit();
    }

    private function test_select()
    {
        $table = 'test_select';

        $entity = entity($table);
        if ( $entity->exists() ) $entity->uninit();


        $entity
            ->init()
            ->addColumn('name', 'varchar', 32)
            ->addUniqueKey('name');

        $entity->create()->set('name', 'JaeHo Song')->save();
        $entity->create()->set('name', 'Eunsu Jung')->save();
        $entity->create()->set('name', 'Nobody')->save();
        $thruthesky = $entity->create()->set('name', 'thruthesky')->save();

        test( $entity->count() == 4 );
        test( count($entity->loadAll()) == 4 );

        test( count( $entity->loadQuery("name like '%j%'") ) == 2 );
        test( count( $entity->loadQuery("name like '%jung'") ) == 1 );

        $entities = $entity->loads( array( $thruthesky->get('id') ) );
        $ne = array_shift($entities);
        test($thruthesky);
        test($ne);
        if ( $thruthesky && $ne ) test( $thruthesky->get('id') == $ne->get('id') );

        $entity->uninit();

    }



    private function createDefaultTable()
    {
        $table = 'test_table';

        $entity = entity($table);
        if ( $entity->exists() ) $entity->uninit();

        $entity
            ->init()
            ->addColumn('name', 'varchar', 32)
            ->addUniqueKey('name')
            ->addColumn('address', 'varchar')
            ->addIndex('address');

        $entity->create()->set('name', 'JaeHo Song')->set('address', 'KimHae')->save();
        $entity->create()->set('name', 'Eunsu Jung')->set('address', 'KangWonDo')->save();
        $entity->create()->set('name', 'Jack')->set('address', 'United State, Toronto.')->save();
        $entity->create()->set('name', 'Joshua')->set('address', 'United State, Toronto.')->save();
        $entity->create()->set('name', 'Jeimy')->set('address', 'United State, Toronto.')->save();
        $entity->create()->set('name', 'Nobody')->set('address', 'No Where')->save();
        $entity->create()->set('name', 'thruthesky')->set('address', 'Internet')->save();

        return $entity;
    }




    private function test_select_fields()
    {
        $entity = $this->createDefaultTable();

        //
        $entities = $entity->loadQuery("name like '%j%'", 'id,address');
        test($entities);
        if ( $entities ) {
            $first = array_shift($entities);
            test($first);
            if ( $first ) {
                $re = $first->getRecord();
                test( isset($re['address']) );
                test( ! isset($re['name']) );
            }
        }
        $entity->uninit();
    }

    private function test_search( )
    {
        $entity = $this->createDefaultTable();
        for( $i = 0; $i < 22; $i ++ ) {
            $entity
                ->create()
                ->sets( array('name'=>"name $i", 'address' => "address $i"))
                ->save();
        }

        $entities = $entity->search();
        test( count($entities) >= 22 );

        //
        $entities = $entity->search( array(
            'page' => 2,
            'limit' => 5,
        ));
        test( count($entities) == 5 );

        //
        $page = $entity->page(2, 5);
        test( count($page) == 5 );

        //
        $items = $entity->search( array('offset'=>5, 'limit'=>5) );
        test( $items );
        $re = false;
        if ( $items ) {
            for ( $i = 0; $i < 5; $i ++ ) {
                $a = $entities[$i];
                $b = $page[$i];
                $c = $items[$i];
                if ( $a->get('id') == $b->get('id') && $a->get('id') == $c->get('id') ) {
                    $re = true;
                }
                else {
                    $re = false;
                    break;
                }
            }
        }
        test( $re );

        // count with condition
        $where = "name like '%j%'";
        $entities = $entity->search ( array(
            'where' => $where,
            'page' => 2,
            'limit' => 2,
        ) );
        $cnt = $entity->count( $where );
        test( $cnt > count( $entities) );

        $entities = $entity->loadQuery($where);
        test( $cnt == count( $entities) );

        //
        $count_total = $entity->count();
        $count_entities = count($entities);
        if ( $entities ) $entity->deleteEntities( $entities );
        test( $entity->count() == ( $count_total - $count_entities ) );

        $entity->uninit();
    }

    private function test_query()
    {
        $entity = $this->createDefaultTable();

        $row = $entity->row("name like '%jung%'", 'id,name');
        $re = $entity->result('name', "name like '%jung%'");
        test( $row['name'] == $re );
        $entity->uninit();
    }

    public function test_puts()
    {
        $entity = $this->createDefaultTable();
        $entity->addColumn('age', 'int');

        $A = $entity->create()->set('name', 'A')->set('address', 'B')->set('age', 1)->save();

        $item = $entity->load("name='A'");

        test( $A->get('id') == $item->get('id') );
        test( $A->get('address') == 'B' );
        test( $A->get('age') == 1 );

        $A->puts(['address'=>'Address2', 'age'=>2]);


        $U = $entity->load("name='A'");
        test( $A->get('id') == $U->get('id'), '', "id same." );

        sys()->log("address of B: " . $A->get('address'));
        test( $A->get('address') == 'Address2', '', 'Address is changed since it is the object of main' );
        test( $item->get('address') == 'B', '', 'Address is not changed');

        test( $item->get('age') == 1, '', 'Age is 1' );


        test( $U->get('address') == 'Address2' );
        test( $U->get('age') == 2 );

        test( $item->get('name') == $U->get('name') );


        $entity->uninit();


    }

    /**
     *
     * @code
     *      php index.php route=entity.EntityTest.test_delete_all
     * @endcode
     */
    public function test_delete_all()
    {
        $name = 'test_delete_all';
        $entity = entity($name);
        if ( $entity->exists() ) $entity->uninit();
        $entity
            ->init()
            ->create()
            ->save();
        test( $entity->count() == 1, 'OK', 'ERROR');
        $entity->deleteAll();
        test( $entity->count() == 0, 'OK', 'ERROR');
        $entity->uninit();
    }

    public function test_delete_query()
    {
        $name = 'test_delete_query';
        $entity = entity($name);
        if ( $entity->exists() ) $entity->uninit();
        $entity
            ->init()
            ->create()
            ->save();

        $entity
            ->create()
            ->save();

        test( $entity->count() == 2, 'OK', 'ERROR');
        $entity->deleteQuery("id>=2");
        test( $entity->count() == 1, 'OK', 'ERROR');
        $entity->uninit();
    }
}