<?php


namespace core\model\route;

class Route
{
    /**
     * 해당 경로에 있는 클래스의 객체를 생성하고 메소드를 실행한다.
     * @param $path
     * @return array
     */
    public function run( $path, $in ) {
        list ( $model, $class, $method ) = explode('.', $path);


        if ( sys()->isCoreModel($model) ) {
            //response( array( 'code' => -2, 'message'=>'model does not exists.') );
            $path = "core\\model\\$model\\$class";
            $obj = new $path();
            if ( ! method_exists( $obj, $method ) ) response( array( 'code' => -200, 'message' => "method $method - does not exists. in $path") );
            $data = $obj->$method( $in );
            if ( $data ) response( $data );
        }
        else if ( sys()->isUserModel($model) ) {
            $path = "model\\$model\\$class";
            $obj = new $path();
            if ( ! method_exists( $obj, $method ) ) response( array( 'code' => -201, 'message' => "method $method - does not exists. in $path") );
            $data = $obj->$method( $in );
            if ( $data ) response( $data );
        }
        else {
            response( array( 'code' => -100, 'message' => 'model does not exists.') );
        }

    }
}
