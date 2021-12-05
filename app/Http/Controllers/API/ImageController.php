<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Image;
use Validator;
use Illuminate\Http\Request;
use App\Traits\backendTraits;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ImageResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\BaseController as BaseController;

class ImageController extends BaseController
{
    use backendTraits;

    public function saveImage(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required',
            ], [
               'image.required'  => __('user.imageReq'),
            ]
        );
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $image = new Image;
        //Images
        $image->image          = $this->imageInterve($request->image, 'uploads/images/');
        $image->user_id        = Auth::user()->id;
        $image->type           = 1;
        $image->save();
        $message = __("user.uploadImageSuccess");
        return $this->returnData('','',$message);
    }

    public  function allImages(){
        $images = ImageResource::collection(Image::where('type',1)->orderBy('id','DESC')->paginate(10));
        return $this->returnData('true', $images);
    }


    public function userImage($id){
        $user = User::find($id);
        if($user){
            $images = ImageResource::collection(Image::where('user_id',$user->id)->where('type',1)->orderBy('id','DESC')->paginate(10));
            return $this->returnData('true', $images);
        }else{
            return $this->returnError('E001', __('user.false_info'));
        }
    }

    public function getImageId($id){
        $image = Image::find($id);
        $image = Image::where('type',1)->where('id',$id)->first();
        if($image){
            $image = new ImageResource(Image::where('type',1)->where('id',$id)->first());
            return $this->returnData('true', $image);
        }else{
            return $this->returnError('E001', __('user.false_info'));
        }
    }

    public function deleteImage($id){
        $userId  = Auth::user()->id;
        $image = Image::where('type',1)->where('user_id',$userId)->find($id);
        if($image){
            $img   = Image::find($image->id)->image;
            Storage::disk('uploads')->delete('images/' . $img);
            $image->delete();
            return $this->returnData('success', __('user.delimage'));
        }else{
            return $this->returnError('E001', __('user.false_info'));
        }
    }
}
