<?php

namespace core\model\data;




class Controller extends Data
{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     *
     */
    public function install() {
        $this->init();
        $this->addColumn('gid', 'varchar', 64);
        $this->addColumn('code', 'varchar', 64);
        $this->addColumn('finish', 'char');
        $this->addColumn('name', 'varchar', 255);
        $this->addColumn('name_saved', 'varchar', 255);
        $this->addColumn('mime', 'varchar', 64);
        $this->addColumn('size', 'int');
        $this->addIndex('gid');
        $this->addIndex('code');
        $this->addIndex('gid,code');
        return SUCCESS();
    }

    public function uninstall()
    {
        $this->uninit();
        return SUCCESS();
    }
}
