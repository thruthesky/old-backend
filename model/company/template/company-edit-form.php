<?php

global $company;
$company = FALSE;
if ( hi('id') ) {
    $company = company()->load(hi('id'));
    if ( $company->username != login()->username ) {
        echo '<h2 class="box warning">회원님의 업소 정보가 아니어서 수정 할 수 없습니다.</h2>';
        return;
    }
}



?>
<?php if ( $company ) { ?>
    <h2>업소 정보 수정</h2>
<?php } else { ?>
    <h2>업소 정보 등록</h2>
<?php } ?>

<?php
$user = user()->loginUser();
if ( empty($user) ) {
    echo "<h3>회원 로그인을 하십시오.</h3>";
    return;
}


$location = new model\philocation\PhiLocation();
//print_r($location->eng_to_ko);
//print_r($location->ko_to_eng);

$cats = category()->search([
    'order_by' => 'code ASC',
    'limit' => 9999,
    'return' => 'array'
]);

function val($k) {
    global $company;
    if ( ! $company ) return null;
    return $company->get( $k );
}

?>
<style>
    form.company-edit input {
        display:block;
    }
</style>
<form class="company-edit">
    <input type='hidden' name='route' value='company.Controller.edit'>
    <?php if ( $company ) { ?>
        <input type="hidden" name="id" value="<?php echo $company->id?>">
    <?php } ?>
    <select name="category">
        <?php
        foreach ( $cats as $cat ) {
            $selected = null;
            if ( $company ) {
                if ( $cat['id'] == $company->category ) {
                    $selected = ' selected=1';
                }
            }
            ?>
            <option value="<?php echo $cat['id']?>"<?php echo $selected?>><?php echo $cat['code']?></option>
        <?php } ?>
    </select>
    <input type='text' name='title' value="<?php echo val('title')?>" placeholder='제목을 입력하십시오.'>
    <input type='text' name='company_name' value="<?php echo val('company_name')?>" placeholder='회사 명'>
    <input type='text' name='ceo_name' value="<?php echo val('ceo_name')?>" placeholder='대표자 명'>
    <input type='text' name='mobile' value="<?php echo val('mobile')?>" placeholder='핸드폰 번호'>
    <input type='text' name='landline' value="<?php echo val('landline')?>" placeholder='유선 전화 번호'>
    <input type='text' name='kakao' value="<?php echo val('kakao')?>" placeholder='카카오톡'>
    <input type='email' name='email' value="<?php echo val('email')?>" placeholder='이메일'>


    <select name="city">
        <option value="etc">지역/도시 선택</option>
        <?php
        $cities = $location->ko_to_eng;
        foreach ( $cities as $ko => $en ) {
            if ( $ko == '기타지역' ) continue;
            $selected = null;
            if ( $en == val('city') ) $selected = ' selected=1';
            ?>
            <option value="<?php echo $en?>"<?php echo $selected?>><?php echo $ko?></option>
        <?php } ?>
        <option value="etc" <?php val('city') == 'etc' ? 'selected=1' : ''?>>기타지역</option>
        <option value="whole" <?php val('city') == 'whole' ? 'selected=1' : ''?>>필리핀 전지역</option>
    </select>



    <input type='text' name='address' value="<?php echo val('address')?>" placeholder='업소 상세 주소'>
    <label for="delivery">
        배달가능
        <input type="checkbox" id="delivery" name="delivery" value='1' <?php echo val('delivery') == 1 ? 'checked=1' : '' ?>>
    </label>
    <input type='text' name='homepage' value="<?php echo val('homepage')?>" placeholder='홈페이지 주소'>

    <textarea name='content' value='' placeholder='설명을 입력하십시오.'><?php echo val('content')?></textarea>



    <input type='submit'>
</form>

