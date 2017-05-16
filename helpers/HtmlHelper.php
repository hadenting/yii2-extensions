<?php
/**
 * User: hadenting
 * Date: 2017/5/11
 * Time: 15:03
 */

namespace hadenting\helpers;

use yii\bootstrap\Html;

/**
 * Class HtmlHelper
 * @package common\helpers
 */
class HtmlHelper
{

    public static function getImgsHtml($urls)
    {
        $url_html = '';
        if (!empty($urls))
            foreach ($urls as $url_item) {
                $url_html .= Html::img($url_item, ['width' => '200px', 'height' => '200px']);
            }
        return $url_html;
    }
}
