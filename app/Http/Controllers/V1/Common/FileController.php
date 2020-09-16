<?php


namespace App\Http\Controllers\V1\Common;


use App\Http\Controllers\V1\BaseController;

class FileController extends BaseController
{
    public function download()
    {
        $file = storage_path() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'lumen-2020-09-09.log';
        if (!file_exists($file)) exit();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit();
    }
}
