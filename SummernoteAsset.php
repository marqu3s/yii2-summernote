<?php

namespace marqu3s\summernote;

use Yii;
use yii\web\AssetBundle;

class SummernoteAsset extends AssetBundle
{
    public $baseUrl = 'https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/';

    /** @var array */
    public $depends = ['yii\bootstrap\BootstrapPluginAsset'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (
            isset(Yii::$app->params['bsVersion']) &&
            substr(Yii::$app->params['bsVersion'], 0, 1) == '5'
        ) {
            $this->depends = ['yii\bootstrap5\BootstrapPluginAsset'];
            $this->css[] = 'summernote-bs5.min.css';
            $this->js[] = 'summernote-bs5.min.js';
        } elseif (
            isset(Yii::$app->params['bsVersion']) &&
            substr(Yii::$app->params['bsVersion'], 0, 1) == '4'
        ) {
            $this->depends = ['yii\bootstrap4\BootstrapPluginAsset'];
            $this->css[] = 'summernote-bs4.min.css';
            $this->js[] = 'summernote-bs4.min.js';
        } else {
            $this->depends = ['yii\bootstrap\BootstrapPluginAsset'];
            $this->css[] = 'summernote.min.css';
            $this->js[] = 'summernote.min.js';
        }

        parent::init();
    }
}
