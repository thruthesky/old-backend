<?php

namespace model\user;

/**
 * Class Controller
 *
 * @package model\user
 */
class Controller extends User
{



    public function __construct()
    {
        parent::__construct();
    }


    public function test() {
        if ( $this->exists() ) echo "OK: user entity table installed\n";
        else die("ERROR: user entity table is not installed.");

        $count = $this->count();
        echo "$count users are exists.\n";
    }

    /**
     *
     */
    public function install() {
        $this->init();

        $this->addColumn('username', 'int');
        $this->addUniqueKey('username');


        $this->addColumn('password', 'varchar');


        $this->addColumn('email', 'varchar');
        $this->addUniqueKey('email');

        $this->addColumn('first_name', 'varchar');
        $this->addColumn('last_name', 'varchar');
        $this->addColumn('middle_name', 'varchar');

        $this->addColumn('mobile', 'varchar');
        $this->addColumn('landline', 'varchar');
        $this->addColumn('address', 'varchar');


        user()
            ->create()
            ->set('username', 'anonymous')
            ->set('email', 'anonymous@no-email.com')
            ->set('first_name', 'Anonymous')
            ->set('last_name', 'Anonymous')
            ->save();

        user()
            ->create()
            ->set('username', 'admin')
            ->set('password', password_encrypt('1111'))
            ->set('first_name', 'Admin')
            ->set('last_name', 'Admin')
            ->set('email', 'admin@no-email.com')
            ->save();


        return SUCCESS();
    }


    public function installed() {
        $re = $this->exists();
        if ( $re ) return SUCCESS();
        else {
            $table = $this->getTableName();
            return ERROR(-441, "NOT Installed. $table table does not exists.");
        }
    }






    public function uninstall() {
        $this->uninit();
        return SUCCESS();
    }


    /**
     * @param $in
     * @return array
     *
     * @code
     *      $ php index.php "route=user.Controller.register&username=abc&password=1234&email=abc@def.com"
     * @endcode
     *
     */
    public function register($in) {

        if ( ! isset($in['username'] ) ) {
            sys()->log("Username is empty.");
            return ERROR(-111, "Username is empty.");
        }
        if ( ! isset($in['password'] ) ) return ERROR(-1121, "Password is empty.");
        if ( ! isset($in['email'] ) ) return ERROR(-113, "Email is empty.");

        if ( user_exists($in['username']) ) return ERROR(-121, "User: $in[username] exists.");
        if ( user_email_exists($in['email']) ) return ERROR(-121, "User email: $in[email] exists.");


        $sets = array();
        $sets['username'] = $in['username'];
        $sets['password'] = password_encrypt($in['password']);
        $sets['email'] = $in['email'];
        $sets['first_name'] = hi('first_name', '');
        $sets['middle_name'] = hi('middle_name', '');
        $sets['last_name'] = hi('last_name');
        $sets['mobile'] = hi('mobile', '');
        $sets['landline'] = hi('landline', '');
        $sets['address'] = hi('address');


        $re = user()
            ->create()
            ->sets( $sets )
            ->save();

        if ( $re ) return SUCCESS();
        else return ERROR(-4, 'Failed on saving user information.');
    }

    public function edit($in) {

        if ( ! login() ) return ERROR(-400201, "Login first");
        $sets = array();
        if ( isset($in['password']) && ! empty($in['password']) ) {
            $sets['password'] = password_encrypt($in['password']);
        }
        $sets['email'] = $in['email'];

        $sets['first_name'] = hi('first_name', '');
        $sets['middle_name'] = hi('middle_name', '');
        $sets['last_name'] = hi('last_name');
        $sets['mobile'] = hi('mobile', '');
        $sets['landline'] = hi('landline', '');
        $sets['address'] = hi('address');

        $sets['updated'] = time();

        $login = login()->puts($sets);

        if ( empty($login) ) return ERROR(-400204, "Update failed.");

        return SUCCESS();
    }

    /**
     *
     * Log-in user.
     *
     * @note This returns signature IF the username & password is correct.
     *
     *
     *
     * @param $in
     * @return array
     *
     *
     * @code
     *      php index.php "route=user.Controller.login&username=abc&password=1234"
     * @endcode
     *
     */
    public function login($in) {

        if ( !isset($in['username']) || empty($in['username'] ) ) return ERROR(-410, "Input username");
        if ( !isset($in['password']) || empty($in['password'] ) ) return ERROR(-411, "Input password");


        $user = user($in['username']);
        if ( ! $user ) return ERROR(-412, "User not exists by that username - $in[username]");

        if ( $user->get('password') != password_encrypt($in['password'] ) ) return ERROR(-413, "Password does not match.");


        $this->setLogin($user);
        $signature = user()->loginUser()->signature();

        return SUCCESS( array("signature"=>$signature) );

        /**
        if ( $user->get('id') == user()->currentUser()->get('id') ) return SUCCESS();
        else return ERROR(-415, "User login failed.");
         */
    }





    /**
     *
     *
     *
     */
    public function resign() {




    }



    /**
     *
     * Returns the user information in ARRAY.
     *
     * @note This only wraps entity::search() and returns in JSON array.
     *
     *
     *
     *
     * @param $in
     * @return array
     *
     * @usage php index.php "route=user.Controller.collect&limit=5"
     *
     * @note It is same call as
     *      - php index.php "route=user.Controller.search&limit=5&return=array"
     *
     */
    public function collect($in) {
        $o = $in;
        $o['return'] = 'array';
        return SUCCESS( $this->search( $o ) );
    }

    public function registerForm() {
        echo template('user', 'register_form');
    }
    public function loginForm() {
        echo template('user', 'login_form');
    }

    public function editForm() {
        if ( login() ) {
            echo template('user', 'edit_form');
        }
        else {
            return ERROR(-40119, "Login first");
        }
    }

    /**
     *
     * Checks 'who am I' or gets the login username.
     *
     * @param $in
     * @return array
     *      - The input HTTP variable & login username in "data['login']"
     *
     *
     */
    public function who($in) {
        $re = $in;
        $user = user()->loginUser();
        if ( $user ) $re['username'] = $user->username;
        return SUCCESS( $re );
    }
}

