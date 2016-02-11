<?php
use core\model\system\System;



sys()->log('core/model/system/init.php with URL : ' . url_full());
if ( is_post() ) {
    sys()->log( http_input() );
}

