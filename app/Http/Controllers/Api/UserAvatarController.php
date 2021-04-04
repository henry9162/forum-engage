<?php

namespace App\Http\Controllers\Api;

use App\Picture;
use App\BlobApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UserAvatarController extends Controller
{
    /**
     * Store a new user avatar.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'image' => ['required']
        ]);
        
        $this->addImage(request('image'), auth()->user());

        //Storage::disk('public')->delete(auth()->user()->getOriginal('avatar_path'));
        // auth()->user()->update([
        //     'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        // ]);

        return response([], 204);
    }

    protected function addImage($image, $user)
    {
        $this->deleteExistingProfileImages($user);
        $blobImage = substr($image, 23);
        $blobApiClass = new BlobApi($blobImage);
        $blobApiClass->get_image_url();
        $image_url = $blobApiClass->imageUrl;

        Picture::create([
            'blob_id' => $image_url['data']['remoteImageId'],
            'url' => $image_url['data']['imageURL'],
            'pictureable_id' => $user->id,
            'pictureable_type' => "APP\User"
        ]);
    }

    protected function deleteExistingProfileImages($user)
    {
        if (!count($user->pictures)){ 
            return; 
        } else {
            foreach ($user->pictures as $picture){
                $remoteImageId = $picture->blob_id;
                $blobApiClass = new BlobApi(null, $remoteImageId);
                $blobApiClass->delete_image_url();
                Picture::where('blob_id', $remoteImageId)->delete();
            }
        }
    }
}
