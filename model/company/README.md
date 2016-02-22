# Company Book

# TODO

- 마무리하고 끝낼 것.

- 업소는 필고에서 오는 데이터를 주로 한다.

    - 필고 빠진 정보를 모두 가져 올 것.
    
        - 사진
        - 지도 위치
        - 기타 설명.

- 업소 검색

- (완료) 업소 등록/ 업데이트/ 삭제/ 사진 등록

# 종속 관계

- User model 이 활성화 되어야 한다.

- Data model 이 활성화 되어야 한다.



# 재 설치 시, 임시 데이터 ( 기본 데이터 ) 입력하는 방법


## 설치가 잘 되었는지 확인하는 방법

    php index.php route=company.Controller.test


## 기존 데이터 및 테이블 삭제

먼저 전체 카테고리 및 입력되어져 있는 데이터를 삭제하고, 테이블을 제거한다.


    php index.php route=company.Install.deleteAllCategory ( 카테고리 정보를 삭제한다. )
    php index.php route=company.Install.deleteAllCompany ( 회사 정보를 삭제한다 )
    php index.php route=company.Install.uninstall

을 통해서 삭제를 하고 다시 설치를 한다.

## 설치

    php index.php route=company.Install.install ( DB Table 생성 )
    php index.php route=company.Install.inputCategoryData ( 기본 카테고리 입력 )
    php index.php route=company.Install.inputCompanyDataFromPhilgo ( 필고 데이터 포팅 )
    



위와 같이 하면 model/company/tmp/category-icon/*.png 이미지 파일들을 활용하여 기본 카테고리 정보를 만든다. 


## 필고 Company Book 데이터를 포팅하는 방법

그냥 아래와 같이 하면, HTTP 로 필고에 접속해서 새로운 데이터가 업데이트 된다.

기존의 다른 정보는 놔 두고, 필고 정보만 새로 업데이트를 하므로 주기적으로 실행을 해도 된다.


    




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

