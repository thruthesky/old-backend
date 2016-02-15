# Company Book

# TODO

- 업소 등록/ 업데이트/ 삭제/ 사진 등록/

    -- 홈페이지 등록. http://xxx.philgo.com/ 와 같이 홈페이지를 등록해 줌.

    -- 추가 정보. 회사 로고, 업소 사진 등록 필수.

    -- 회사 사업자 등록증 제출.

    -- 업소 소개 글( 소개, 오시는 길, 연락처 표시 )

    -- 게시판 메뉴 두개 제공.(메뉴 제목 변경 가능. 예: 방명록/후기, 칼럼)

    -- 나머지는 필기(필리핀 여행 이야기) 자유게시판/질문과답변 게시판 연동.

    -- 필기는 필고랑 rss 연동이 되도록 한다.




# 재 설치 시, 임시 데이터 ( 기본 데이터 ) 입력하는 방법


## 설치가 잘 되었는지 확인하는 방법

    php index.php route=company.Controller.test


## 기존 데이터 및 테이블 삭제

먼저 전체 카테고리 및 입력되어져 있는 데이터를 삭제하고, 테이블을 제거한다.


    php index.php route=company.Controller.deleteAllCategory ( 카테고리 정보를 삭제한다. )
    php index.php route=company.Controller.deleteAllCompany ( 회사 정보를 삭제한다 )
    php index.php route=company.Controller.uninstall

을 통해서 삭제를 하고 다시 설치를 한다.

## 설치

    php index.php route=company.Controller.install

## 기본 카테고리 데이터 입력 방법

    php index.php route=company.Controller.inputCategoryData

위와 같이 하면 model/company/tmp/category-icon/*.png 이미지 파일들을 활용하여 기본 카테고리 정보를 만든다. 


## 필고 Company Book 데이터를 포팅하는 방법

그냥 아래와 같이 하면, 새로운 데이터가 업데이트 된다.

    php index.php route=company.Controller.inputCompanyDataFromPhilgo




## 전체 카테고리 데이터 삭제 방법

    php index.php route=company.Controller.deleteAllCategory

위와 같이 하면 생성된 모든 카테고리 정보를 삭제한다.

## 전체 회사 정보 삭제하는 방법

    php index.php route=company.Controller.deleteAllCompany ( 회사 정보를 삭제한다 )





# 테이블 정보 - TABLES

## company_node_entity

회사 정보를 담는 테이블이다.

- source 필드는 데이터가 어디로 부터 오는지를 나타낸다.

    -- 이 값이 philgo 이면 필고로 부터 포팅이 된 데이터이다.

