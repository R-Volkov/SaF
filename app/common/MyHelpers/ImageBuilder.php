<?php

namespace common\MyHelpers;

class ImageBuilder
{
	public static function createMiniature($image_name, $image_small_name, $image_extension, $path, $height = NULL, $width = NULL)
	{
        if ($image_extension == 'png') {
            $image_origin = imagecreatefrompng("$path/" . "$image_name");
            list($image_x, $image_y) = getimagesize("$path/" . "$image_name");
            $image_small_width = $image_x * $height / $image_y;
            $image_small = imagecreatetruecolor($image_small_width, $height);
            imagecopyresampled($image_small, $image_origin, 0, 0, 0, 0, $image_small_width, $height, $image_x, $image_y);
            imagepng($image_small, "$path/" . "$image_small_name", 0); 
        } elseif (($image_extension == 'jpg') || ($image_extension == 'jpeg')) {
            $image_origin = imagecreatefromjpeg("$path/" . "$image_name");
            list($image_x, $image_y) = getimagesize("$path/" . "$image_name");
            $image_small_width = $image_x * $height / $image_y;
            $image_small = imagecreatetruecolor($image_small_width, $height);
            imagecopyresampled($image_small, $image_origin, 0, 0, 0, 0, $image_small_width, $height, $image_x, $image_y);
            imagejpeg($image_small, "$path/" . "$image_small_name", 100); 
        }
    }
    
}