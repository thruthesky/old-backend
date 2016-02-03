<?php
namespace core\model\node;

class NodeTest extends Node {
    public function run() {
        $this->test_init();
    }

    private function test_init()
    {
        $table = 'abc';
        $node = node($table);
        $name = $node->getTableName();
        test( ! $node->exists(), '', "$name Table exists" );

        // if ( $this->exists() ) $this->uninit();

        //
        $node->init();
        test( $node->exists() );

        $node->uninit();
        test( ! $node->exists(), '', "$name Table exists" );
    }



}