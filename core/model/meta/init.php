<?php


use core\model\meta\Meta;

function meta($name) {
    $meta = new Meta();
    $meta->setTableName($name);
    return $meta;
}
