<?php
use model\user\User;

/**
 *
 *
 *
 * It checks the username and signature of HTTP input.
 *
 * @note it does not check any thing if there is no username and signature.
 *
 * @Attention it stops the run time ONLY if the user's signature does not match with his username.
 *
 *      - if user is not found, then it just continues the running.
 */
if ( hi('username') && hi('signature') ) {
    $user = user( hi('username') );
    if ( $user ) {
        if ( $user->signature() == hi('signature') ) {
            $user->setLogin();
        }
        else response( ERROR(-40112, "Signature does not match"));
    }
    //else response(ERROR(-40111, "User not found."));
}
else {
    // response(ERROR(-40113, "username and signature not provided."));
}


/**
 *
 *
 * Returns user object.
 *
 *
 *
 * @param null
 *
 *      - If $username is a numeric, then it assumes as user id.
 *      - If it is not numeric, then it assumes as username.
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
 *
 *
 * Returns TRUE if user exist. Or returns FALSE if user is not exists.
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
 *
 * Returns TRUE if the log-in user is admin.
 * @return bool
 */
function isAdmin() {
    if ( ! login() || login()->username != 'admin' ) return FALSE;
    else return TRUE;
}