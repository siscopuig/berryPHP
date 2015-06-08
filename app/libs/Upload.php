<?php

/*
 * Notice that this script is under development and many changes are expected
 *
 * */

// @TODO create a method to check directory and change permissions
// @TODO add comments and methods descriptions
// @TODO delete files in upload folder as soon as file has moved

class Upload
{
    public $file_new_name;
    public $file_original_name;
    public $file_tmp_path;
    public $file_extension;
    public $file_size;
    public $file_size_kb;
    public $file_size_mb;
    public $folder_upload_path;
    public $folder_thumbs_path;
    public $file_src_path;
    public $file_dst_path;
    public $filename;
    public $status;

    public $allowed_ext = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');


    public function __construct($upload_file)
    {

        $this->folder_upload_path = Config::get('PATH_UPLOAD_FILE');
        $this->folder_thumbs_path = Config::get('PATH_THUMB_IMAGE');


        $this->setUploadFilesPost($upload_file);


        $this->validateAndSetFilePostData();

        // return false if
        ($this->status == true ? $this->setStatus(true) : $this->setStatus(false));
    }

    public function setStatus($status = null)
    {
        return $this->status = $status;
    }

    public function getMimeFileType()
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return $finfo_result = finfo_file($finfo, $this->file_tmp_path);
    }


    public function validateAndSetFilePostData()
    {
        $finfo_mime_file_type = $this->getMimeFileType();
        if (!in_array($finfo_mime_file_type, $this->allowed_ext)) {
            Session::add('feedback_negative', Message::get('UPLOAD_MIME_TYPE_FILE_UNKNOWN_OR_CORRUPTED'));
            return false;
        }

        if (!is_dir(Config::get('PATH_UPLOAD_FILE')) AND is_writable(Config::get('PATH_UPLOAD_FILE'))) {
            Session::add('feedback_negative', Message::get('UPLOAD_FOLDER_UPLOAD_IS_NOT_WRITABLE_OR_DOES_NOT_EXISTS'));
            return false;
        }
        if (!is_dir(Config::get('PATH_THUMB_IMAGE')) AND is_writable(Config::get('PATH_THUMB_IMAGE'))) {
            Session::add('feedback_negative', Message::get('UPLOAD_FOLDER_THUMBS_IS_NOT_WRITABLE_OR_DOES_NOT_EXISTS'));
            return false;
        }

        $filesize = $this->get_file_size();
        if (!$filesize) {
            Session::add('feedback_negative', Message::get('UPLOAD_UNEXPECTED_ERROR_FILESIZE'));
            return false;
        }

        $filename = $this->file_original_name;
        $this->createAndSetNewAndUniqueFilename($filename, $finfo_mime_file_type);


        $filesize = $this->file_size;
        $this->file_size_kb = $this->bytes_to_kb($filesize);
        $this->file_size_mb = $this->bytes_to_mb($filesize);

        $this->file_src_path = $this->folder_upload_path . $this->file_new_name;
        $this->file_dst_path = $this->folder_thumbs_path . $this->file_new_name;
        $this->filename = Config::get('BASE_URL') . 'app/public/images/thumbs/' . $this->file_new_name;


        $file_saved = move_uploaded_file($this->file_tmp_path, $this->folder_upload_path . $this->file_new_name);
        if(!$file_saved) {
            Session::add('feedback_negative', Message::get('UPLOAD_MOVE_UPLOAD_FILE_FAILED'));
            return false;
        }

        return $this->setStatus($status = true);
    }


    public function setUploadFilesPost($upload_file)
    {
        $this->file_original_name = strtolower($upload_file['name']);
        $this->file_tmp_path  = $upload_file['tmp_name'];
        $this->file_size = $upload_file['size'];
        $array = explode('.', $this->file_original_name);
        $this->file_extension = end($array);
        //$this->file_extension = end(explode('.', $this->file_original_name));
    }

    protected function createAndSetNewAndUniqueFilename($filename, $finfo_mime_file_type)
    {
        $this->file_new_name = $filename;
        $extension = explode('image/', $finfo_mime_file_type);
        $this->file_extension = end($extension);
        $this->file_new_name = sha1(mt_rand(1, 9999) . $this->folder_thumbs_path . uniqid()) . time(). '.' . $this->file_extension;
        return true;
    }


    /**
     * @param $bytes
     * @return float
     */
    public function bytes_to_mb($bytes)
    {
        return round(($bytes / 1048576), 2);
    }


    /*
     *  Get filesize
     *
     *  @return int
     * */
    public function get_file_size()
    {
        return filesize($this->file_tmp_path);
    }

    /*
     * Convert bytes to kilobytes
     *
     * @return int
     *
     * */
    public function bytes_to_kb($bytes)
    {
        return round(($bytes / 1024), 2);
    }

}
