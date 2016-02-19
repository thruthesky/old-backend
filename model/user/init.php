<?php
use model\user\User;

/**
 *
 * http input 으로 username 과 signature 가 들어오면 자동으로 사용자 정보를 확인한다.
 * 이 두 값을 입력하지 않으면 사용자 정보를 확인하지 않는다.
 *
 */
if ( hi('username') && hi('signature') ) {
    $user = user( hi('username') );
    if ( $user ) {
        if ( $user->signature() == hi('signature') ) {
            $user->setLogin();
        }
        else response( ERROR(-40112, "Signature does not match"));
    }
    else response(ERROR(-40111, "User not found."));
}
else {
    // response(ERROR(-40113, "username and signature not provided."));
}


/**
 *
 * 회원 정보 User object 를 리턴한다.
 *
 *
 *
 *
 * @param null $username 숫자이면 회원번호, 문자이면 username 으로 인식하여 회원 정보를 리턴한다.
 *
 *
 * @return User
 */
function user($username=null) {

    if ( $username ) {
        $e = new User();
        if ( is_numeric($username) ) return $e->load($username);
        else return $e->load("username='$username'");
    }
    else return new User();
}

function login() {
    return user()->loginUser();
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

/**
 * 관리자이면 참을 리턴한다.
 * @return bool
 */
function isAdmin() {
    if ( ! login() || login()->username != 'admin' ) return FALSE;
    else return TRUE;
}