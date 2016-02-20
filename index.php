<?php

require 'config.php';   //
require 'core/script/function.php';   //
require 'core/script/init.php';   // Initialization code for system.
require 'autoload.php';
require 'vendor/autoload.php'; // composer package autoload


/** ------------------ Loading Init Files ------------------- */

/**
 *
 * Loads init.php files for core module and user module.
 */
foreach ( sys()->getPathCoreModel() as $path ) {
    $init = "$path/init.php";
    if ( file_exists($init) ) include $init;
}
foreach ( sys()->getPathUserModel() as $path ) {
    $init = "$path/init.php";
    if ( file_exists($init) ) include $init;
}




/** ---------------------------- Run Test Codes If 'php index.php test' is run ---------------------------
 *
 *
 *
 * Run unit test.
 *
 *
 *
 */
if ( isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'test' ) {
    sys()->runTest();
}


/**
 *
 * @short Running 'Route'. This runs route. It can be accessed through HTTP or CLI.
 *
 *
 *
 *
 *
 */
if ( ! is_cli() ) header('Access-Control-Allow-Origin: *');
if ( hi('route') ) {
    route()->run( hi('route'), hi() );
}


