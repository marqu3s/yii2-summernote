<?php

namespace marqu3s\summernote;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

class Summernote extends InputWidget
{
    /** @var array */
    private $defaultOptions = ['class' => 'form-control'];

    /** @var array */
    private $defaultClientOptions = [
        'height' => 200,
        'codemirror' => [
            'theme' => 'monokai',
        ],
    ];

    /** @var array */
    public $options = [];

    /** @var array */
    public $clientOptions = [];

    /** @var bool Set to true to upload images to an Amazon S3 bucket */
    public $uploadToS3 = false;

    ######################################################################
    # The following attributes must be set if uploadToS3 is set to true. #
    ######################################################################

    /** @var string An endpoint (URL) of an action to sign the POST request that will upload the image to Amazon.
     * Check the actions folder for an Action class example.
     * NOTE: You will need to add the Amazon PHP SDK to your project to use this feature!
     * The recommended way to install the PHP SDK is using composer.
     *
     * php composer.phar require aws/aws-sdk-php
     *
     * For more options: http://docs.aws.amazon.com/aws-sdk-php/v3/guide/getting-started/installation.html
     */
    public $signEndpoint;

    /** @var string A bucket name */
    public $bucket;

    /** @var string|JsExpression A folder name. S3 doesn't really use folders but it works like so. */
    public $folder = '';

    /** @var string|JsExpression A prefix to preppend to the filename. */
    public $filenamePrefix = "''";

    /** @var integer The maximum file size allowed in bytes */
    public $maxFileSize;

    /** @var string An expiration date in ISO8601 format (YYYYMMDD'T'HHMMSS'Z'). After this date the POST request will fail. */
    public $expiration;

    /**
     * @inheritdoc
     */
    public function init()
    {
        # Validate attributes required to upload images do S3.
        if ($this->uploadToS3) {
            if (empty($this->signEndpoint)) {
                throw new InvalidConfigException(
                    'The "signEndpoint" attribute must be set because "uploadToS3" is set to true.'
                );
            }

            if (empty($this->bucket)) {
                throw new InvalidConfigException(
                    'The "bucket" attribute must be set because "uploadToS3" is set to true.'
                );
            }

            if (empty($this->maxFileSize)) {
                throw new InvalidConfigException(
                    'The "maxFileSize" attribute must be set because "uploadToS3" is set to true.'
                );
            }

            if (empty($this->expiration)) {
                throw new InvalidConfigException(
                    'The "expiration" attribute must be set because "uploadToS3" is set to true.'
                );
            }
        }

        $this->options = array_merge($this->defaultOptions, $this->options);
        $this->clientOptions = array_merge($this->defaultClientOptions, $this->clientOptions);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();

        echo $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $this->options)
            : Html::textarea($this->name, $this->value, $this->options);

        if (empty($this->folder)) {
            $this->folder = "''";
        }

        # If uploadToS3 is true, create the onImageUpload callback.
        if ($this->uploadToS3) {
            if (!($this->filenamePrefix instanceof JsExpression)) {
                $this->filenamePrefix = "'{$this->filenamePrefix}'";
            }

            $this->clientOptions['callbacks']['onImageUpload'] = "function(files) {
                // get current editable container
                var editor = $(this);

                // set properties of the summernote S3 uploader.
                summernoteS3uploader.signEndpoint = '{$this->signEndpoint}';
                summernoteS3uploader.bucket = '{$this->bucket}';
                summernoteS3uploader.folder = {$this->folder};
                summernoteS3uploader.filenamePrefix = {$this->filenamePrefix};
                summernoteS3uploader.maxFileSize = '{$this->maxFileSize}';
                summernoteS3uploader.expiration = '{$this->expiration}';
                summernoteS3uploader.file = files[0];
                summernoteS3uploader.editor = editor;
                summernoteS3uploader.sendImage();
            }";
        }

        $callbacks = $this->getExtendsParams('callbacks');
        $buttons = $this->getExtendsParams('buttons');
        $modules = $this->getExtendsParams('modules');

        $clientOptions = empty($this->clientOptions) ? null : Json::encode($this->clientOptions);

        $this->getView()->registerJs(
            '
            var params = ' .
                $clientOptions .
                ';' .
                (empty($callbacks) ? '' : 'params.callbacks = { ' . $callbacks . ' };') .
                (empty($buttons) ? '' : 'params.buttons = { ' . $buttons . ' };') .
                (empty($modules) ? '' : 'params.modules = { ' . $modules . ' };') .
                'jQuery( "#' .
                $this->options['id'] .
                '" ).summernote(params);
        '
        );
    }

    private function registerAssets()
    {
        $view = $this->getView();

        if (ArrayHelper::getValue($this->clientOptions, 'codemirror')) {
            CodemirrorAsset::register($view);
        }

        SummernoteAsset::register($view);
        SummernoteFixBS3ButtonsAsset::register($view);

        if ($this->uploadToS3) {
            SummernoteS3Asset::register($view);
        }

        if ($language = ArrayHelper::getValue($this->clientOptions, 'lang', null)) {
            SummernoteLanguageAsset::register($view)->language = $language;
        }
    }

    private function getExtendsParams($param)
    {
        $result = '';
        foreach (ArrayHelper::remove($this->clientOptions, $param, []) as $val => $key) {
            $result .= (empty($result) ? '' : ',') . $val . ': ' . $key;
        }

        return $result;
    }
}
