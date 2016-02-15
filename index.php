<?php

require 'config.php';   // 각종 경로 및 초기 값 설정
require 'core/script/function.php';   // 각종 경로 및 초기 값 설정
require 'core/script/init.php';   // 초기화 코드
require 'autoload.php';
require 'vendor/autoload.php'; // composer package autoload

/**
 * 모듈 init.php 파일을 여기서 로드해야, 전역으로 적용가능하다. 예) 전역 변수 설정 등.
 */
foreach ( sys()->getPathCoreModel() as $path ) {
    $init = "$path/init.php";
    if ( file_exists($init) ) include $init;
}
foreach ( sys()->getPathUserModel() as $path ) {
    $init = "$path/init.php";
    if ( file_exists($init) ) include $init;
}


/**
 * 전체 UNIT TEST 코드 실행
 */
if ( isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'test' ) {
    sys()->runTest();
}



if ( hi('route') ) {
    header('Access-Control-Allow-Origin: *');
    route()->run( hi('route'), hi() );
}


