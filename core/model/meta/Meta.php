<?php
namespace core\model\meta;
use core\model\entity\Entity;


/**
 * Class Meta
 * @package of
 *
 *
 *
 * @usage 특정 그룹의 내용을 추출 할 때에는 아래와 같이 해야하낟.
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
     */
    public function load($id, $fields='*') {
        return parent::load("code='$id'");
    }

    /**
     * 입력된 코드의 값을 변경한다.
     *
     * 내부적으로 entity::put() 을 사용한다.
     *
     * @param $code
     * @param $value
     * @return bool
     */
    public function set($code, $value) {
        $meta = $this->load($code);
        if ( $meta ) return parent::put('value', $value);
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
     * @param $code
     * @return bool|mixed
     */
    public function get($code)
    {
        $meta = $this->load($code);
        if ( $meta ) return parent::get('value');
        else return FALSE;
    }

}
