<?php

namespace Hansanghyeon;

/**
 * 2021.02.23
 * 네이버 파파고 API를 이용해 번역 서비스 이용
 * @author sample
 */

class Papago
{

    private $papagoUrl;
    private $clientId;
    private $clientSecret;

    /**
     * 생성자
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct($clientId, $clientSecret)
    {

        // 테스트 계정
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        // 일반버전 (유료버전은 주소가 다르다.)
        $this->papagoUrl = 'https://openapi.naver.com/v1/papago/n2mt';
    }

    /**
     * 외부에서 접근 가능한 번역 서비스 메소드
     * 
     * @param string $text
     * @param string $source
     * @param string $target
     * @return array
     */
    public function translate($text, $source = "en", $target = "ko")
    {

        $postData = "source={$source}&target={$target}&text=" . urlencode($text);

        $responseData = $this->call($postData);

        return $responseData;
    }

    /**
     * 네이버 파파고 서비스와 통신하는 메서드 
     * @param string $postData
     * @return Array
     *  (
     *      [code] => 200
     *      [data] => stdClass Object
     *          (
     *              [message] => stdClass Object
     *                  (
     *                      [result] => stdClass Object
     *                          (
     *                              [srcLangType] => en
     *                              [tarLangType] => ko
     *                              [translatedText] => 테스트
     *                              [engineType] => N2MT
     *                              [pivot] => 
     *                              [dict] => 
     *                              [tarDict] => 
     *                              [modelVer] => 1.2.11|enko.2022.0119.02|koen.2022.0119.02
     *                          )
     *                      [@type] => response
     *                      [@service] => naverservice.nmt.proxy
     *                      [@version] => 1.0.0
     *                  )
     *          )
     *  )
     */
    private function call($postData)
    {

        // 일반버전 (유료버전은 키의 이름이 다르다.)
        $headers = array(
            "X-Naver-Client-Id: " . $this->clientId,
            "X-Naver-Client-Secret: " . $this->clientSecret,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->papagoUrl);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        // 헤더와 바디를 구분해준다.
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);

        // 결과값을 코드와 함께 배열로 만들어 반환한다.
        $responseData["code"] = $status_code;
        $responseData["data"] = json_decode($body);

        return $responseData;
    }
}
