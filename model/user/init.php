<?php
use model\user\User;


/**
 *
 * return User
 */
function user() {
    global $global_user ;
    if ( $global_user  === null ) $global_user = new User();
    return $global_user ;
}


/**
 * 사용자가 존재하면 참을 아니면 거짓을 리턴한다.
 *
 * @param $id_username
 * @return bool|mixed
 */
function user_exists($id_username) {
    if ( is_numeric($id_username) ) {
        $user = user()->load($id_username);
        if ( $user ) return $user->is();
    }
    $user = user()->load("username='$id_username'");
    if ( $user ) return $user->is();
    else return FALSE;
}


function user_email_exists($email) {
    $user = user()->load("email='$email'");
    if ( $user ) return $user->is();
    else return FALSE;
}