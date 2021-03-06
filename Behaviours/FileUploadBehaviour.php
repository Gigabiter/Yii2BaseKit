<?php

namespace kosuhin\Yii2BaseKit\Behaviours;

use yii\base\Behavior;
use yii\web\UploadedFile;

/**
 * TODO many files upload ability
 * @package kosuhin\Yii2BaseKit\Behaviours
 */
class FileUploadBehaviour extends Behavior
{
    /**
     * @var UploadedFile
     */
    public $file;

    public $type;

    public function upload()
    {
        if (!$this->file) {
            return false;
        }
        $filePath = 'uploads/' . $this->type . '/';
        $pathFromRoot = \Yii::getAlias('@webroot') . '/' . $filePath;
        if (!is_dir($pathFromRoot)) {
            mkdir($pathFromRoot);
            chmod($pathFromRoot, 0755);
        }
        $filename = $filePath . $this->file->baseName . '-' . time() . '.' . $this->file->extension;
        $this->file->saveAs($filename);
        $this->owner->src = '/'.$filename;
        $this->file = null;
        $this->owner->save();

        return true;
    }
}