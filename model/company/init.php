<?php

function category($code=null) {
    if ( $code ) {
        $meta = meta('company_category');
        return $meta->load($code);
    }
    else return meta('company_category');
}
function company() {
    return new \model\company\Company();
}