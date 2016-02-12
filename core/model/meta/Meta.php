<?php
namespace core\model\meta;
use core\model\entity\Entity;


/**
 * Class Meta
 * @package of
 *
 *
 *
 * @usage 특정 그룹의 내용을 추출 할 때에는 아래와 같이 해야한다.
 * @code
$meta = meta('philgo');
$users = $meta->rows("code LIKE 'google_store.%'");
 * @endcode
 *
 * @code 삭제
        $meta = meta('philgo');
        $meta->load($in['code']);
        if ( $meta && $meta->is() ) $meta->delete();
 * @endcode
 *
 * @code Meta 의 경우, 전체 레코드를 읽거나 부분적으로 읽는다면, Array 로 값을 가져오는 것이 간편하다.
        $cats = category()->loadAllArray();
 * @endcode
 *
 * @code id 를 바탕으로 값을 읽어서 id 를 유지한 채 키와 코드를 바꾸는 방법.
        $meta = meta('table-name')->load($id);
        $meta->sets(['code'=>hi('code'), 'value'=>hi('value')])->save();
 * @endcode
 *
 *
 */
class Meta extends Entity {

    public function __construct() {
        parent::__construct();
    }

    public function init() {
        parent::init();
        $this->addColumn('code', 'varchar', '64', '');
        $this->addColumn('value', 'text');
        $this->addUniqueKey('code');
        return $this;
    }

    public function setTableName($name) {
        $name = $name . '_meta';
        parent::setTableName($name);
    }

    /**
     * Load an item by 'code'
     * @param $id - is the code
     * @param string $fields
     * @return $this|bool - returns FALSE If there is no record matching.
     * - returns FALSE If there is no record matching.
     * @warning If the key is numeric, then you must use loadBy('code', 123);
     *
     * @code 메타키 삭제 방법
     *      meta('table-name')->load('key')->delete();
     *      meta('table-name')->load(1)->delete();
     * @endcode
     * @Attention $id 가 숫자이면, 필드의 id 값을 바탕으로 로드한다.
     */
    public function load($id, $fields='*') {
        if ( is_numeric($id) ) return parent::load($id);
        else return parent::load("code='$id'");
    }

    /**
     * 입력된 코드의 값을 생성 또는 변경한다.
     *
     *
     * @param $code
     * @param $value
     * @return bool
     */
    public function set($code, $value) {
        $meta = $this->load($code);
        if ( $meta ) {
            //return parent::put('value', $value);
            parent::set('code', $code);
            parent::set('value', $value);
            $re = parent::save();
            return $re;
        }
        else {
            $this->create();
            parent::set('code', $code);
            parent::set('value', $value);
            $re = parent::save();
	return $re;
        }
    }

    /**
     * 코드를 입력받아서 현재 object 에 로드 한 다음, 값을 리턴한다.
     *
     * @Attention meta code 의 value 가 null 이거나 empty 이면 레코드가 있음에도 불구하고 false 를 리턴하므로 주의해야 한다.
     *
     * @param $code
     * @return bool|mixed
     */
    public function get($code)
    {
        $meta = $this->load($code);
        if ( $meta ) return parent::get('value');
        else return FALSE;
    }


    /**
     *
     * 해당 코드의 id 를 리턴한다.
     *
     * @return array
     * @code
     *  $cat = meta('code')->getID();
     * @endcode
     */
    public function getID()
    {
        return $this->getRecord('id');
    }


}
