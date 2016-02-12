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
 * TEST 코드 실행
 */
if ( isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'test' ) {
    sys()->runTest();
}


/**
 * CLI 에서 입력되는 값을 해당 route 로 전달한다.
 * 이 때, $_http_input global 변수에 입력 값을 파싱해서 집어 넣으므로서 HTTP 로 route 에 접속하는 것과 동일한 효과를 같도록 한다.
 */
if ( isset($_SERVER['argv'][1]) ) {
    parse_str( $_SERVER['argv'][1], $_http_input );
    if ( isset($in['route']) ) {
        route()->run($in['route'], $_http_input);
    }
}



if ( hi('route') ) {
    header('Access-Control-Allow-Origin: *');
    route()->run( hi('route'), hi() );
}


