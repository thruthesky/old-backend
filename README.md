# backend
@writer JaeHo Song

    - This backend PHP framework works with frontend Javascript framework named 'frontend'.
    - Code in 'model/xxx' folder to develop an web/app.

- 웹앱을 개발할 때, 백엔드로 사용하는 프레임워크

    - 프론트엔드 용이 아니므로 화려한 디자인을 기대 할 수 없다.
    
- 간단한 설치를 위해서 sqlite3 를 적용했다.


# TODO

    - user model 을 core 로 이동한다.


    user 모델에서

    웹으로 로그인/로그아웃하는 것. setLogin(), setLogout() 에서 웹 접속이며 쿠키를 남김.

 

# 외부 라이브러리

- composer 를 사용해서 외부 라이브러리를 사용한다.

    - guzzle 이 설치되어져 있다.



# Basic Concepts of Backend

## Entity

Entity is a set of data information. It is actually a database table.

Entity item is a record of table.

When you create a table using Entity, '_entity' is attached at the end of table name.

For instance, if 'abc' is the entity name, then 'abc_entity' is the table name.


### getter

Entity 에 getter magic 메쏘드를 추가했다.

이는 meta 클래스를 활용 할 때, id 나 code, value 를 통해서 쉽게 값을 얻을 수 있따.



## Node

Node is a class that extends Entity.

When you create a node, '_node_entity' is attached at the end of table name.

For instance, if 'abc' is the node name, then 'abc_node_entity' is the table name.



## Meta
 
Meta is a class that extends Entity. It is more about key/value pair data set.

When you create a node, '_meta_entity' is attached at the end of table name.

For instance, if 'abc' is the node name, then 'abc_meta_entity' is the table name.






# Route

route 는 model.class.method 로 구성된 소스 코드 실행 경로로서 특정 모델의 클래스에 있는 메소드를 직접 실행 할 수 있게 해 준다.

예를 들어, "http://work.org/?route=abc.def.ghi" 와 같이 접속을 하면,

abc 모델 폴더의 def.php 파일에 있는 ghi 메소드를 실행하는 것이다.

그리고 ghi 메소드에서는 적절한 결과를 JSON 또는 HTML 등으로 리턴하면 된다.

특히, Ajax 콜을 할 때, 직접적으로 route 를 호출하면 된다.

## Route 를 직접 호출할 때 장점

모델/클래스::메쏘드를 직접 호출 하므로 여러 함수가 연속적으로 호출 되어야하는 경우 또는 여러 함수가 복잡하게 엮여서 호출되는 경우,

해당 메쏘드만 호출하여 구간 테스트를 보다 쉽게 할 수 있다.






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



# User model - 사용자 모델

model/user/README.md 파일 참고


# 테스트 스크립트 작성 방법

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

model/model-name/template 에는 PHP 파일을 저장한다.

- core 에는 template 폴더가 없다.
- 확장자를 php 로 한 이유는 각종 편집기에서 PHP 파일로 인식하게 하기 위해서이다.
- template() 은 함수이다. 따라서 tmeplate PHP 파일이 함수 안에서 실행되므로 적절한 글로벌 변수 처리가 필요하다.



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


예제) HTML 과 자바스크립트

아래의 예제는 범용적으로 사용 할 수 있다.

form 의 hidden 변수 중, gid, code, unique, finish 는 적절하게 변경을 하면 된다.

upload 버튼을 클릭하게 되면, on_change_file_upload() 가 실행되고 업로드를 진행한다.

업로드에 에러가 없으면 display-uploaded-file 에 업로드 된 파일의 IMG 태그 내용을 추가하는데, 삭제 버튼을 추가한다.




    <?php
    if ( $company ) $gid = $company->gid;
    else $gid = getGid();
    ?>
    <div class="display-uploaded-file">
        <?php
            $data = data()->loadByGidOne($company->gid);
            if ( $data ) {
                $id = $data->id;
                $url = $data->url;
                echo "<img width='100%' fid='$id' src='$url'><span class='button delete-category-icon'>삭제</span>";
            }
        ?>
    </div>
    <form class='philgo-banner-form' action="<?php echo url_script()?>?route=data.Controller.fileUpload" method="post" enctype="multipart/form-data">
        <input type="hidden" name="gid" value="<?php echo $gid?>">
        <input type="hidden" name="code" value="primary-photo">
        <input type="hidden" name="unique" value="1">
        <input type="hidden" name="finish" value="1">
        <input type="file" name="userfile" onchange="on_change_file_upload(this);">
    </form>
    <script>
        function on_change_file_upload(filebox) {
            var $filebox = $(filebox);
            if ( $filebox.val() == '' ) return;
            var $form = $filebox.parents("form");
            $form.ajaxSubmit({
                error : function (xhr) {
                    alert("ERROR on ajaxSubmit() ...");
                },
                complete: function (xhr) {
                    console.log("File upload completed through jquery.form.js");
                    var re;
                    try {
                        re = JSON.parse(xhr.responseText);
                    }
                    catch (e) {
                        console.log(xhr.responseText);
                        return alert("ERROR: JSON.parse() error : Failed on file upload...");
                    }
                    if ( re['code'] ) {
                        return app.alert(re['message']);
                    }
                    else {
                        $('.display-uploaded-file').html( get_markup_icon(re['id'], re['url']) );
                    }
                    console.log(re);
                }
            });
            $filebox.val('');
        }
        function get_markup_icon( id, url ) {
            return "<img width='100%' fid='"+id+"' src='"+url+"'><span class='button delete-uploaded-file'>삭제</span>";
        }
    </script>



삭제 버튼은 아래와 같은데 완전히 범용적이다. 아무것도 수정하지 않고 그대로 사용가능하다.


    
    function on_delete_category_icon(e) {
        var $this = $(this);
        //$this.parents('.row').find('.content').show();
        var $img = $this.parent().find('img');
        var fid = $img.attr('fid');
        ajax_load_route( 'data.Controller.fileDelete&id=' + fid, function(res) {
            var re = JSON.parse(res);
            if ( re['code'] ) return alert('파일 삭제에 실패하였습니다. ' + re['message']);
            $this.parent().empty();
        });
    }


# 모듈 별 테스트, 모듈 별 기능 동작 상태 확인

- 각 모듈 Controller 의 test 메소드를 실행하므로서 설치 여부나 entity 정보를 얻을 수 있다.

    -- 형식) php index.php route=모델.Controller.test
    -- 예제) php index.php route=user.Controller.test



# Transaction

대용량 데이터 또는 민감한 코드의 경우 transaction 을 사용한다.

실제로 사용을 해 보니, 속도도 빠르고 또 table lock 에러가 발생하지 않는다.

    $db->beginTransaction();
    $db->addColumn($table_name, 'name', 'varchar');
    $db->insert($table_name, ['name'=>'AAA']);
    $db->insert($table_name, ['name'=>'JJJ']);
    $db->insert($table_name, ['name'=>'ZZZ']);
    $db->endTransaction();

# 사용자

관리자는 항상 username 이 admin 이다.

