<?php

namespace marqu3s\summernote;

use Yii;
use yii\web\AssetBundle;

class SummernoteAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@bower/summernote/dist';
    /** @var array */
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $postfix = YII_DEBUG ? '' : '.min';

        if (isset(Yii::$app->params['bsVersion']) && substr(Yii::$app->params['bsVersion'], 0, 1) == '5') {
            $this->depends = ['yii\bootstrap5\BootstrapPluginAsset'];
            $this->css[] = 'summernote-bs5' . $postfix . '.css';
            $this->js[] = 'summernote-bs5' . $postfix . '.js';
        } elseif (isset(Yii::$app->params['bsVersion']) && substr(Yii::$app->params['bsVersion'], 0, 1) == '4') {
            $this->depends = ['yii\bootstrap4\BootstrapPluginAsset'];
            $this->css[] = 'summernote-bs4' . $postfix . '.css';
            $this->js[] = 'summernote-bs4' . $postfix . '.js';
        } else {
            $this->depends = ['yii\bootstrap\BootstrapPluginAsset'];
            $this->css[] = 'summernote.css';
            $this->js[] = 'summernote' . $postfix . '.js';
        }

        parent::init();
    }
}
