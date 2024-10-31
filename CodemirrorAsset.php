<?php

namespace marqu3s\summernote;

use yii\web\AssetBundle;

class CodemirrorAsset extends AssetBundle
{
    /** @var string */
    public $baseUrl = 'https://cdn.jsdelivr.net/npm/codemirror@5.65.18/';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->css[] = 'lib/codemirror.min.css';
        $this->css[] = 'theme/monokai.css';

        $this->js[] = 'lib/codemirror.min.js';
        $this->js[] = 'mode/xml/xml.js';

        parent::init();
    }
}
