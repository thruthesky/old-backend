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

    - 로그인이 성공하면 username 과 signature 를 localStroage 에 기억한다.

예제)

    ls.set('username', username);
    ls.set('signature', re['data']['signature']);
        
- 그리고 매번 접속시 아이디와 signature 를 backend 서버로 전달한다.
    
단점은

- 좀 번거롭고,
- 이런 식으로 해 보지 않아서, 관리하기가 쿠키로 하는 것 보다 좀 새롭다.

장점은,

- 쿠키에 남기지 않아서 더 안전하다.

# who - 회원 로그인한 사용자 정보 알아보기

로그인을 하면, signature 정보를 얻을 수 있다. 그 값을 localStorage 에 저장해 놓았다가,

route 로 접속을 할 때에는 사용자 정보를 HTTP INPUT 으로 username, signature 에 넘겨야한다.

user.Controller.who 는 로그인 한 사용자의 username 을 리턴한다.

예제)

    C:\work\www\backend>php index.php "route=user.Controller.who&username=user2&signature=66f35ae085e2ab710bc2efea2388936b"
    {"code":0,"username":"user2","data":{"route":"user.Controller.who","username":"user2","signature":"66f35ae085e2ab710bc2efea2388936b"}}


user.Controller.who 는 로그인이 틀린 경우, 아래와 같이 정보를 리턴한다.

    {"code":-40111,"message":"User not found."}
    {"code":-40112,"message":"Signature does not match"}

"User not found" 는 username 이 틀린 경우,

"Signature does not match" 는 로그인 signature 가 틀린 경우이다.



# 사용자 가입, 로그인, 로그아웃 프로세스 정리

사용자가 먼저 홈페이지에 접속해서 회원 가입을 해야한다.

- HTML 에서 메뉴를 보여 줄 때, .user-in 과 .user-out 을 활용하면 된다.

    - frontend/js/function.js 의 updateUserLogin() 에 보면 설명이 나와있다.
    
예제는 아래와 같다.

예제) .user-in 와 .user-out 을 활용하여 사용자 로그인/로그아웃에 따른 메뉴 변경해서 보여주는 class 지정 방법 

    <td width="25%" class="user-out" route="user.Controller.loginForm"><i class="nav-link fa fa-key"></i>로그인</td>
    <td width="25%" class="user-in logout-button" style="display:none;"><i class="nav-link fa fa-key"></i>로그아웃</td>
    <td width="25%" class="user-out" route="user.Controller.registerForm"><i class="nav-link fa fa-user"></i>회원가입</td>
    <td width="25%" class="user-in" route="user.Controller.registerForm"><i class="nav-link fa fa-user"></i>회원정보</td>

위와 같이 하고 updateUserLogin() 을 호출하면 적절하게 표현된다.

- 회원 가입 버튼을 클릭하면 route 를 user.Controller.loginForm 연결해서 클릭하면 이 route 의 HTML 을 content 에 보여준다.

- 회원 가입을 하게 되면, 메인 페이지 또는 로그인 페이지로 이동을 하고

- 로그인을 성공하면

    - updateUserLogin() 을 호출하여 .user-in 속성의 정볼르 보여준다.

- 로그아웃을 하면,

    - localStorage 에서 username 을 지우고
    
    - updateUserLogin() 을 호출하다.



