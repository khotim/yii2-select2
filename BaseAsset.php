<?php
/**
 * @author Thimy Khotim <thimy.khotim@gmail.com>
 * @link https://github.com/khotim/yii2-select2/
 * @license MIT License
 * @version 1.0
 */
namespace khotim\select2;

/**
 * BaseAsset is AssetBundle for Select2 widget.
 */
class BaseAsset extends \yii\web\AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
    
    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        
        $this->sourcePath = __DIR__ . '/assets';
        if (YII_DEBUG) {
            $this->js = ['js/select2.js'];
            $this->css = [
                'css/select2.css',
                'css/select2-custom.css'
            ];
        } else {
            $this->js = ['js/select2.min.js'];
            $this->css = [
                'css/select2.min.css',
                'css/select2-custom.css'
            ];
        }
    }
}
