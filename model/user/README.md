# 사용자 모델 User model

사용자 정보를 관리하는 모델이다.


# 설치, 삭제



테이블은 아래와 같이 설치 및 삭제를 한다.

    php index.php "route=user.Controller.install" ( 설치)
    php index.php "route=user.Controller.installed" ( 설치 확인 )
    php index.php "route=user.Controller.uninstall" ( 삭제 )



# 회원 가입

회원 가입 코드는 임의로 작성하면 된다.

user.Controller.registerForm 이 예제로서 좋으니 살펴보도록 한다.

- 폼 전송을 하면 form submit 이벤트를 받아서 ajax 로 처리한다.




# 회원 로그인

- 모바일과 웹/앱 등에서 동일한 코드를 작성하기 위해서

    - 로그인이 성공하면 아이디와 signature 를 localStroage 에 기억한다.
    - 매번 접속시 아이디와 signature 를 backend 서버로 전달한다.
    
단점은

- 좀 번거롭고,
- 이런 식으로 해 보지 않아서, 관리하기가 쿠키로 하는 것 보다 좀 새롭다.

장점은,

- 쿠키에 남기지 않아서 더 안전하다.

