<?php
	
class ProfileModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}


    /**
     * @param $user_id
     * @return mixed
     */
    public function displayUserProfile($user_id)
    {
        $sql = "SELECT user_id, user_name, user_email, user_active, user_has_picture
                FROM users WHERE user_id = :user_id LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id));

        $user = $query->fetch();

        // if found a row
        if($query->rowCount() == 1) {
            //var_dump($user->user_email);
        }
        else {
            Session::add('feedback_negative', Message::get('MESSAGE_USER_NOT_REGISTERED_YET'));
        }

        return $user;
    }


    public function uploadAndSaveProfilePicture()
    {
        $profile_picture = $_FILES['file'];

        $dataInfoPost = new Upload($profile_picture);
        if ($dataInfoPost->status) {

            $resize_picture = $this->resizeProfilePictureAndCreateThumbnail(
                $dataInfoPost->file_src_path, $dataInfoPost->file_dst_path, $dataInfoPost->file_extension);
            if (!$resize_picture) {
                Session::add('feedback_negative', Message::get('UPLOAD_UNSUCCESSFUL_UNKNOWN_PROBLEM_DURING_RESIZE'));
                return false;
            }

            // destroy file in upload folder
            unlink($dataInfoPost->folder_upload_path . $dataInfoPost->file_new_name);


            $user_filename = $this->insertProfilePictureDataInDatabase(Session::get('user_id'), $dataInfoPost->filename);
            if (!$user_filename) {
                Session::add('feedback_negative', Message::get('UPLOAD_UNSUCCESSFUL_UNKNOWN_PROBLEM_DATABASE'));
                return false;
            }

            // know if user already has a profile picture
            $user_has_picture = $this->getUserHasProfilePicture(Session::get('user_id'));
            if ($user_has_picture) {
                Session::set('user_has_picture', $user_has_picture);
            }


            // get user profile picture path and set in Sessions
            $filename_path = $this->getProfilePicturePathByUserId(Session::get('user_id'));
            if ($filename_path) {
                Session::set('user_filename', $filename_path);
            }

            return true;

        }
        else {

            return false;
        }
    }

    public function resizeProfilePictureAndCreateThumbnail($file_src_path, $file_dst_path, $ext)
    {
        $resize_image = new ResizeImage($file_src_path, $file_dst_path, $ext);
        if($resize_image) {
            return true;
        }
    }


    public function insertProfilePictureDataInDatabase($user_id, $user_filename)
    {
        $sql = "UPDATE users SET user_filename = :user_filename, user_has_picture = TRUE WHERE user_id = :user_id LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id, ':user_filename' => $user_filename));
        $count = $query->rowCount();
        if ($count == 1) {
            return true;
        }
        return false;
    }

    public function getProfilePicturePathByUserId($user_id)
    {
        $sql = "SELECT user_filename FROM users WHERE user_id = :user_id LIMIT 1 ";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id));
        $result = $query->fetchColumn();
        return $result;
    }

    public function getUserHasProfilePicture($user_id)
    {
        $sql = "SELECT user_has_picture FROM users WHERE user_id = :user_id LIMIT 1 ";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id));
        $user_has_picture = $query->fetchColumn();
        return $user_has_picture;

    }


} // End of class

