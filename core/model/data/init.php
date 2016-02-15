<?php

use core\model\data\Data;

function data( $id = null ) {
    if ( $id ) {
        $data = new Data();
        $new_data = $data->load( $id );
        return $new_data;
    }
    else return new Data();
}