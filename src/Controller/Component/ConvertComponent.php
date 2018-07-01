<?php

namespace App\Controller\component;

use Cake\Controller\Component;

/**
 * 変換コンポーネント
 */
class ConvertComponent extends Component
{

    /**
     * ひらがなに変換
     * @param $sentence ふりがなを付ける対象の文字
     * @return 変換した値
     */
    public function toHiragana($sentence)
    {

        if (empty($sentence)) {
            return '';
        }

        // 【参考】http://www.imuza.com/entry/2016/02/19/194719
        $api = 'http://jlp.yahooapis.jp/FuriganaService/V1/furigana';
        $appid = 'dj00aiZpPXBBSHdGMW4zNUo0NCZzPWNvbnN1bWVyc2VjcmV0Jng9ZmQ-';

        $params = array(
            'sentence' => $sentence,
        );

        $ch = curl_init($api . '?' . http_build_query($params));
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => "Yahoo AppID: $appid",
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($result);
        $furigana = '';
        foreach ($xml->Result->WordList as $WordList) {
            foreach ($WordList->Word as $Word) {
                if (isset($Word->Furigana)) {
                    $furigana .= (string)$Word->Furigana;
                } else {
                    $furigana .= (string)$Word->Surface;
                }
            }
        }

        return $furigana;
    }
}
