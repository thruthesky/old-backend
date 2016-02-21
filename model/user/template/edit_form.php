<style>
    form.user-edit .password .input {
        display:none;
    }
</style>
<script>
    $(function(){
        $('form.user-edit .password .text').click(function(){
            $(this).next().show();
            $(this).remove();
        });
    });
</script>
<h2>회원 정보 수정</h2>
<form class="user-edit" action="<?php echo url_site()?>">
    <input type="hidden" name="route" value="user.Controller.edit">
    <div class="username"><?php echo login()->username?></div>
    <div class="password">
        <div class="text">비밀번호는 변경하시려면 클릭하십시오.</div>
        <div class="input"><input type="password" name="password" placeholder="사용자 비밀번호"></div>
    </div>
    <div class="first-name"><input type="text" name="first_name" placeholder="First Name" required value="<?php echo login()->first_name?>"></div>
    <div class="middle-name"><input type="text" name="middle_name" placeholder="Middle Name" value="<?php echo login()->middle_name?>"></div>
    <div class="last-name"><input type="text" name="last_name" placeholder="Last Name" required value="<?php echo login()->last_name?>"></div>

    <div class="email"><input type="email" name="email" placeholder="메일주소" required value="<?php echo login()->email?>"></div>
    <div class="mobile"><input type="number" name="mobile" placeholder="휴대폰 번호. 숫자만 입력." required value="<?php echo login()->mobile?>"></div>
    <div class="mobile"><input type="number" name="landline" placeholder="유선 전화 번호. 숫자만 입력." value="<?php echo login()->landline?>"></div>

    <div class="address"><input type="text" name="address" placeholder="주소" value="<?php echo login()->address?>"></div>

    <div class="submit"><input type="submit" value="회원 정보 수정"></div>
</form>
