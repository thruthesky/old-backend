<?php
use core\model\database\Database;

$global_database = null;
/**
 * 이 함수를 사용하면 기존에 생성한 연결을 계속 사용한다.
 *
 * 만약, 새로운 연결을 하고 싶으면, new Database() 를 그대로 호출해야 한다.
 *
 * @return Database|null
 */
function database() {
    global $global_database;
    if ( $global_database === null ) $global_database = new Database();
    return $global_database;
}
