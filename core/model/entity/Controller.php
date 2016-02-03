<?php

namespace core\model\entity;


class Controller extends Entity {

    public function __construct()
    {
        parent::__construct();
    }


    /**
     *
     *
     * 데이터베이스 테이블이 존재하는지 하지 않는지 확인르 한다.
     *
     * @Attention 이 메소드는 언뜻 보기에는 해당 Entity 클래스가 존재하는지 확인하는 것 같지만, 사실은 데이터베이스 존재 여부만 확인을 한다.
     *
     *
     * @param $in
     * @return array
     *
     *
     * @usage php index.php "route=entity.Controller.exist&node=user"
     *
     */
    public function exist($in)
    {
        $tablename = null;
        if ( isset($in['node']) ) $tablename = $in['node'] . "_node_entity";
        else if ( isset($in['meta']) ) $tablename = $in['meta'] . "_meta_entity";
        else ERROR( -401, 'Wrong input' );
        $re = parent::exists($tablename);
        if ( $re ) return SUCCESS();
        else return ERROR(-402, "$tablename does not exists.");
    }

    /**
     *
     *
     * @note HTTP 입력 변수로 condition 값이 들어오며 적절한 처리를 하여 JSON 으로 리턴한다.
     *
     * 내부적으로는 Entity::search() 를 사용하므로 where, limit, offset, page, order_by, fields 의 값을 그대로 사용 할 수 있다.
     *
     *
     *  &entity=데이터베이스 테이블
     *  &where=의 값은 entity->search() 의 where SQL 컨디션과 동일
     *  &order_by=SQL ORDER BY 컨디션
     *  &limit=, &offset=, &page= 의 값은 entity::search() 의 것과 동일
     *
     * @return JSON 내부의 동작은 entity::search() 의 것과 동일하지만, 결과는 JSON 으로 리턴한다.
     *
     * @code 쿼리 예제
     * http://philgo.org/?module=overframe&action=index&model=entity.crud.collect&entity=data&order_by=id%20ASC&fields=id,finish,name&where=id%3E3&limit=3
     * @endcode
     */
    public function collect($in) {

        $o['fields'] = isset($in['fields']) ? $in['fields'] : '*';
        $o['where'] = isset($in['where']) ? $in['where'] : null;
        $o['order_by'] = isset($in['order_by']) ? $in['order_by'] : 'id DESC';
        $o['limit'] = isset($in['limit']) ? $in['limit'] : 10;
        $o['page'] = isset($in['page']) ? $in['page'] : 1;
        $o['offset'] = isset($in['offset']) ? $in['offset'] : 0;

        $entity_name = $in['entity'];
        $node = node($entity_name);
        $entities = $node->search( $o );

        $data = array();

        foreach ( $entities as $e ) {
            $data[] = $e->getRecord();
        }

        return $data;
    }

}