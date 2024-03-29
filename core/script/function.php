<?php
/**
 * @file function.php
 */

/**
 * @return bool
 */
function is_cli() {
    return php_sapi_name() == 'cli';
}
/**
 * @short returns a UNIQUE ID
 *
 */
function unique_id()
{
    return md5(uniqid(rand(), true) . time());
}
function getGid()
{
    return unique_id();
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


/**
 *
 * It yields error message if the $code is not TRUE or it echoes a number.
 *
 * @param $code
 * @param null $good
 * @param null $bad
 */
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

/**
 *
 * It echoes dat in JSON string and exits the script.
 *
 * @note Route must use this function to send data to frontend.
 * @note Use this function to send data to client ( frontend ).
 *
 *
 *
 * @param $data
 *
 * @Attention This function ends the runtime.
 *
 * @code How to send data to frontend.
 *      response(ERROR(-40111, "User not found."));
 *      response(SUCCESS('html'=>'<h1>Okay</h1>'));
 *      response("<p>This is HTML</p>");
 * @endcode
 */
function response( $data ) {
    echo json_encode( $data );
    exit;
}

/**
 * @param $code
 * @param $message
 * @return array
 */
function response_error( $code, $message ) {
    $re = ['code'=>$code, 'message'=>$message];
    return $re;
}


/**
 *
 * This adds
 *
 *  - 'code'=0
 *  - 'username' = "login username ONLY IF the user is logged in"
 *  - 'route'='route path'
 *
 *
 * @param array $data - array of data to echo in JSON string.
 * @return array
 */
function response_success( $data = array() ) {
    if ( login() ) {
        $data['username'] = login()->username;
    }
    $data['route'] = hi('route');
    $data['code'] = 0;
    return $data;
}

/**
 * Alias of response_error
 * @param $code
 * @param $message
 * @return array
 */
function error( $code, $message ) {
    return response_error($code, $message );
}

/**
 * Alias of response_success
 * @param array $data
 * @return array
 */
function success( $data = array() ) {
    return response_success( $data );
}

function password_encrypt($str) {
    return md5($str);
}



/**
 * @param null $name
 * @param null $default
 * @return array
 * @code
 *  $in = http_input();
 * @endcode
 *
 * @note 내부적으로 캐시를 하므로 반복적으로 사용해도 속도 저하가 없다.
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
    ob_start();
    include DIR_ROOT . "/model/$model/template/$name.php";
    return ob_get_clean();
}


/**
 * 도메인을 소문자로 리턴한다.
 *
 * 2차, 3차, 4차 도메인을 인정한다.
리턴 값 예:
abc.123.456.com
www.abc.com
abc.com
 *
 */
function domain_name()
{
    if ( isset( $_SERVER['HTTP_HOST'] ) ) {
        $domain = $_SERVER['HTTP_HOST'];
        $domain = strtolower($domain);
        return $domain;
    }
    else return NULL;
}


/**
 *
 * http(s)://.....com 까지 리턴한다.
 *
 * @return string
 */
function url_domain( )
{
    return '//' . domain_name();
}



/**
 * @param array $s
 * @param bool|false $use_forwarded_host
 * @return string
 * @code
 *
    $absolute_url = url_full( $_SERVER );
    echo $absolute_url;
 *
 * @endcode
 *
 *
 * @note 리턴 예제: http://work.org/backend/?route=company.Controller.categoryList&_=1455179511439
 *
 */
function url_full( $s = array(), $use_forwarded_host = false )
{
    if ( empty($s) ) $s = $_SERVER;
    if ( isset($s['REQUEST_URI']) ) $uri = $s['REQUEST_URI'];
    else $uri = null;
    return url_domain() . $uri;
}


/**
 *
 * @note     Query String 은 제외하고 PHP 스크립트의 URL 만 리턴한다.
 * @return string
 *
 * @note 리턴 예제: http://work.org/backend/index.php
 */
function url_script() {
    return url_domain( ) . $_SERVER["PHP_SELF"];
}

/**
 * backend 가 설치된 경로를 리턴한다.
 * @usage FORM 문장에서 backend 의 주소를 'action' 속성에 기록하고자 할 때, 이 함수를 사용하면 된다.
 * @note 끝에 index.php 를 없애고 설치 폴더의 '/' 까지만 기록한다.
 * @return string
 */
function url_install_dir() {
    $url_script = url_script();
    return str_replace("/index.php", '/', $url_script);
}

/**
 * alias of url_install_dir()
 * @return string
 */
function url_site() { return url_install_dir(); }



function is_post() {
    if ( isset($_SERVER['REQUEST_METHOD']) ) {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    else return FALSE;
}
function is_get() {
    if ( isset($_SERVER['REQUEST_METHOD']) ) {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    else return FALSE;
}