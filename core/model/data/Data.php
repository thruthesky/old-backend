<?php
namespace core\model\data;

use core\model\node\Node;

class Data extends Node {

    private $in;

    public function __construct()
    {
        parent::__construct();
        $this->setTableName('data');
        $this->in = http_input();
    }


    /**
     * @param $form_name
     * @return array|int
     *      - 에러가 있으면 숫자를 리턴
     *      - 에러가 없으면, 저장된 파일 레코드를 배열로 리턴.
     */
    public function upload( $form_name ) {
        sys()->log("Data::upload( $form_name )");

        $this->deleteUnfishedUploads();

        if ( ! isset($_FILES[$form_name]) ) {
            sys()->log("No file selected on upload box.");
            return UPLOAD_ERR_NO_FILE;
        }

        $_file = $_FILES[$form_name];
        if ( ! is_uploaded_file($_file['tmp_name'])) return isset($_file['error']) ? $_file['error'] : 4;

        $filename = $this->getNextFilename($_file['name']);

        $path = $this->path($filename);
        if ( ! move_uploaded_file($_file["tmp_name"], $path) ) {
            if ( is_dir( DIR_DATA_UPLOAD )) {
                sys()->log("ERROR: move_uploaded_file($_file[tmp_name], $path)");
            }
            else {
                sys()->log("ERROR: data/upload folder does not exists.");
            }
            return -4004901;
        }

        $data = $this->create();
        $data->set('name', $_file['name']);
        $data->set('mime', $_file['type']);
        $data->set('size', $_file['size']);
        $data->set('name_saved', $filename);
        $data->set('finish', 0);
        $data->set('code', $this->in['code']);
        $data->set('gid', $this->in['gid']);
        $data->save();

        $file = data( $data->get('id') );
        return $file->getRecord();
    }

    /**
     *
     * 첨부 파일을 CLI 로 부터 복사를 해서 넣는다.
     * @param $in
     *      $in['gid'] - 해당 GID
     *      $in['code'] - 해당 CODE
     *      $in['finish'] = 1 이면 finish 를 한다.
     *      $in['unique'] = 1 이면, 기존의 gid, code 파일을 지운다.
     * @return array
     *
     * @code
     * >php index.php "route=data.Controller.copyUpload&from=model/company/tmp/category-icon/gov.png&gid=abc&code=def&finish=1&unique=1"
     * @endcode
     *
     * @note 아래의 예제를 참고한다.
     * @example >php index.php route=company.Controller.inputCategoryData
     */
    public function copyUpload($in) {
        $pi = pathinfo($in['from']);
        $filename = $this->getNextFilename($pi['basename']);
        $path = $this->path($filename);
        copy($in['from'], $path );
        $data = $this->create();
        $data->set('name', $pi['basename']);
        $data->set('mime', mime_content_type($in['from']));
        $data->set('size', filesize( $in['from']));
        $data->set('name_saved', $filename);
        $data->set('finish', 0);
        $data->set('code', $in['code']);
        $data->set('gid', $in['gid']);
        if ( $in['finish'] ) $data->set('finish', 1);
        if ( $in['unique'] ) $this->fileDeleteUnique($in['gid'], $in['code'] );
        $data->save();
        $file = data( $data->get('id') );
        return $file->getRecord();
    }

    public function getErrorString($code) {
        switch ($code)
        {
            case UPLOAD_ERR_INI_SIZE: return 'upload_file_exceeds_limit';
            case UPLOAD_ERR_FORM_SIZE: return 'upload_file_exceeds_form_limit';
            case UPLOAD_ERR_PARTIAL: return 'upload_file_partial';
            case UPLOAD_ERR_NO_FILE: return 'upload_no_file_selected';
            case UPLOAD_ERR_NO_TMP_DIR: return 'upload_no_temp_directory';
            case UPLOAD_ERR_CANT_WRITE: return 'upload_unable_to_write_file';
            case UPLOAD_ERR_EXTENSION: return 'upload_stopped_by_extension';
            case -4004901 : return 'Uploaded file movement failed';
            default: return 'upload_no_file_selected';
        }
    }


    public function finish()
    {
        sys()->log("Data::finish() : id=" . $this->get('id'));
        if ( $this->is() ) {
            $this->put('finish', 1);
            return 0;
        }
        else return -1;
    }

    private function deleteUnfishedUploads()
    {
        $stamp = time() - 60 * 60 * 4; // 4 hours.
        $entities = $this->loadQuery("finish=0 AND created<$stamp");
        if ( $entities ) {
            foreach ( $entities as $data ) {
                $data->delete();
            }
        }
    }


    /**
     * @return int
     */
    public function delete() {
        //sys()->log("Data::delete() this:");
        //sys()->log($this);
        if ( $this->is() ) {
            sys()->log("Data::delete() : path: " . $this->get('path'));
            @unlink($this->get('path'));
            parent::delete();
            return 0;
        }
        else {
            sys()->log("Data::delete() : data object does not exists. File does not exists.");
            return -1;
        }
    }


    /**
     * Backward Overriding
     * @param $id
     * @param string $fields
     * @return Data
     * @Attention 새로운 객체를 로드해서 그 객체의 url, path 값을 지정하므로, 리턴되는 객체를 그대로 사용해야한다.
     *      객체화된 기존 객체를 사용하면 안된다.
     * @code 중요 : 아래와 같이 객체를 받아서 사용해야한다. $new_data 를 사용해야한다. 그냥 $data 를 사용하면 안된다.
            $data = new Data();
            $new_data = $data->load( $id );
     * @endcode
     */
    public function load($id, $fields='*') {
        sys()->log("Data::load($id)");
        $data = parent::load($id, $fields);
        if ( $data && $data->is() ) {
            sys()->log("Setting url & path : ");
            $data->set( 'url', $this->url($this->get('name_saved')) );
            $data->set( 'path', $this->path( $this->get('name_saved') ) );
        }
        return $data;
    }

    private function path($filename) {
        return DIR_DATA_UPLOAD . '/' . $filename;
    }

    private function url($filename) {
        $url = url_script();
        $path = parse_url($url, PHP_URL_PATH);
        $path = str_replace('/index.php', '', $path);
        return url_domain() . $path . '/' . PATH_DATA_UPLOAD . '/' . $filename;
    }



    private function getNextFilename($filename) {
        $filename = $this->makeSafeFilename($filename);
        if ( ! is_file($this->path($filename)) ) return $filename;

        $pi = pathinfo($filename);
        for ( $i=1; $i<9999; $i++ ) {
            if ( ! empty( $pi['extension'] ) ) $new_name = $pi['filename'] . "-$i." . $pi['extension'];
            else {
                $new_name = $pi['filename']. "-$i";
            }
            if ( ! is_file($this->path($new_name)) ) return $new_name;
        }
        return unique_id();
    }

    private function makeSafeFilename($filename)
    {
        // Remove any trailing dots, as those aren't ever valid file names.
        $filename = rtrim($filename, '.');
        $filename = str_replace(' ', '-', $filename);
        $regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
        $filename = trim(preg_replace($regex, '', $filename));
        return $filename;
    }

    /**
     *
     * @note 모든 Ajax 파일 업로드는 이 함수 하나만을 통해서 업로드해야 한다.
     * README 파일 참고
     */
    public function fileUpload()
    {
        if ( hi('unique') ) $this->fileDeleteUnique();
        $re = $this->upload('userfile');
        if ( is_numeric($re) ) return ERROR( $re, data()->getErrorString($re) );
        else {
            //sys()->log( $re );
            if ( hi('finish') ) $this->finish();
            return SUCCESS( $re );
        }
    }

    /**
     *
     * It nd removes the previously uploaded file with same 'gid' and 'code'.
     *
     * 이전에 업로드한 'gid' 와 'code' 의 파일을 지운다.
     *
     * 동일한 'gid', 'code' 의 파일을 하나만 유지하고자 하는 경우에 사용하면 된다.
     *
     * @usage Use this method when you want to upload a file and maintain only the newly uploaded file by deleting previously uploaded file with same 'gid' and 'code'
     *
     *
     *
     *
     * @return array
     *
     *
     */
    public function fileDeleteUnique($gid=null, $code=null)
    {
        $gid = $gid ? $gid : hi('gid');
        $code = $code ? $code : hi('code');
        $files = data()->loadQuery("gid='$gid' AND code='$code'");
        sys()->log($files);
        if ($files) {
            foreach ($files as $file) {
                sys()->log($file);
                $file->delete();
            }
        }

    }


    public function fileDelete()
    {
        sys()->log("ajaxFileDelete() : id=" . $this->in['id']);
        $data = data($this->in['id']);
        if ( $data ) {
            if ( $code = $data->delete() ) return ERROR($code, "failed to delete file");
            else return SUCCESS(array('id'=>$this->in['id']));
        }
        else return ERROR(-4333, "Entity does not exists.");
    }

    /**
     *
     * @deprecated
     *
    public function fileFinish()
    {
        sys()->log("ajaxFileFinish() : id=" . $this->in['id']);
        if ( $code = data($this->in['id'])->finish() ) json_error($code, "failed to finish file");
        else json_success(array('id'=>$this->in['id']));
    }
     *
     */


    /**
     * gid 를 바탕으로 관련된 모든 데이터를 리턴한다.
     * @param $gid
     * @return array
     */
    public function loadByGid($gid)
    {
        return $this->loadQuery("gid='$gid'");
    }

    /**
     * gid 를 바탕으로 관련된 모든 데이터를 추출하여 그 중 첫번째 데이터 파일만 로드해서 리턴한다.
     * @param $gid
     * @return bool|Data
     */
    public function loadByGidOne($gid)
    {
        $ids = $this->loadQueryID("gid='$gid'");
        if ( $ids ) {
            return $this->load($ids[0]);
        }
        else return FALSE;
    }




}