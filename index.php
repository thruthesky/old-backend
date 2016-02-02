<?php
require 'config.php';   // 각종 경로 및 초기 값 설정
require 'autoload.php';


use core\model\system\System;
$sys = new System();


/**
 * 모듈 init.php 파일을 여기서 로드해야, 전역으로 적용가능하다. 예) 전역 변수 설정 등.
 */
foreach ( System::$list_core_model as $path ) {
    $init = "$path/init.php";
    if ( file_exists($init) ) include $init;
}
foreach ( System::$list_user_model as $path ) {
    $init = "$path/init.php";
    if ( file_exists($init) ) include $init;
}


/**
 * TEST 코드 실행
 */
if ( $_SERVER['argv'][1] == 'test' ) {
    sys()->runTest();
}
