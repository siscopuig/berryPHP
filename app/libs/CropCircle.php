<?php


class CircleCrop
{

    public $src_img;
    public $src_w;
    public $src_h;
    public $dst_img;
    public $dst_w;
    public $dst_h;
    public $dst_path;


    public function __construct($img, $dstWidth, $dstHeight, $dest)
    {
        // we set some properties
        $this->src_img = $img;

        // get image width
        $this->scr_w = imagesx($img);

        //get image height
        $this->src_h = imagesy($img);

        $this->dst_w = $dstWidth;
        $this->dst_h = $dstHeight;
        $this->dst_path = $dest;


    }

    public function __destruct()
    {
        // returns true if variable is a resource
        if (is_resource($this->dst_img)) {
            // frees any memory associated with image
            imagedestroy($this->dst_img);
        }
    }

    public function reset()
    {
        if (is_resource($this->dst_img)) {
            imagedestroy($this->dst_img);
        }

        // returns an image identifier representing a black image of the specified size
        $this->dst_img = imagecreatetruecolor($this->dst_w, $this->dst_h);

        // copy part of an image. Returns true on success
        imagecopy($this->dst_img, $this->src_img, 0, 0, 0, 0, $this->dst_w, $this->dst_h);

        return $this;
    }

    // set properties width & height
    public function size($dstWidth, $dstHeight)
    {
        // 153 × 180

        $this->dst_w = $dstWidth;
        $this->dst_h = $dstHeight;
        return $this->reset();
    }

    public function crop()
    {
        $this->reset();

        $mask = imagecreatetruecolor($this->dst_w, $this->dst_h);

        // allocate a color for an image
        $maskTransparent = imagecolorallocate($mask, 255, 0, 255);

        // define a color as transparent
        imagecolortransparent($mask, $maskTransparent);


        // draws an ellipse centered at the specified coordinate on the given image
        imagefilledellipse($mask, $this->dst_w / 2, $this->dst_h / 2, $this->dst_w, $this->dst_h, $maskTransparent);

        // copy and merge part of an image
        imagecopymerge($this->dst_img, $mask, 0, 0, 0, 0, $this->dst_w, $this->dst_h, 100);

        // set color for an image
        $dstTransparent = imagecolorallocate($this->dst_img, 255, 0, 255);



        // performs a flood fill starting at the given coordinate (top left is 0, 0) with the given color in the image.
        imagefill($this->dst_img, 0, 0, $dstTransparent);
        imagefill($this->dst_img, $this->dst_w - 1, 0, $dstTransparent);
        imagefill($this->dst_img, 0, $this->dst_h - 1, $dstTransparent);
        imagefill($this->dst_img, $this->dst_w - 1, $this->dst_h - 1, $dstTransparent);

        // set the transparent color in the given image
        imagecolortransparent($this->dst_img, $dstTransparent);

        // creates a jpeg file from the given image
        imagepng($this->dst_img,$this->dst_path);

    }

} // end CircleCrop


