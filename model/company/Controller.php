<?php

namespace model\company;


use GuzzleHttp\Client;

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

        sys()->log("company\\Controller::edit()");

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

        //
        parent::init();
        $this->addColumn('category', 'int');
        $this->addColumn('company_name', 'varchar');
        $this->addColumn('title', 'varchar');
        $this->addColumn('ceo_name', 'varchar');
        $this->addColumn('email', 'varchar');
        $this->addColumn('mobile', 'varchar');
        $this->addColumn('landline', 'varchar');
        $this->addColumn('kakao', 'varchar');

        $this->addColumn('region', 'varchar');
        $this->addColumn('city', 'varchar');
        $this->addColumn('address', 'varchar');

        $this->addColumn('homepage', 'varchar');
        $this->addColumn('etc', 'varchar');
        $this->addColumn('source', 'varchar');

        $this->addColumn('gid', 'int');


        //
        $this->addIndex('category');
        $this->addIndex('company_name');
        $this->addIndex('email');
        $this->addIndex('source');


        //
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

    public function deleteAllCategory() {
        $category = meta('company_category');
        $cats = $category->loadQuery();
        if ( $cats ) {
            foreach ( $cats as $cat ) {
                $id = $cat->getRecord('id');
                $file = data()->load( " gid='company-category' AND code='$id' ");
                if ( $file ) {
                    $file->delete();
                }
                $cat->delete();
            }
        }
    }

    public function deleteAllCompany() {
        $this->deleteAll();
    }

    public function inputCategoryData() {

        $this->setCategory("가구", "사무용 가구, 책상, 의자 등 포함", "furniture.png");
        $this->setCategory("교회", "", "church.png");

        $this->setCategory("절", "포교당 포함", "");
        $this->setCategory("공공기관", "시청, 대사관 등", "gov.png");

        $this->setCategory("어학원", "", "train.png");

        $this->setCategory("홈스테이", "", "");
        $this->setCategory("하숙", "", "");
        $this->setCategory("학교", "각종 교육 시설. 어학원을 제외한 일반 학원 포함.", "school.png");

        $this->setCategory("김치/반찬", "마트, 식품점에서 따로 뺌", "kimchi.png");

        $this->setCategory("마트/식품", "슈퍼마켓, 식품점.", "mart.png");


        $this->setCategory("망고", "", "mango.png");

        $this->setCategory("식당", "레스토랑", "restaurant.png");


        $this->setCategory("통관/배달", "통관, 배달, 운송, 해운/항공 등", "delivery.png");

        $this->setCategory("약국", "", "drugstore.png");

        $this->setCategory("병원", "", "");

        $this->setCategory("한의원", "", "");
        $this->setCategory("안경점", "", "eyeglass.png");

        $this->setCategory("미용/뷰티", "매니큐어, 페디큐어 등 포함", "beauty.png");

        $this->setCategory("건강식품", "", "food.png");

        $this->setCategory("휴대폰", "스마트폰 포함", "smartphones.png");

        $this->setCategory("컴퓨터", "", "computer.png");

        $this->setCategory("인터넷", "", "internet.png");

        $this->setCategory("TV", "", "internet.png");

        $this->setCategory("가전/전자", "", "electronics.png");

        $this->setCategory("여행사", "", "travel.png");

        $this->setCategory("부동산", "", "realestate.png");

        $this->setCategory("이주/이민", "", "immigrant.png");

        $this->setCategory("항공사", "", "airport.png");

        $this->setCategory("골프/부킹", "", "golf.png");

        $this->setCategory("호텔/숙박", "", "hotel.png");

        $this->setCategory("리조트", "", "resort.png");

        $this->setCategory("간판/인쇄", "프린팅,출력 등 포함", "office.png");

        $this->setCategory("인테리어", "내부 설비", "interior.png");

        $this->setCategory("환전", "은행, 금융대신 환전으로 명시", "bank.png");

        $this->setCategory("방역/청소", "바퀴벌레 등 벌레 청소", "pest.png");

        $this->setCategory("자동차", "자동차 판매. 중고차 포함.", "car.png");
        $this->setCategory("렌트카", "", "rentcar.png");
        $this->setCategory("자동차정비", "", "rentcar.png");

        $this->setCategory("정수기", "", "water.png");
        $this->setCategory("건설", "", "");
        $this->setCategory("신문/잡지", "", "");
        $this->setCategory("은행", "", "");
        $this->setCategory("술집/주점", "", "");
        $this->setCategory("기타", "", "");

    }


    public function inputCompanyDataFromPhilgo() {
        $this->deleteQuery("source='philgo'");
        $client = new Client();
        $response = $client->get("http://philgo.com/?module=etc&action=company_data_json_submit");
        $code = $response->getStatusCode();
        if ( $code == 200 ) {
            $body = $response->getBody();
            $stringBody = (string) $body;
            $jsonString = base64_decode($stringBody);
            $arr = json_decode($jsonString, true);
            $i = 0;
            foreach ( $arr as $cats ) {
                foreach ( $cats as $c ) {
                    //print_r($c);
                    //if ( $i ++ > 10 ) break;
                    $this->create()
                        ->set('company_name', $c['company_name'])
                        ->set('etc', $c['category'])
                        ->set('title', $c['subject'])
                        ->set('region', $c['region'])
                        ->set('kakao', $c['varchar_17'])
                        ->set('homepage', "http://www.philgo.com/$c[url]")
                        ->set('source', 'philgo');
                    if ( isset($c['landline']) ) $this->set('landline', $c['landline']);
                    if ( isset($c['mobile']) ) $this->set('mobile', $c['mobile']);
                    $this->save();
                    echo (++$i) . ' ';
                }
            }
            $this->updateCategoryForPhilgoData();
        }
        else {
            die("Failed downloading philgo.com company data");
        }

    }
    public function updateCategoryForPhilgoData() {

        $this->setPhilgoCategory('TV/인터넷', '인터넷');
        $this->setPhilgoCategory('가구', '가구');
        $this->setPhilgoCategory('가전/전자', '가전/전자');
        $this->setPhilgoCategory('간판/인쇄', '간판/인쇄');
        $this->setPhilgoCategory('건강식품', '건강식품');
        $this->setPhilgoCategory('건설', '건설');
        $this->setPhilgoCategory('골프/부킹', '골프/부킹');
        $this->setPhilgoCategory('공공기관', '공공기관');
        $this->setPhilgoCategory('교육기관', '학교');
        $this->setPhilgoCategory('교육시설', '학교');
        $this->setPhilgoCategory('기타', '기타');
        $this->setPhilgoCategory('김치/반찬', '김치/반찬');
        $this->setPhilgoCategory('라조트', '리조트');
        $this->setPhilgoCategory('락식클리닉', '병원');
        $this->setPhilgoCategory('레스토랑', '식당');
        $this->setPhilgoCategory('렌트카', '렌트카');
        $this->setPhilgoCategory('렌트카/자동차/정비', '자동차');
        $this->setPhilgoCategory('리조트', '리조트');
        $this->setPhilgoCategory('마트/식당', '마트/식품');
        $this->setPhilgoCategory('마트/식품', '마트/식품');
        $this->setPhilgoCategory('망고', '망고');
        $this->setPhilgoCategory('방역/청소', '방역/청소');
        $this->setPhilgoCategory('병/의원', '병원');
        $this->setPhilgoCategory('부동산', '부동산');
        $this->setPhilgoCategory('뷰티/스킨', '미용/뷰티');
        $this->setPhilgoCategory('안경점', '안경점');
        $this->setPhilgoCategory('약국', '약국');
        $this->setPhilgoCategory('어학원', '어학원');
        $this->setPhilgoCategory('어학원', '어학원');
        $this->setPhilgoCategory('언론사', '신문/잡지');
        $this->setPhilgoCategory('여행사', '여행사');
        $this->setPhilgoCategory('은행/금융', '환전');
        $this->setPhilgoCategory('이주/이민', '이주/이민');
        $this->setPhilgoCategory('인테리어', '인테리어');
        $this->setPhilgoCategory('자동차관련', '자동차');
        $this->setPhilgoCategory('정수기렌탈', '정수기');
        $this->setPhilgoCategory('종교시설', '교회');
        $this->setPhilgoCategory('주점', '술집/주점');
        $this->setPhilgoCategory('컴퓨터', '컴퓨터');
        $this->setPhilgoCategory('컴퓨터 ', '컴퓨터');
        $this->setPhilgoCategory('통관', '통관/배달');
        $this->setPhilgoCategory('통관운송이사', '통관/배달');
        $this->setPhilgoCategory('통신', '인터넷');
        $this->setPhilgoCategory('필리핀 호텔/숙박', '호텔/숙박');
        $this->setPhilgoCategory('호텔/숙박', '호텔/숙박');
        $this->setPhilgoCategory('항공사', '항공사');
        $this->setPhilgoCategory('휴대폰', '휴대폰');
        $this->setPhilgoCategory('', '기타');

        $no = $this->count("source='philgo' AND category=0");
        echo "No of un-categorized: $no\n";

        $db = database();
        $table = $this->getTableName();
        $rows = $db->query("SELECT etc, COUNT(etc) FROM $table WHERE category=0 AND source='philgo' GROUP BY etc");
        print_r($rows);

    }



    private function setPhilgoCategory($old_category, $new_category)
    {


        $c = category($new_category);
        if ( empty($c) ) die("No category : $new_category");
        $category_id = $c->getID();


        $companies = $this->loadQuery("category == 0 AND source='philgo' AND etc='$old_category'");

        if ( $companies ) {

            foreach( $companies as $com ) {
                $com->put('category', $category_id);
            }
        }

    }
}

