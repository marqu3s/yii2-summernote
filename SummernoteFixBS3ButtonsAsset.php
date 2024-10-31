<?php

namespace marqu3s\summernote;

use Yii;
use yii\web\AssetBundle;

class SummernoteFixBS3ButtonsAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@vendor/marqu3s/yii2-summernote/assets';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (
            !isset(Yii::$app->params['bsVersion']) ||
            substr(Yii::$app->params['bsVersion'], 0, 1) == '3'
        ) {
            $this->css[] = 'fix-bs3-groups-buttons.css';
        }

        parent::init();
    }
}
