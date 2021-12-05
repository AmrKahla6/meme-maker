<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

Trait  backendTraits
{
     // save image by Image Intervention
     function imageInterve($image,$path){
        // Image::make($image)
        // ->save(public_path($path .$image->hashName()));

        Image::make(file_get_contents($image))->save(public_path($path .$image->hashName()));

        $image = $image->hashName();
        return $image;
    }

}
