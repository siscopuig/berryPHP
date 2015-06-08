<?php

class ResizeImage
{
    public $src_img;
    public $src_w;
    public $src_h;
    public $dst_img;
    public $dst_w;
    public $dst_h;
    public $ext;
    public $img;
    public $dst_path;


    public function __construct($src_img, $dst_path, $ext)
    {

        list($src_width, $src_height) = getimagesize($src_img);
        $this->src_img = $src_img;

        // get image width, height
        $this->src_w = $src_width;
        $this->src_h = $src_height;

        $this->ext = $ext;
        $this->dst_path = $dst_path;

        $this->setThumbWidthAndHeightDesired(100, 100);

        $this->resize();
    }

    public function setThumbWidthAndHeightDesired($dst_w, $dst_h)
    {
        $this->dst_w = $dst_w;
        $this->dst_h = $dst_h;
        return $this;
    }

    public function __destruct()
    {
        if (is_resource($this->dst_img))
        {
            imagedestroy($this->dst_img);
        }
    }

    public function resize()
    {
        $scale_ratio = $this->src_w / $this->src_h;

        if($this->src_w < $this->dst_w)
        {
            $this->dst_w = $this->src_w;
        }

        if($this->src_h < $this->dst_h)
        {
            $this->dst_h = $this->src_h;
        }

        if(($this->dst_w / $this->dst_h) > $scale_ratio)
        {
            $this->dst_w = $this->dst_h * $scale_ratio;
        }
        else {
            $this->dst_h = $this->dst_w / $scale_ratio;
        }

        $this->dst_img = imagecreatetruecolor($this->dst_w, $this->dst_h);

        // returns an image identifier representing the image obtained from the given filename
        $this->img = imagecreatefromjpeg($this->src_img);

        // copy and resize part of an image with resampling
        imagecopyresampled($this->dst_img, $this->img, 0,0,0,0, $this->dst_w, $this->dst_h, $this->src_w, $this->src_h);

        // creates a jpeg file from the given image
        imagejpeg($this->dst_img, $this->dst_path, 100);


    }

}
