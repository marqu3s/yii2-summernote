<?php

namespace marqu3s\summernote;

use yii\web\AssetBundle;

class SummernoteLanguageAsset extends AssetBundle
{
    /** @var string */
    public $baseUrl = 'https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/lang';

    /** @var array */
    public $depends = ['marqu3s\summernote\SummernoteAsset'];

    /** @var string */
    public $language = 'pt-BR';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->js[] = 'summernote-' . $this->language . '.min.js';
    }
}
