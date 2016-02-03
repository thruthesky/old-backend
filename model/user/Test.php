<?php
namespace model\user;

class Test extends User {


    /**
     * 테스트용 임시 사용자 생성.
     *
     * @usage php index.php "route=user.Test.createTempUsers"
     *
     */
    public function createTempUsers()
    {
        $user = array();
        $sec = date('his');
        for( $i = 1; $i <= 100; $i ++ ) {
            $user['username'] = "Username($sec)$i";
            $user['password'] = password_encrypt("Username$i");
            $user['email'] = "email($sec)$i@gmail.com";
            $o = user()
                ->create()
                ->sets($user)
                ->save();
            if ( $o == FALSE ) die("ERROR on creating Users.");
            else echo $o->get('id') . ' ';
        }
    }


}