<?php
namespace core\model\meta;

use core\model\entity\Entity;

class MetaTest extends Meta {
    public function run() {

        $this->instance();
        $this->test_init();
        $this->test_get_set();
    }

    private function test_init()
    {
        $this->setTableName('abc');
        if ( $this->exists() ) $this->uninit();
        $this->init();
        test( $this->exists(), 'OK', 'ERROR');
        $this->uninit();
    }

    private function test_get_set()
    {
        $meta = meta('def');
        if ( $meta->exists() ) $meta->uninit();
        $meta->init();

        $meta->set('a', 'b');
        test( $meta->get('a') == 'b', 'OK', 'ERROR');
        $meta->set('a', 'c');
        test( $meta->get('a') == 'c', 'OK', 'ERROR');

        $meta->set('name', 'jaeho');
        test( $meta->get('name') == 'jaeho', 'OK', 'ERROR');

        $meta->set('first name of jung', 'eunsu');
        test( $meta->get('first name of jung') == 'eunsu', 'OK', 'ERROR');

        test( $meta->get('a') == 'c', 'OK', 'ERROR');

        $meta->uninit();
    }

    private function instance()
    {
        $meta = meta("test");

        test ( $meta instanceof Meta, "OK", "No. meta is not instance of Meta");
        test ( $meta instanceof Entity, "OK", "No. meta is not instance of Entity");
    }


}