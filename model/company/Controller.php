<?php

namespace model\company;


class Controller extends Company
{

    public function __construct()
    {
        parent::__construct();
    }


    public function admin() {
        $html = <<<EOH
<h1>Admin page</h1>
<h2>Create Company Information</h2>
<form class="company-edit">
<input type='hidden' name='route' value='company.Controller.edit'>
<input type='text' name='company_name' value='' placeholder='Company Name'>
<input type='text' name='mobile' value='' placeholder='Mobile Number'>
<input type='text' name='landline' value='' placeholder='Landline Number'>
<input type='text' name='ceo_name' value='' placeholder='CEO Name'>
<input type='text' name='address' value='' placeholder='Company Address'>
<input type='email' name='email' value='' placeholder='Company Email'>
<input type='text' name='kakao' value='' placeholder='KakaoTalk ID'>
<input type='submit'>

</form>
EOH;


        return SUCCESS( array('html'=>$html) );
    }

    public function edit() {

        $entity = $this
            ->create()
            ->set('company_name', hi('company_name'))
            ->set('ceo_name', hi('ceo_name'))
            ->set('mobile', hi('mobile'))
            ->set('landline', hi('landline'))
            ->set('email', hi('email'))
            ->set('address', hi('address'))
            ->set('kakao', hi('kakao'))
            ->save();

        if ( $entity ) return SUCCESS();
        else return ERROR(-431, "Failed on creating/updating company information");
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

        return SUCCESS();
    }



    public function uninstall() {
        $this->uninit();
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

