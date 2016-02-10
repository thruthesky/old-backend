<?php

function category() {
    return meta('company_category');
}
function company() {
    return new \model\company\Company();
}