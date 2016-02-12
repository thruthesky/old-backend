# backend
@writer JaeHo Song

    - This backend PHP framework works with frontend Javascript framework named 'frontend'.
    - Code in 'model/xxx' folder to develop an web/app.

- 웹앱을 개발할 때, 백엔드로 사용하는 프레임워크

    - 프론트엔드 용이 아니므로 화려한 디자인을 기대 할 수 없다.
    
- 간단한 설치를 위해서 sqlite3 를 적용했다.


# 외부 라이브러리

- composer 를 사용해서 외부 라이브러리를 사용한다.

    - guzzle 이 설치되어져 있다.



# Route

route 는 model.class.method 로 구성된 소스 코드 실행 경로로서 특정 모델의 클래스에 있는 메소드를 직접 실행 할 수 있게 해 준다.

예를 들어, "http://work.org/?route=abc.def.ghi" 와 같이 접속을 하면,

abc 모델 폴더의 def.php 파일에 있는 ghi 메소드를 실행하는 것이다.

그리고 ghi 메소드에서는 적절한 결과를 JSON 또는 HTML 등으로 리턴하면 된다.

특히, Ajax 콜을 할 때, 직접적으로 route 를 호출하면 된다.

# 사용법 - Usage

    php index.php


## CLI 에서 모델/클래스/메소드를 실행하는 방법 - How to run in CLI

    php index.php route=entity.EntityTest.run
    
위 실행 방법은 HTTP 액세스를 할 때에도 비슷하게 적용이 되는 것이다.

route 에는 모델.클래스.메소드를 기록하면 된다.


몇 몇 예제를 들자면 아래와 같다.

    php index.php "route=entity.Controller.exist&node=user"

위 예제는 user_node_entity 테이블이 존재하는지 확인하는 것이다.



## HTTP 로 모델/클래스/메소드를 실행하는 방법

    http://work.org/backend/index.php?route=user.Controller.register&username=jaeho&password=4321&email=jaeho@gmail.com

위 코드는 아래의 CLI 로 접속을 할 수 있다.

    php index.php "route=user.Controller.register&username=jaeho&password=4321&email=jaeho@gma il.com"

즉, 라우팅과 값 입력 방법이 비슷하며 서로 호환이 된다.



## UnitTest 방법 - How to UnitTest


    - It has its own unit test function. ( 자체적인 유닛테스트 기능을 가지고 있다. )


To do the Unit Test, run like below.

( 아래와 같이 실행을 하면된다. )


    php index.php test ( This runs all the tests )
    php index.php route=entity.EntityTest.run ( This runs run() method of EntityTest.php in entity model. EntityTest.php 의 run() 메쏘드를 호출한다. )
    php index.php route=user.UserTest.path ( UserTest.php 의 path() 메쏘드를 호출한다. run() 을 직접 호출 하지 않아도 된다. )
    php index.php "route=entity.Controller.exist&node=user"


    - the first line of cli command runs all the test files.
    - Test file ends with "Test.php"
    
    
첫번째 라인은 모든 ***Test.php 를 찾아서 실행한다.

두번째 라인은 해당 모델의 ***Test 클래스를 찾아서 그 안의 run() 을 실행한다.

세번째 라인은 입력 변수를 쌍따옴료포 둘러 싼 것이다. 엠퍼센트( & )와 같은 특수문자가 들어가는 경우 쌍따오표로 둘러싸야한다. 예를 들면 '&' 는 쉘에서 두개의 명령을 순서대로 실행하는 역활을 하는 것이다.

- UnitTest 코드 작성은

model 아래의 각 ***Test.php 에서 하면 된다.

DatabaseTest.php 와 EntityTest.php, NodeTest.php, MetaTest.php 등을 참고한다.

## 데이터베이스 생성과 테입을의 생성

기본적으로 수동으로 생성하는 것이 원칙이다.

다만,

id, created, updated 와 meta 태그에 해당하는 code,value 는 기본적인 코드를 활용한다.


## 샘플 서버

http://jungeunsu.com/backend/


## 코어

core 폴더에는 필수적인 코드와 범용적으로 쓰이는 코드들이 들어가 있다.

임시적으로 필요한 기능이 있는 경우에는 root/model 폴더에 기록을 한다.


## 모듈 설치하는 방법

모듈을 설치하는 것은 CLI 에서 route 로 접속하는 것이 원칙이다.

아래의 예제는 user 모델의 Controller 에 있는 install 메소드를 통해서 설치를 하도록 한다.

    php index.php "route=user.Controller.install"

### 모듈 설치 확인

아래의 예제는 user/Controller.php 의 installed 메소드를 통해서 설치가 올바로 되었는지 확인을 한다.

    php index.php "route=user.Controller.installed"



### 모듈 삭제

아래와 같이 컨트롤러로 모듈을 삭제한다.

    php index.php "route=user.Controller.uninstall"




## 사용자 모델 User model

테이블은 아래와 같이 설치 및 삭제를 한다.

    php index.php "route=user.Controller.install" ( 설치)
    php index.php "route=user.Controller.installed" ( 설치 확인 )
    php index.php "route=user.Controller.uninstall" ( 삭제 )





## 테스트 스크립트 작성 방법

참고: 테스트 스크립트는 각 model 폴더의 Test.php 로 작성한다. 따라서 각 model 의 Test.php 파일을 참고한다.


각 model 폴더 아래에 Test.php 클래스를 작성해 놓고 아래와 같이 route=model.class.method 로 호출하면 된다.

    php index.php "route=user.Test.call&a=b&c=d&number=0917"
    php index.php route=user.Test.createTempUsers
    
    

입력 값은 첫번째 변수에 모두 전달된다.

메쏘드 예제
    
    public function call( $in ) {
        print_r($in);
    }



# Company 모듈

## 설치

    $ php index.php "route=company.Controller.install"

위와 같이 설치를 하면 된다.


# template 사용하기

model/model-name/template 에는 HTML 파일을 저장한다.

( core 에는 template 폴더가 없다. )


backend 에는 특별히 view 가 없는데,

frontend 에서 사용 할 수 있는 view 와 관련된 HTML 를 보관하는 template 폴더가 있다.

이 것은 model 마다 있을 수도 있고 없을 수도 있는데, frontend 의 web/app 과 연관이 있는 것이다.

즉, frontend 에 view 를 정적으로 집어넣지 않고 backend 에서 실시가능로 항상 로드하므로서 frontend 의 디자인 및 기능을 실시간으로 변경 할 수 있는 것이다.

이 폴더에서는 frontend 에서 보여주고 사용 할 html 이나, css, javascript 등을 넣을 수 있다.

특히 ajax 나 ajax 를 통한 캐시, _.template() 등의 기능을 집어 넣으므로서 적절하게 활용을 할 수 있다.




# 파일 업로드 - Data 모듈

- 데이터를 업로드 할 때, finish 훅을 하기 위해서
 
- 글 작성을 할 때, 임시 랜덤 gid 를 생성해서,
 
- 글 작성 FORM 에 gid 를 기억하고,

- 파일을 업로드 할 때, 그 gid 를 Data 모듈의 gid 에 저장한다.

- 그리고 글 작성 FORM 이 전송될 때, finish 를 1 값으로 둔다.

- 이렇게 하므로서 data.gid 는 일반적으로 각 테이블 레코드의 gid 필드의 값을 가지며
 
    -- 하나의 레코드에 파일이 여러개 등록 될 때, gid 에 따라서 어떤 레코드의 파일들인지 쉽게 확인 할 수 있다.


