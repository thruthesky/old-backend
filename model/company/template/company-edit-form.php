<h2>업소 정보 등록</h2>
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

?>
<style>
    form.company-edit input {
        display:block;
    }
</style>
<form class="company-edit">
    <input type='hidden' name='route' value='company.Controller.edit'>
    <select name="category">
        <?php foreach ( $cats as $cat ) { ?>
        <option value="<?php echo $cat['id']?>"><?php echo $cat['code']?></option>
        <?php } ?>
    </select>
    <input type='text' name='title' value='' placeholder='제목을 입력하십시오.'>
    <input type='text' name='company_name' value='' placeholder='회사 명'>
    <input type='text' name='ceo_name' value='' placeholder='대표자 명'>
    <input type='text' name='mobile' value='' placeholder='핸드폰 번호'>
    <input type='text' name='landline' value='' placeholder='유선 전화 번호'>
    <input type='text' name='kakao' value='' placeholder='카카오톡'>
    <input type='email' name='email' value='' placeholder='이메일'>
    <!--
    <input type='text' name='region' value='' placeholder='지역 선택'>
    <input type='text' name='province' value='' placeholder='지역 선택'>
    <input type='text' name='city' value='' placeholder='도시 선택'>
    -->
    <select name="city">
        <option value="etc">지역/도시 선택</option>
        <?php
$cities = $location->ko_to_eng;
        foreach ( $cities as $ko => $en ) {
            if ( $ko == '기타지역' ) continue;
        ?>
        <option value="<?php echo $en?>"><?php echo $ko?></option>
        <?php } ?>
        <option value="etc">기타지역</option>
        <option value="whole">필리핀 전지역</option>
    </select>

    <input type='text' name='address' value='' placeholder='업소 상세 주소'>
    <label for="delivery">
        배달가능
        <input type="checkbox" id="delivery" name="delivery">
    </label>
    <input type='text' name='address' value='' placeholder='홈페이지 주소'>

    <textarea name='content' value='' placeholder='설명을 입력하십시오.'></textarea>



    <input type='submit'>
</form>
