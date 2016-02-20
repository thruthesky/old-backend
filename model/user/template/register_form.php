<h2>회원 등록</h2>
<form class="user-register" action="<?php echo url_site()?>">
    <input type="hidden" name="route" value="user.Controller.register">
    <div class="username"><input type="text" name="username" placeholder="사용자 아이디" required></div>
    <div class="password"><input type="password" name="password" placeholder="사용자 비밀번호" required></div>
    <div class="first-name"><input type="text" name="first_name" placeholder="First Name" required></div>
    <div class="middle-name"><input type="text" name="middle_name" placeholder="Middle Name"></div>
    <div class="last-name"><input type="text" name="last_name" placeholder="Last Name" required></div>
    <div class="email"><input type="email" name="email" placeholder="메일주소" required></div>
    <div class="mobile"><input type="number" name="mobile" placeholder="휴대폰 번호. 숫자만 입력." required></div>
    <div class="mobile"><input type="number" name="landline" placeholder="유선 전화 번호. 숫자만 입력."></div>
    <div class="address"><input type="text" name="address" placeholder="주소"></div>
    <div class="submit"><input type="submit" value="회원 가입하기"></div>
</form>
