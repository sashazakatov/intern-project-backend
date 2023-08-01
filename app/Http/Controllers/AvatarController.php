<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function getStandardAvatars()
    {
        $avatarPath = 'storage/standard_avatars';
        $avatarFiles = Storage::files($avatarPath);

        $avatars = [];
        foreach ($avatarFiles as $file) {
            $relativePath = str_replace('public/', '', $file);
            $url = asset($relativePath);
            $fileName = pathinfo($file, PATHINFO_BASENAME);
            $avatars[] = [
                'url' => $url,
                'file_name' => $fileName,
            ];
        }
        return response()->json($avatars);
    }
}