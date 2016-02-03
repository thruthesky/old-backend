<?php

use core\model\node\Node;

function node($name) {
    $node = new Node();
    $node->setTableName($name);
    return $node;
}
