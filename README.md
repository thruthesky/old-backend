# backend

- 웹앱을 개발할 때, 백엔드로 사용하는 프레임워크

    - 프론트엔드 용이 아니므로 화려한 디자인을 기대 할 수 없다.
    
- 간단한 설치를 위해서 sqlite3 를 적용했다.

## 사용법

    php index.php

### CLI 에서 모델/클래스/메소드를 실행하는 방법

    php index.php route=entity.EntityTest.run
    
위 실행 방법은 HTTP 액세스를 할 때에도 비슷하게 적용이 되는 것이다.

route 에는 모델.클래스.메소드를 기록하면 된다.


몇 몇 예제를 들자면 아래와 같다.

    php index.php "route=entity.Controller.exist&node=user"

위 예제는 user_node_entity 테이블이 존재하는지 확인하는 것이다.



### HTTP 로 모델/클래스/메소드를 실행하는 방법

    http://work.org/backend/index.php?route=user.Controller.register&username=jaeho&password=4321&email=jaeho@gmail.com

위 코드는 아래의 CLI 로 접속을 할 수 있다.

    php index.php "route=user.Controller.register&username=jaeho&password=4321&email=jaeho@gma il.com"

즉, 라우팅과 값 입력 방법이 비슷하며 서로 호환이 된다.



### UnitTest 방법

- 자체적인 유닛테스트 기능을 가지고 있다.

아래와 같이 실행을 하면된다.

    php index.php test
    php index.php route=entity.EntityTest.run
    php index.php "route=entity.Controller.exist&node=user"

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


