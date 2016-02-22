<?php
if ( ! login() ) {
    echo "<h1>로그인을 하십시오.</h1>";
    return;
}
$username = login()->username;
$companies = company()->loadQuery("username='$username'");
$count = count($companies);
?>
<style>
    .companies {

    }
    .companies .company {
        margin:.4em 0;
        background-color:#efefef;
    }
</style>
<h1>
    내가 등록한 업소 목록
</h1>
<h2>
    업소를 클릭 한 다음 맨 아래의 메뉴에서 수정 버튼을 클릭하시면 됩니다.
</h2>

<?php
    if ( $count == 0 ) return;
    display_companies($companies);
?>