<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Image;
use Validator;
use Illuminate\Http\Request;
use App\Traits\backendTraits;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StikerResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\BaseController as BaseController;

class StikerController extends BaseController
{
    use backendTraits;

    public function saveStiker(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'stiker' => 'required',
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
        $image->image          = $this->imageInterve($request->stiker, 'uploads/stikers/');
        $image->user_id        = Auth::user()->id;
        $image->type           = 2;
        $image->save();
        $message = __("user.uploadImageSuccess");

        return $this->returnData('', "",$message);
    }


    public  function allStikers(){
        $stikers = StikerResource::collection(Image::where('type',2)->orderBy('id','DESC')->paginate(10));
        return $this->returnData('true', $stikers);
    }

    public function userStiker($id){
        $user = User::find($id);
        if($user){
            $images = StikerResource::collection(Image::where('user_id',$user->id)->where('type',2)->orderBy('id','DESC')->paginate(10));
            return $this->returnData('true', $images);
        }else{
            return $this->returnError('E001', __('user.false_info'));
        }
    }


    public function getStikerId($id){
        $image = Image::find($id);
        $image = Image::where('type',2)->where('id',$id)->first();
        if($image){
            $image = new StikerResource(Image::where('type',2)->where('id',$id)->first());
            return $this->returnData('true', $image);
        }else{
            return $this->returnError('E001', __('user.false_info'));
        }
    }


    public function deleteStiker($id){
        $userId  = Auth::user()->id;
        $image = Image::where('type',2)->where('user_id',$userId)->find($id);
        if($image){
            $img   = Image::find($image->id)->image;
            Storage::disk('uploads')->delete('stikers/' . $img);
            $image->delete();
            return $this->returnData('success', __('user.delimage'));
        }else{
            return $this->returnError('E001', __('user.false_info'));
        }
    }
}
