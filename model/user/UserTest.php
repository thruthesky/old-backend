<?php
namespace model\user;

class UserTest extends User {

    public function run() {
        $this->path();
    }

    public function path()
    {

        $p1 = DIR_ROOT . '/model/user';
        $p2 = sys()->dirModel('user');

        test( $p1 == $p2 );


    }



}