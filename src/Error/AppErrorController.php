<?php
namespace App\Error;

use Cake\Controller\ErrorController;
use Cake\Event\Event;

/**
 * 独自のエラーコントローラー
 */
class AppErrorController extends ErrorController
{
    /**
     * 描画前処理
     */
    public function beforeRender(Event $event)
    {
        // エラーの共通レイアウト名を指定
        $this->viewBuilder()->layout('error');

        //Templateファイルのあるパスを指定(src/Template/Error/)
        $this->viewBuilder()->templatePath('Error');

        // セッションの破棄
        // logアウト
        $this->request->session()->destroy();
    }

}
