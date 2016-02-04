<?php
namespace model\company;

use core\model\node\Node;

class Company extends Node {

    public function __construct()
    {
        parent::__construct();
        $this->setTableName('company');
    }




}