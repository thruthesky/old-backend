<?php
namespace model\company;

use core\model\node\Node;

class Company extends Node {

    const CATEGORY_TABLE = 'company_category';

    public function __construct()
    {
        parent::__construct();
        $this->setTableName('company');
    }



}