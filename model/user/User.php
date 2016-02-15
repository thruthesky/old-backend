<?php

namespace model\user;

use core\model\node\Node;

class User extends Node {


    static $userLogin = null;

    public function __construct()
    {
        parent::__construct();
        $this->setTableName('user');
    }


    /**
     *
     * 회원 로그인을 지정한다.
     *
     * @note 이 메소드를 통해서 실제 로그인을 한다.
     *
     * @param $user
     *      - empty 이면 현재 사용자를 로그인한다.
     *      - 문자열이면 username,
     *      - 객체이면 회원 정보 객체로 인식을 한다.
     *      - 숫자로는 입력하지 않는다.
     *
     * @return bool
     */
    public function setLogin($user=null)
    {
        if ( empty($user) ) $user = $this;
        if ( is_string($user) ) $user = user($user);
        self::$userLogin = $user;
        return TRUE;
    }

    /**
     * 현재 로그인한 사용자 정보를 리턴한다.
     *
     * @return User
     */
    public function loginUser() {
        return self::$userLogin;
    }

    /**
     * 현재 객체의 정보를 담고 있는 사용자의 signature 를 리턴한다. 이것은 session 정보에도 사용 할 수 있다.
     *
     * @note 사용자 번호,비밀번호,가입일을 바탕으로 md5 로 해서 리턴한다.
     *
     */
    public function signature()
    {
        return md5( $this->get('id') . $this->get('password') . $this->get('created') );
    }

}