<?php
print_r( hi() );
if ( ! hi('id') ) {
    echo "회원 정보 번호가 입력되지 않았습니다.";
    return;
}
?>