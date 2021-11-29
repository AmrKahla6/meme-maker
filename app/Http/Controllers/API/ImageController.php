<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Image;
use Validator;
use Illuminate\Http\Request;
use App\Traits\backendTraits;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $image->save();
        $message = __("user.uploadImageSuccess");
        return $this->returnData('true', $message);
    }

    public  function allImages(){
        $images = Image::select('id')->get();
        return $this->returnData('true', $images);
    }


    public function userImage($id){
        $user = User::find($id);
        if($user){
            $images = Image::select('id')->where('user_id',$user->id)->get();
            return $this->returnData('true', $images);
        }else{
            return $this->returnError('E001', __('user.false_info'));
        }
    }
}
