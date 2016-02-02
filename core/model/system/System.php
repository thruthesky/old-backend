<?php

namespace core\model\system;


class System {

    static $count_log = 0;

    static $list_core_model = array();
    static $list_user_model = array();

    public function __construct()
    {
        self::$list_core_model = glob(DIR_ROOT. "/core/model/*", GLOB_ONLYDIR);
        self::$list_user_model = glob(DIR_ROOT. "/model/*", GLOB_ONLYDIR);
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


}
