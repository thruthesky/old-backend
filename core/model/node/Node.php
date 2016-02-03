<?php
namespace core\model\node;
use core\model\entity\Entity;

/**
 * Class Node
 *
 *
 *
 *
 *
 */
class Node extends Entity
{

    public function __construct()
    {
        parent::__construct();
    }


    public function setTableName($name) {
        $name = $name . '_node';
        parent::setTableName($name);
    }

}
