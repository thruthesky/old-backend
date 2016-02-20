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
    <?php if ( $company->homepage ) { ?>
        <div class="row">
            <span class="caption">홈페이지</span>
            <span class="text"><?php echo $company->homepage?></span>
        </div>
    <?php } ?>
    <?php if ( $company->content ) { ?>
        <div class="row content"><?php echo $company->content?></div>
    <?php } ?>
</div>
