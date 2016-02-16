<?php
namespace model\philocation;
/**
 * Class PhiLocation
 *
 * @code
    $location = new model\philocation\PhiLocation();
    print_r($location->eng_to_ko);
    print_r($location->ko_to_eng);
 * @endcode
 */
class PhiLocation
{

    public $eng_to_ko = array();
    public $ko_to_eng = array();

    public function __construct()
    {
        $this->eng_to_ko['whole'] = '전지역';
        $this->eng_to_ko["Metro Manila"] = "메트로마닐라";
        $this->eng_to_ko["Makati"] = "마카티";
        $this->eng_to_ko["Malabon"] = "말라본";
        $this->eng_to_ko["Mandaluyong"] = "만달루용";
        $this->eng_to_ko["Manila"] = "마닐라";
        $this->eng_to_ko["Marikina"] = "마리키나";
        $this->eng_to_ko["Muntinlupa"] = "문틴루파";
        $this->eng_to_ko["Navotas"] = "나보타스";
        $this->eng_to_ko["Paranaque"] = "파라냐케";
        $this->eng_to_ko["Pasay"] = "파사이";
        $this->eng_to_ko["Pasig"] = "파식";
        $this->eng_to_ko["Quezon"] = "퀘존";
        $this->eng_to_ko["San Juan"] = "산후안";
        $this->eng_to_ko["Taguig"] = "따귁";
        $this->eng_to_ko["Caloocan"] = "칼로오칸";
        $this->eng_to_ko["Las Pinas"] = "라스피냐스";
        $this->eng_to_ko["Valenzuela"] = "발렌수엘라 시";
        $this->eng_to_ko["Pampanga"] = "팜팡가";
        $this->eng_to_ko["Pampanga - Angeles"] = "앙헬레스";
        $this->eng_to_ko["Pampanga - Mabalacat"] = "마발라캇";
        $this->eng_to_ko["Cebu"] = "세부";
        $this->eng_to_ko["Lapu-Lapu"] = "라푸라푸시";
        $this->eng_to_ko["Cavite"] = "까비테";
        $this->eng_to_ko["Tagaytay"] = "따가이따이";
        $this->eng_to_ko["Silang"] = "실랑";
        $this->eng_to_ko["Rosario"] = "로사리오";
        $this->eng_to_ko["Dasmarinas"] = "다스마리냐스";
        $this->eng_to_ko["Benguet - Baguio"] = "바기오";
        $this->eng_to_ko["Iloilo"] = "일로일로";
        $this->eng_to_ko["Bataan"] = "바타안";
        $this->eng_to_ko["Batangas"] = "바탕가스";
        $this->eng_to_ko["Bohol"] = "보홀";
        $this->eng_to_ko["Bulacan"] = "불라칸";
        $this->eng_to_ko["Cagayan"] = "카가얀";
        $this->eng_to_ko["Leyte"] = "레이테";
        $this->eng_to_ko["Palawan"] = "팔라완";
        $this->eng_to_ko["Pangasinan"] = "팡가시난";
        $this->eng_to_ko["Rizal"] = "리잘";
        $this->eng_to_ko["Antipolo"] = "안티폴로";
        $this->eng_to_ko["Samar"] = "사말";
        $this->eng_to_ko["Tarlac"] = "딸락";
        $this->eng_to_ko['Dumaguete'] = '두마게티';
        $this->eng_to_ko['Zamboanga'] = '잠보앙가';
        $this->eng_to_ko["Davao"] = "다바오";
        $this->eng_to_ko['Zambales'] = '잠발레스';
        $this->eng_to_ko['Subic'] = '수빅';
        $this->eng_to_ko['Laguna'] = '라구나';
        $this->eng_to_ko['San Fernando'] = '산페르난도';
        $this->eng_to_ko['etc'] = '기타지역';

        foreach( $this->eng_to_ko as $e => $k ) {
            $this->ko_to_eng[$k] = $e;
        }

        ksort($this->eng_to_ko);
        ksort($this->ko_to_eng);
    }

    /**
     *
     * @return 필리핀의 지명을 영어에서 한글로 바꾼다. (영어로 입력하면 한글로 리턴해 준다.)
     *
     *
     * @param $place "주 - 도시" 와 같이 영어로 입력되면 된다.
     *    예: "Metro Manila - Mandaluyong City"
     * @code
     *      location::p2k("Metro Manila - Mandaluyong");
     * @endcode
     */
    public function p2k($place)
    {
        if ( isset($this->eng_to_ko[$place]) ) return $this->eng_to_ko[$place];
        else return $place;
    }

    /**
     * p2k 의 반대.
     * 한국어 지명을 영어 지명으로 변경한다.
     * 즉, 한국어로 입력하면 영어로 리턴한다.
     *
     */
    public function k2p($ko)
    {
        return $this->ko_to_eng[$ko];
    }

}
