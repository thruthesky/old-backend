<?php
if ( ! login() ) {
    echo "<div class='box warning'>로그인을 하십시오.</div>";
    return;
}
$company = company()->load(hi('id'));
if ( ! $company ) {
    echo "<div class='box warning'>업소 정보가 존재하지 않습니다.</div>";
    return;
}

if ( empty($company->get('username')) || $company->username != login()->username ) {
    echo "<div class='box warning'>삭제 실패 : 회원님의 업소가 아닙니다.</div>";
    return;
}

$company->delete();

?>
<h1>업소록 삭제</h1>
업소록 정보가 삭제되었습니다.

