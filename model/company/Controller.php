<?php

namespace model\company;


class Controller extends Company
{

    public function __construct()
    {
        parent::__construct();
    }


    public function admin() {

        ob_start();
        include template('company','admin');
        $html = ob_get_clean();
        return SUCCESS( array('html'=>$html) );
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

    public function editCategory() {
        $name = hi('category_name');
        if ( empty($name) ) return ERROR( -451, 'Input category name');
        category()->set( $name, hi('category_comment') );
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
        $data = category()->loadAllArray();
        return SUCCESS($data);
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
        $this->uninit();
        meta('company_category')->uninit();
        return SUCCESS();
    }


    public function countInformation() {
        return SUCCESS( array('count' => $this->count() ) );
    }


    public function searchInformation($in) {
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
}

