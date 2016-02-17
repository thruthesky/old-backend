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

    public function test() {
        echo "Installation check:\n";
        if ( $this->exists() ) echo "OK: Company data table exists.\n";
        else echo "ERROR: Company data table does not exists.\n";
        if ( meta('company_category')->exists() ) echo "OK: Company category table exists.\n";
        else echo "ERROR: Company category table does not exists.\n";
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


    public function editForm() {
        echo template('company', 'company-edit-form');
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


    public function categoryView($in) {
        echo template('company', 'categoryView');
    }
}

