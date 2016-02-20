<h2>회원 로그인</h2>
<form class="login" action="<?php echo url_site()?>">
    <input type="hidden" name="route" value="user.Controller.login">
    <input type="text" name="username" placeholder="사용자 아이디" required>
    <input type="password" name="password" placeholder="사용자 비밀번호" required>
    <input type="submit" value="로그인">
</form>
