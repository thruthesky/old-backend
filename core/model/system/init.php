<?php
use core\model\system\System;



sys()->log('core/model/system/init.php with URL : ' . url_full());
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sys()->log( http_input() );
}

