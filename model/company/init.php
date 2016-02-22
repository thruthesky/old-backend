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


function display_companies($companies) {
    echo '<div class="companies">';
    foreach ( $companies as $company ) {
        $homepage = $company->homepage;
        if ( strpos( $homepage, "http://www.philgo.com/?module=post&action=view&idx=" ) !== false ) $homepage = str_replace("http://www.philgo.com/?module=post&action=view&idx=", "www.philgo.com?", $homepage);
        if ( $company->kakao ) $kakao = "<div class='kakao'>카카오톡 : {$company->kakao}</div>";
        else $kakao = null;
        echo <<<EOH
    <div class="company" route="company.Controller.view&id={$company->id}">
        <div class="company-name">{$company->company_name}</div>
        <div class="numbers">{$company->mobile} {$company->landline}</div>
        $kakao
    </div>
EOH;
    }
    echo '</div>';
}