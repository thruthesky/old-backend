<?php
use core\model\system\System;
$global_sys = null;
/**
 * @note This method creates only one instance of System class.
 * @return System
 */
function sys() {
    global $global_sys ;
    if ( $global_sys  === null ) $global_sys = new System();
    return $global_sys ;
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

sys()->log('core/model/system/init.php');



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

