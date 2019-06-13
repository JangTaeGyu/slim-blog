<?php
namespace App\Traits;

use Slim\Http\UploadedFile;
use App\Traits\Exceptions\UploadException;

trait UploadTrait
{
    protected $filepath;

    protected $filedirectory;

    protected $fileoriginal;

    protected $filename;

    protected $filetype;

    protected $filesize;

    private function doUpload($directory, UploadedFile $uploadedFile, $type = null)
    {
        $this->filepath = '/' . $directory;
        $this->filedirectory = $this->settings['uploadDirectory'] . '/' . $directory;
        $this->fileoriginal = $uploadedFile->getClientFilename();
        $this->filetype = $uploadedFile->getClientMediaType();
        $this->filesize = $uploadedFile->getSize();

        if (!is_dir($this->filedirectory)) {
            mkdir($this->filedirectory, 0777, true);
        }

        if (!is_null($type)) {
            if (strpos($this->filetype, $type) === false) {
                return false;
            }
        }

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $baseName = bin2hex(random_bytes(8));

        $this->filename = sprintf('%s.%0.8s', $baseName, $extension);

        $uploadedFile->moveTo($this->filedirectory . DIRECTORY_SEPARATOR . $this->filename);

        return true;
    }

    private function getUpload()
    {
        return [
            'path' => $this->filepath,
            'directory' => $this->filedirectory,
            'origina' => $this->fileoriginal,
            'name' => $this->filename,
            'type' => $this->filetype,
            'size' => $this->filesize
        ];
    }

}
