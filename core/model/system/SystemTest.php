<?php
namespace core\model\system;

class SystemTest {

    public function run() {
        $this->test_path();
    }

    public function test_path()
    {

        $p1 = DIR_ROOT . '/core/model/system';
        $p2 = sys()->dirModel('system');

        test( $p1 == $p2 );

    }



}
