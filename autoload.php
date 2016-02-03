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



} );
