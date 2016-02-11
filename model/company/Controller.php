<?php

namespace model\company;


class Controller extends Company
{

    public function __construct()
    {
        parent::__construct();
    }

    public function version() {
        echo '20160213';
    }

    public function header() {
        echo template('company', 'header');
    }

    public function footer() {
        echo template('company', 'footer');
    }

    public function frontPage() {
        echo template('company', 'frontPage');
    }

    public function admin() {
        return SUCCESS( array('html'=> template('company', 'admin')) );
    }

    public function edit() {

        $company_name = hi('company_name');
        $email = hi('email');

        if ( empty($company_name) ) return ERROR( -437, "Input company name" );
        if ( empty($email) ) return ERROR( -439, "Input email");

        $e = $this->load("company_name='$company_name'");
        if ( $e ) return ERROR( -438, "Company name exists.");

        $e = $this->load("email='$email'");
        if ( $e ) return ERROR( -440, "Company email exists.");


        $entity = $this
            ->create()
            ->set('company_name', $company_name)
            ->set('ceo_name', hi('ceo_name'))
            ->set('mobile', hi('mobile'))
            ->set('landline', hi('landline'))
            ->set('email', $email)
            ->set('address', hi('address'))
            ->set('kakao', hi('kakao'))
            ->save();

        if ( $entity ) return SUCCESS();
        else return ERROR(-431, "Failed on creating/updating company information");
    }


    public function createCategory() {
        $name = hi('code');
        if ( empty($name) ) return ERROR( -451, 'Input category name');
        category()->set( $name, hi('value') );
        return SUCCESS();
    }

    /**
     *
     * @return array
     *
     */
    public function editCategory() {
        $id = hi('id');
        if ( empty($id) ) return ERROR( -452, 'category id is not provied.');
        $meta = category()->load($id);
        $meta->sets(['code'=>hi('code'), 'value'=>hi('value')])->save();
        return SUCCESS();
    }

    public function categoryDelete() {
        $id = hi('id');
        if ( empty($id) ) return ERROR( -457, 'Input category id');
        $c = category()->load( $id );
        if ( empty($c) ) return ERROR( -458, "Category by that id - $id does not exists.");
        category()->load($id)->delete();
        return SUCCESS();
    }


    public function categoryList($in) {
        //$data = category()->loadAllArray();
        //return SUCCESS($data);
        echo template('company', 'categoryList');
    }



    public function installed() {
        $re = $this->exists();
        if ( $re ) return SUCCESS();
        else {
            $table = $this->getTableName();
            return ERROR(-441, "NOT Installed. $table table does not exists.");
        }
    }



    public function install() {
        parent::init();
        $this->addColumn('category', 'int');
        $this->addColumn('company_name', 'varchar');
        $this->addColumn('ceo_name', 'varchar');
        $this->addColumn('email', 'varchar');
        $this->addColumn('mobile', 'varchar');
        $this->addColumn('landline', 'varchar');
        $this->addColumn('kakao', 'varchar');
        $this->addColumn('address', 'varchar');

        $this->addIndex('category');
        $this->addUniqueKey('company_name');
        $this->addUniqueKey('email');

        meta('company_category')->init();

        return SUCCESS();
    }



    public function uninstall() {

        // company_node_entity DB Table 삭제.
        if ( $this->exists() ) {
            $this->uninit();
        }

        // company_category_meta_entity DB Table 삭제.
        $meta = meta('company_category');
        if ( $meta->exists() ) {
            $meta->uninit();
        }

        return SUCCESS();
    }


    /**
    public function countInformation() {
        return SUCCESS( array('count' => $this->count() ) );
    }
     * */


    public function getCategory($in) {
        $entities = $this->search();
        $list = [];
        if ( $entities ) {
            foreach ( $entities as $entity ) {
                $rec = $entity->getRecord();
                $list[] = $rec;
            }
        }
        return SUCCESS( [ 'category' => $in['category'], 'list' => $list ] );
    }



    public function setCategory($code, $value, $icon) {

        $in = [ 'gid' => 'company-category', 'finish' => 1, 'unique' => 1 ];

        $category = meta('company_category');
        $c = $category->set($code, $value);
        if ( $icon ) {
            $in['code'] = $c->getRecord('id');
            $in['from'] = "model/company/tmp/category-icon/$icon";
            data()->copyUpload($in);
        }
    }
    public function inputCategoryData() {

        $this->setCategory("가구", "사무용 가구, 책상, 의자 등 포함", "furniture.png");
        $this->setCategory("교회", "", "church.png");

        $this->setCategory("절", "포교당 포함", "");
        $this->setCategory("공공기관", "시청, 대사관 등", "gov.png");

        $this->setCategory("어학원", "", "train.png");

        $this->setCategory("홈스테이", "", "");
        $this->setCategory("하숙", "", "");
        $this->setCategory("학교", "각종 교육 시설. 학원 포함.", "school.png");

        $this->setCategory("김치/반찬", "마트, 식품점에서 따로 뺌", "kimchi.png");

        $this->setCategory("마트식품", "슈퍼마켓, 식품점.", "mart.png");


        $this->setCategory("망고", "", "mango.png");

        $this->setCategory("식당", "레스토랑", "restaurant.png");

        $this->setCategory("렌트카", "", "rentcar.png");

        $this->setCategory("배달운송", "통관, 배달, 운송, 해운/항공 등", "delivery.png");

        $this->setCategory("약국", "", "drugstore.png");

        $this->setCategory("병원", "", "");

        $this->setCategory("한의원", "", "");
        $this->setCategory("안경점", "", "eyeglass.png");

        $this->setCategory("미용뷰티", "매니큐어, 페디큐어 등 포함", "beauty.png");

        $this->setCategory("건강식품", "", "food.png");

        $this->setCategory("휴대폰", "스마트폰 포함", "smartphones.png");

        $this->setCategory("컴퓨터", "", "computer.png");

        $this->setCategory("인터넷", "", "internet.png");

        $this->setCategory("가전전자", "", "electronics.png");

        $this->setCategory("여행사", "", "travel.png");

        $this->setCategory("부동산", "", "realestate.png");

        $this->setCategory("이주이민", "", "immigrant.png");

        $this->setCategory("항공사", "", "airport.png");

        $this->setCategory("골프부킹", "", "golf.png");

        $this->setCategory("호텔숙박", "", "hotel.png");

        $this->setCategory("리조트", "", "resort.png");

        $this->setCategory("간판인쇄", "프린팅,출력 등 포함", "office.png");

        $this->setCategory("인테리어", "내부 설비", "interior.png");

        $this->setCategory("환전", "은행, 금융대신 환전으로 명시", "bank.png");

        $this->setCategory("방역청소", "바퀴벌레 등 벌레 청소", "pest.png");

        $this->setCategory("자동차", "자동차 판매. 중고차 포함.", "car.png");

        $this->setCategory("정수기", "", "water.png");
    }
}

