<?php

namespace model\user;

use core\model\node\Node;

class User extends Node {


    public function __construct()
    {
        parent::__construct();
        $this->setTableName('user');
    }


    /**
     *
     */
    public function install() {
        $this->init();

        $this->addColumn('username', 'int');
        $this->addUniqueKey('username');


        $this->addColumn('password', 'varchar');


        $this->addColumn('email', 'varchar');
        $this->addUniqueKey('email');

        $this->addColumn('first_name', 'varchar');
        $this->addColumn('last_name', 'varchar');
        $this->addColumn('middle_name', 'varchar');

        $this->addColumn('mobile', 'varchar');
        $this->addColumn('landline', 'varchar');
        $this->addColumn('address', 'varchar');


        user()
            ->create()
            ->set('username', 'anonymous')
            ->set('email', 'anonymous@no-email.com')
            ->set('first_name', 'Anonymous')
            ->set('last_name', 'Anonymous')
            ->save();

        user()
            ->create()
            ->set('username', 'admin')
            ->set('password', password_encrypt('1111'))
            ->set('first_name', 'Admin')
            ->set('last_name', 'Admin')
            ->set('email', 'admin@no-email.com')
            ->save();
    }
}