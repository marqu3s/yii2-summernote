<?php

namespace marqu3s\summernote;

use yii\web\AssetBundle;

class SummernoteS3Asset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@vendor/marqu3s/yii2-summernote/assets';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $postfix = YII_DEBUG ? '' : '.min';

        $this->js[] = 'summernote-s3' . $postfix . '.js';

        parent::init();
    }
}
