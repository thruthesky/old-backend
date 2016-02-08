<?php

namespace core\model\system;


class System {

    static $count_log = 0;

    public static $list_core_model = array();
    public static $list_user_model = array();


    public static $list_core_model_name = array();
    public static $list_user_model_name = array();


    public function __construct()
    {
        global $global_sys;
        if ( $global_sys ) die("system is already instantiated.");
            /*
        else {
            echo "instantiating...\n";
            debug_print_backtrace();
        }
            */

        self::$list_core_model = glob(DIR_ROOT. "/core/model/*", GLOB_ONLYDIR);
        self::$list_user_model = glob(DIR_ROOT. "/model/*", GLOB_ONLYDIR);


        foreach ( $this->getPathCoreModel() as $path ) {
            $pi = pathinfo($path);
            self::$list_core_model_name[] = $pi['basename'];
        }
        foreach ( $this->getPathUserModel() as $path ) {
            $pi = pathinfo($path);
            self::$list_user_model_name[] = $pi['basename'];
        }


    }

    public function getPathCoreModel() {
        return self::$list_core_model;
    }
    public function getPathUserModel() {
        return self::$list_user_model;
    }

    public function getCoreModel() {
        return self::$list_core_model_name;
    }


    /**
     * @return array
     *
     * @code
     * print_r( $this->getUserModel() );
     * @endcode
     */
    public function getUserModel() {
        return self::$list_user_model_name;
    }

    public function isCoreModel($model) {
        return in_array($model, self::$list_core_model_name);
    }

    public function isUserModel($model)
    {
        return in_array($model, self::$list_user_model_name);
    }



    /**
     * @param $str
     * @return int|void
     */
    public function log ( $str )
    {
        $str = is_string($str) ? $str : print_r( $str, true );
        file_put_contents ( PATH_DEBUG, self::$count_log++ . ' : ' . $str . "\n", FILE_APPEND );
    }

    public function runTest()
    {
        echo "Backend Test:\n";
        echo "\n";
        //$na = array_merge(self::$list_core_model, self::$list_user_model);
        foreach ( self::$list_core_model as $model ) {
            $files = glob( $model . '/*est.php' );
            //print_r($files);


            foreach( $files as $file ) {

                list($pre, $post) = explode('core/model', $file);
                $path_php = 'core/model' . $post;
                $path_method = str_replace(".php", '', $path_php);
                $path_method = '\\' . str_replace('/', '\\', $path_method);
                $obj = new $path_method();
                if ( method_exists( $obj, 'run') ) $obj->run();
            }
        }
    }

    /**
     * @param $name
     * @return null|string
     */
    public function dirModel($name) {
        if ( $this->isUserModel($name) ) {
            return DIR_ROOT . "/model/$name";
        }
        else if ( $this->isCoreModel($name) ) {
            return DIR_ROOT . "/core/model/$name";
        }
        return null;
    }



}
