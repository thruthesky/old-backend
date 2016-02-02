<?php
use core\model\entity\Entity;


/**
 * @param null $name - table name of the entity
 * @return Entity
 */
function entity($name=null) {
    if ( $name ) {
        $entity = new Entity();
        $entity->setTableName($name);
        return $entity;
    }
    else return new Entity();
}
