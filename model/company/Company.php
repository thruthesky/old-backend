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



    public function delete() {
        // 관련된 첨부 파일을 모두 삭제해야 한다.
        sys()->log("Company::delete");
        parent::delete();
    }

}