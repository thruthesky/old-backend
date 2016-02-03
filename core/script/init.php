<?php
/**
 * @desc 초기화 스크립트를 작성한다.
 */
use core\model\system\System;

/**
 * sys() 함수는 System 클래스를 객체화하는 것으로 한번만 사용하면 된다.
 * 하지만 모듈 init 스크립트가 로드되기 전에 필요하므로 여기서 한번 사용한다.
 */
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