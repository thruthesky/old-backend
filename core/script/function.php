<?php

/**
 * @short returns a UNIQUE ID
 *
 */
function unique_id()
{
    return md5(uniqid(rand(), true) . time());
}


function get_backtrace()
{
    ob_start();
    debug_print_backtrace();
    $str = ob_get_clean();
    return $str;
}

function path_run($n = 1) {

    $d = debug_backtrace();

    $func = $d[$n]['function'];
    $cls = $d[$n]['class'];
    $file = $d[$n]['file'];
    $line = $d[$n]['line'];

    return "$file at line $line - $cls::$func";
}


function test( $code, $good=null, $bad=null ) {
    static $_count_test = 0;
    $_count_test ++;
    $tree = get_backtrace();
    if ( $code ) {
        echo "$_count_test ";
    }
    else {
        echo "\nERROR: ($_count_test)\n$bad\n$tree\n";
        exit;
    }
}


/**
 * Returns page no.
 * @note page no. begins with 1.
 *  if the input is not a number or less than 1, then it returns 1.
 *
 * @attention Since the return 'page no' is 1 on first page, you must do ('page_no' -1 ) for retrieving data form table.
 *      page_no 가 1 부터 시작하므로, 테이블에서 데이터를 처음 부터 추출하기 위해서는 offset 이 '0' 이어야 하므로 'page_no' - 1 을 해야 한다.
 *
 * @param $no
 * @return int|string
 */
function page_no($no) {
    if ( ! is_numeric($no) ) return 1;
    else if ( $no < 1 ) return 1;
    else return $no;
}

function response( $data ) {
    echo json_encode( $data );
    exit;
}
function response_error( $code, $message ) {
    return ['code'=>$code, 'message'=>$message];
}
function response_success( $data ) {
    if ( $data ) return ['code'=>0, 'data'=>$data];
    else return ['code'=>0];
}

function error( $code, $message ) {
    return response_error($code, $message );
}

function success( $data = array() ) {
    return response_success( $data );
}

function password_encrypt($str) {
    return md5($str);
}



$_http_input = array();
/**
 * @param null $name
 * @param null $default
 * @return array
 * @code
 *  $in = http_input();
 * @endcode
 */
function http_input($name = null, $default = null) {
    global $_http_input;
    if ( empty($_http_input) )$_http_input = array_merge($_GET, $_POST);
    if ( $name ) {
        if ( isset( $_http_input[$name] ) ) return $_http_input[$name];
        else return $default;
    }
    return $_http_input;
}

/**
 * 내부적으로 캐시를 하므로 반복적으로 사용을 해도 빠르게 처리를 한다.
 *
 * @note alias of http_input
 *
 * @param null $name
 * @param null $default
 * @return mixed
 * @code
        if ( hi('route') ) {
            route()->run( hi('route'), hi() );
        }
 * @endcode
 */
function hi($name = null, $default = null) {
    return http_input($name, $default);
}


function template($model, $name) {
    return DIR_ROOT . "/model/$model/template/$name.html";
}