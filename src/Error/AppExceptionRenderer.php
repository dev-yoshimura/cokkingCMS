<?php
namespace App\Error;

use App\Error\AppErrorController;
use Cake\Error\ExceptionRenderer;
use Exception;

/**
 * 独自例外用レンダラー
 */
class AppExceptionRenderer extends ExceptionRenderer
{
    /**
     * 独自のコントローラーを指定
     */
    protected function _getController()
    {
        return new AppErrorController();
    }

    /**
     * 独自のテンプレート名を指定
     */
    protected function _template(Exception $exception, $method, $code)
    {
        return $this->template = 'error';
    }

}
