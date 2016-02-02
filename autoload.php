<?php
spl_autoload_register( function( $class ) {

    $path = null;
    if ( strpos($class, "core\\model") === 0 ) {
        $path = str_replace('\\', '/', $class) . '.php';
        include $path;
    }
    else if ( strpos($class, "model\\") === 0 ) {
        $path = str_replace('\\', '/', $class) . '.php';
        include $path;
    }


    /*
    if ( strpos( $class, 'of\\') !== false ) {
        $class_name = str_replace('of\\', '', $class);

        if ( strpos( $class_name, '\\') !== false ) {
            $arr = explode('\\', $class_name);
            $model_name = strtolower($arr[0]);
            $class_name = $arr[1];
        }
        else {
            $model_name = strtolower($class_name);
        }
        include __DIR__ . "/model/$model_name/$class_name.php";
    }
    */

} );
