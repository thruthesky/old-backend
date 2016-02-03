<?php

namespace model\user;


class Controller extends User
{

    public function __construct()
    {
        parent::__construct();
    }


    public function installed() {
        $re = $this->exists();
        if ( $re ) return SUCCESS();
        else {
            $table = $this->getTableName();
            return ERROR(-441, "NOT Installed. $table table does not exists.");
        }
    }



    public function install() {
        parent::install();
        return SUCCESS();
    }


    public function uninstall() {
        $this->uninit();
        return SUCCESS();
    }


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

        $re = user()
            ->create()
            ->sets( $sets )
            ->save();

        if ( $re ) return SUCCESS();
        else return ERROR(-4, 'Failed on saving user information.');
    }


    /**
     * 사용자 정보 추출
     *
     * @param $in
     * @return array
     *
     * @usage php index.php "route=user.Controller.collect&limit=5"
     *
     */
    public function collect($in) {
        $o = [];
        $o['fields'] = isset($in['fields']) ? $in['fields'] : '*';
        $o['where'] = isset($in['where']) ? $in['where'] : null;
        $o['order'] = isset($in['order']) ? $in['order'] : 'id DESC';
        $o['limit'] = isset($in['limit']) ? $in['limit'] : 10;
        $o['page'] = isset($in['page']) ? $in['page'] : 1;
        $o['offset'] = isset($in['offset']) ? $in['offset'] : 0;

        $entities = $this->search( $o );

        $data = array();

        foreach ( $entities as $e ) {
            $data[] = $e->getRecord();
        }

        // returns error if user model is not installed.
        $count = count( $data ) ;
        if ( $count == 0 ) {
            if ( ! $this->exists() ) return ERROR( -131, "User model is not installed");
        }


        return SUCCESS($data);
    }

}

