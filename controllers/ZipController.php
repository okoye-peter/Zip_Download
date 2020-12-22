<?php


namespace controllers;

use Exception;
use ZipArchive;
use File;

trait ZipController{
    public function fetchFiles()
    {
        return json_encode(scandir('./assets/uploads'), true);
    }

    public function ZipFiles()
    {
        try {
            $zip = new ZipArchive;
            $pathdir = 'assets/uploads/';
            $zipCreated = 'myImages.zip';
            if ($zip->open($zipCreated, ZipArchive::CREATE) === true) {
                $dir = opendir($pathdir);
                while ($file = readdir($dir)) {
                    if (is_file($pathdir . $file)) {
                        $zip->addFile($pathdir . $file, $file);
                    }
                    # code...
                }
                $zip->close();
                $this->download($zipCreated);
            } else {
                throw new Exception('Directory couldn\'t be Zipped');
            }
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()], true);
        }        
    }

    public function download($filename)
    {

        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");

        readfile($filename);
        exit();
    }
}