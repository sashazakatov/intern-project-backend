<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Api\Exception\ApiError;

class AvatarController extends Controller
{
    public function getStandardAvatars()
    {
        $avatars = [
            [
                "url" => "https://res.cloudinary.com/dfevrvgy5/image/upload/v1692721057/storage/standard_avatars/avatar_male01_yrl64g.png", 
                "file_name" => "avatar_male01_yrl64g.png"
            ],
            [
                "url" => "https://res.cloudinary.com/dfevrvgy5/image/upload/v1692721057/storage/standard_avatars/avatar_male02_vdghpm.png", 
                "file_name" => "avatar_male02_vdghpm.png"
            ],
            [
                "url" => "https://res.cloudinary.com/dfevrvgy5/image/upload/v1692721057/storage/standard_avatars/avatar_male03_yyzc5c.png", 
                "file_name" => "avatar_male03_yyzc5c.png"
            ],
            [
                "url" => "https://res.cloudinary.com/dfevrvgy5/image/upload/v1692721057/storage/standard_avatars/avatar_male04_wpnvxk.png", 
                "file_name" => "avatar_male04_wpnvxk.png"
            ],
            [
                "url" => "https://res.cloudinary.com/dfevrvgy5/image/upload/v1692721057/storage/standard_avatars/avatar_male05_monrmu.png", 
                "file_name" => "avatar_male05_monrmu.png"
            ],
            [
                "url" => "https://res.cloudinary.com/dfevrvgy5/image/upload/v1692721056/storage/standard_avatars/avatar_male06_i8lr0g.png", 
                "file_name" => "avatar_male06_i8lr0g.png"
            ],
        ];

        return response()->json($avatars);
    }
}