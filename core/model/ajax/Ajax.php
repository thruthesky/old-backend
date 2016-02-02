<?php

namespace of;
/**
 * Class Ajax
 * @package of
 *
 * @code do 다음에 점을 찍으면 모델 이름과 클래스 이름이 된다.
 *
    <?php echo ajax_endpoint()?>&do=entity.ItemList
 *
 * @endcode
 */
class Ajax {
    public function run() {
        header('Access-Control-Allow-Origin: *');



        date_default_timezone_set('Asia/Seoul');
        user()->login();


        if ( $model = http_input('model') ) {
            list ( $model_name, $class_name, $method_name ) = explode('.', $model);
            $uc_first_class_name = ucfirst($class_name);
            $namespace = "of\\$model_name\\$uc_first_class_name";
            $obj = new $namespace();
            return $obj->$method_name();
        }

        switch( $doing = http_input('do') ) {
            default:
                if ( strpos($doing, '.') ) {
                    $doing = str_replace('.', '\\', $doing);
                }
                else {
                    $doing = ucfirst($doing);
                }
                $name = "of\\$doing";
                $obj = new $name();
                $obj->runAjax();
                json_error(-40444, "Nothing to do");
                return null;
        }
    }



}
