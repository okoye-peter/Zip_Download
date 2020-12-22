<?php

namespace controllers;

use Exception;

trait UploadController{
    protected $file_name;
    protected $allowedFileType = ['jpg','jpeg','png'];
    public function validate($file)
    {
        // check if it is an image
        if (!getimagesize($file['image']['tmp_name'])) {
            throw new Exception('only images are allowed');
        }
        // check if image type is allowed
        if (!in_array(strtolower(pathinfo($file['image']['name'], PATHINFO_EXTENSION)), $this->allowedFileType)) {
            throw new Exception('image not allowed');
        }
        // check the filesize
        if ($file['image']['size'] > 10000000) {
            throw new Exception('image size must not be more than 10MB');
        }

        $this->file_name =  $file['image']['name'];
        // check if file exists
        if (file_exists('assets/uploads/'.$file['image']['name'])) {
            $this->file_name = uniqid().'.'. strtolower(pathinfo($file['image']['name'], PATHINFO_EXTENSION));
        }
        return $file;
    }

    public function upload($file)
    {
        try {
            $image = $this->validate($file);
            if (move_uploaded_file($image['image']['tmp_name'], 'assets/uploads/'.$this->file_name)) {
                return json_encode(['success'=>"file upload successfully"], true);
            }
        } catch (Exception $e) {
            return json_encode(['error'=>$e->getMessage()]);
        }
    }

    public function delete($image)
    {
        if ($image['image']) {
            if (file_exists('assets/uploads/' .$image['image'])) {
                unlink('assets/uploads/' . $image['image']);
                return json_encode(['success'=>'image deleted successfully'], true);
            }
        }
    }

}