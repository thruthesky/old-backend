<?php

if ( ! hi('id') ) {
    echo "회원 정보 번호가 입력되지 않았습니다.";
    return;
}

$company = company()->load( hi('id') );

if ( empty($company) ) {
    echo "업소 정보가 존재하지 않습니다.";
    return;
}


?>



<div class="rows view">
    <div class="row title"><?php echo $company->title?></div>
    <?php if ( $company->company_name ) { ?>
        <div class="row">
            <span class="caption">회사명</span>
            <span class="text"><?php echo $company->company_name?></span>
        </div>
    <?php } ?>
    <?php if ( $company->ceo_name ) { ?>
        <div class="row">
            <span class="caption">대표자</span>
            <span class="text"><?php echo $company->ceo_name?></span>
        </div>
    <?php } ?>
    <?php if ( $company->email ) { ?>
        <div class="row">
            <span class="caption">이메일</span>
            <span class="text"><?php echo $company->email?></span>
        </div>
    <?php } ?>
    <?php if ( $company->kakao ) { ?>
        <div class="row">
            <span class="caption">카톡아이디</span>
            <span class="text"><?php echo $company->kakao?></span>
        </div>
    <?php } ?>
    <?php if ( $company->mobile ) { ?>
        <div class="row">
            <span class="caption">휴대폰</span>
            <span class="text"><?php echo $company->mobile?></span>
        </div>
    <?php } ?>
    <?php if ( $company->landline ) { ?>
        <div class="row">
            <span class="caption">전화</span>
            <span class="text"><?php echo $company->landline?></span>
        </div>
    <?php } ?>
    <?php if ( $company->delivery ) { ?>
        <div class="row">
            <span class="caption">배달</span>
            <span class="text"><?php echo $company->delivery?></span>
        </div>
    <?php } ?>
    <?php if ( $company->city ) { ?>
        <div class="row">
            <span class="caption">지역</span>
            <span class="text"><?php echo $company->city?></span>
        </div>
    <?php } ?>
    <?php if ( $company->ceo_name ) { ?>
        <div class="row">
            <span class="caption">주소</span>
            <span class="text"><?php echo $company->address?></span>
        </div>
    <?php } ?>
    <?php if ( $company->homepage ) {
        $homepage = $company->homepage;
        if ( strpos( $homepage, "http://www.philgo.com/?module=post&action=view&idx=" ) !== false ) $homepage = str_replace("http://www.philgo.com/?module=post&action=view&idx=", "www.philgo.com?", $homepage);

        ?>
        <div class="row">
            <span class="caption">홈페이지</span>
            <a href="<?php echo $company->homepage?>" target="_blank">
                <span class="text"><?php echo $homepage?></span>
            </a>
        </div>
    <?php } ?>
    <?php if ( $company->content ) { ?>
        <div class="row content"><?php echo $company->content?></div>
    <?php } ?>


</div>


<div class="button edit" route="company.Controller.editForm&id=<?php echo $company->id?>">수정</div>
<div class="button delete" route="company.Controller.delete&id=<?php echo $company->id?>">삭제</div>
