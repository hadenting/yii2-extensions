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

    public static function ImageInput($form, $model, $attribute)
    {
        $tb = $model->tableName();
        $tb_id = str_replace('_', '', $tb);
        echo '<div class="form-group">';
        echo Html::label($model->getAttributeLabel($attribute));
        echo FileInput::widget([
            'name' => 'fileinput',
            'options' => [
                'accept' => 'image/*',
                //'multiple'=>true
            ],
            'pluginOptions' => [
                'showUpload' => false,
                'uploadUrl' => \yii\helpers\Url::toRoute(['site/upload']) . "?input_name=fileinput",
                'showRemove' => false,
                'autoReplace' => true,
                'maxFileCount' => 1,
                'initialPreview' => [
                    $model->$attribute,
                ],
                'initialPreviewAsData' => true,
                //删除、上传、查看icon按钮设置
                'fileActionSettings' => [
                    'showZoom' => false,
                    'showUpload' => false,
                    'showRemove' => false,
                ],
            ],
            'pluginEvents' => [
                "filecleared" => "function(event){
                            $('#$tb_id-$attribute').val('');
                        }",
                "fileuploaded" => "function(event, data, previewId, index){
                        var response = data.response;
                        $('#$tb_id-$attribute').val(response.res_url);
                     }",
                'change' => 'function(){$(this).fileinput("upload");}'
            ]
        ]);
        echo $form->field($model, $attribute)->hiddenInput()->label(false);
        echo '</div>';
    }

    /**
     * @param $pics 预览图数组
     * @param $max_file_count 最大文件数量
     * @param $label 显示文字
     * @throws \Exception
     */
    public static function ImageInputMultiple($pics, $max_file_count, $label)
    {
        $pics = isset($pics) ? $pics : [];
        $label = isset($label) ? $label : '图片';
        $max_file_count = isset($max_file_count) ? $max_file_count : 9;

        echo '<div class="form-group">';
        echo Html::label($label);
        echo FileInput::widget([
            'name' => 'upload_pics[]',
            'options' => [
                'accept' => 'image/*',
                'multiple' => true
            ],
            'pluginOptions' => [
                'initialPreview' => self::initialPreviewHtml($pics),
                'initialPreviewAsData' => false,
                'initialPreviewConfig' => $pics,
                //删除、上传、查看icon按钮设置
                'fileActionSettings' => [
                    'showZoom' => false,
                    'showUpload' => false,
                    'showRemove' => true,
                ],
                'overwriteInitial' => false,//上传文件后，预览图是否不保留
                'deleteUrl' => \yii\helpers\Url::toRoute(['site/file-delete']),
                'showCaption' => false,//是否展示“选择”按前面名的input区域，默认ture
                'showPreview' => true,//是否展示“拖拽文件到这里...”区域，默认ture
                'showRemove' => false,//是否展示“选择”按钮前面的“移除”按钮，默认ture
                'showUpload' => false,//是否展示“选择”按钮前面的“上传”按钮，默认ture
                'showCancel' => false,//是否展示在上传文件过程中，“选择”按钮前面的“取消”按钮，默认ture
                'showClose' => false,//是否展示“拖拽文件到这里...”区域右上角的X关闭按钮，默认true
                'showUploadedThumbs' => true,//再次上传图片时，是否保留之前上传的图片缩略图，默认true
                'uploadUrl' => \yii\helpers\Url::toRoute(['site/upload']) . '?input_name=upload_pics',
                'autoReplace' => false,
                'maxFileCount' => $max_file_count
            ],
            'pluginEvents' => [
                "fileuploaded" => "function(event, data, previewId, index){
						if(data.response.status == '200'){
							html = '<input type=\"hidden\" name=\"hid_photos[]\" value=\"'+data.response.res_url+'\">';
							$('#'+previewId).children().first().append(html);
						}else{
							$('#'+previewId).remove();
							layer.alert(data.response.message);
						}
                     }",
                "fileimagesloaded" => "function(){
						have_uploaded = $('[name=\"hid_photos[]\"]').length;
						going_upload = $(this).fileinput('getFileStack').length;
						if(have_uploaded+going_upload > $max_file_count){
							layer.alert('照片展示限制最多上传'+$max_file_count+'张照片');
							$(this).fileinput('cancel');
							$(this).fileinput('reset');
						}else{
							$(this).fileinput('upload');
						}
					}",
            ]
        ]);
        echo '</div>';
    }

    public static function AudioInput($form, $model, $attribute)
    {
        $tb = $model->tableName();
        $tb_id = str_replace('_', '', $tb);
        echo '<div class="form-group">';
        echo Html::label($model->getAttributeLabel($attribute));
        echo FileInput::widget([
            'name' => 'fileinput',
            'options' => [
                'accept' => 'audio/*',
                //'multiple'=>true
            ],
            'pluginOptions' => [
                'showUpload' => false,
                'uploadUrl' => \yii\helpers\Url::toRoute(['site/upload']) . "?input_name=fileinput",
                'showRemove' => false,
                'autoReplace' => true,
                'maxFileCount' => 1,
                'initialPreview' => [
                    $model->$attribute,
                ],
                'initialPreviewAsData' => true,
                //删除、上传、查看icon按钮设置
                'fileActionSettings' => [
                    'showZoom' => false,
                    'showUpload' => false,
                    'showRemove' => false,
                ],
            ],
            'pluginEvents' => [
                "filecleared" => "function(event){
                            $('#$tb_id-$attribute').val('');
                        }",
                "fileuploaded" => "function(event, data, previewId, index){
                        var response = data.response;
                        $('#$tb_id-$attribute').val(response.res_url);
                     }",
                'change' => 'function(){$(this).fileinput("upload");}'
            ]
        ]);
        echo $form->field($model, $attribute)->hiddenInput()->label(false);
        echo '</div>';
    }

    public static function FileInput($form, $model, $attribute, $params = '')
    {
        $tb = $model->tableName();
        $tb_id = str_replace('_', '', $tb);
        echo '<div class="form-group">';
        echo Html::label($model->getAttributeLabel($attribute));
        echo FileInput::widget([
            'name' => 'fileinput',
            'options' => [
//                'accept' => 'pdf/*',
                //'multiple'=>true
            ],
            'pluginOptions' => [
                'showUpload' => false,
                'uploadUrl' => \yii\helpers\Url::toRoute(['site/upload']) . "?input_name=fileinput" . $params,
                'showRemove' => false,
                'autoReplace' => true,
//                'maxFileCount' => 1,
                'initialPreview' => [
                    $model->$attribute,
                ],
                'initialPreviewAsData' => true,
                //删除、上传、查看icon按钮设置
                'fileActionSettings' => [
                    'showZoom' => false,
                    'showUpload' => false,
                    'showRemove' => false,
                ],
            ],
            'pluginEvents' => [
                "filecleared" => "function(event){
                            $('#$tb_id-$attribute').val('');
                        }",
                "fileuploaded" => "function(event, data, previewId, index){
                        var response = data.response;
                        $('#$tb_id-$attribute').val(response.res_url);
                     }",
                'change' => 'function(){$(this).fileinput("upload");}'
            ]
        ]);
        echo $form->field($model, $attribute)->hiddenInput()->label(false);
        echo '</div>';
    }

    private static function initialPreviewHtml($data)
    {
        $arr = Array();
        foreach ($data as $item) {
            $arr[] = '<img src="' . $item . '" class="file-preview-image kv-preview-data" title="" alt="" style="width:auto;height:160px;">'
                . '<input type="hidden" name="hid_photos[]" value="' . $item . '">';
        }
        return $arr;
    }

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
