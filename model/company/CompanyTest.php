<?php
namespace model\company;

class CompanyTest extends Company {
    public function run() {

        $this->test_category();
    }



    public function test_category()
    {
        $category = meta('company_category');
        $category->set("마트", "코리안 마트, 슈퍼마켓");
        $category->set("김치", "마트에서 김치는 따로 뺌");
    }

}