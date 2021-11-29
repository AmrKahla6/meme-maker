<?php

namespace App\Http\Controllers\API;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\BaseController as BaseController;

class AuthController extends BaseController
{

    /**
     * User Registration
    */

    public function register(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'email'                => 'required|unique:users',
                'password'             => 'required|min:6',
            ], [
                'email.required'       => __("user.reqEmail"),
                'email.unique'         => __("user.uniqueEmail"),
                'password.required'    => __("user.reqPassword"),
            ]
        );
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $data = $request->except(['password']);
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);
        $token = NULL;
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $message = __("user.registSuccess");

        $response = [
            'id'        => $user->id,
            'email'     => $user->email,
            'token'     => $token,
        ];

        // return $this->returnData('msg',$token,$message);
        return $this->returnData('token', $response,__("user.registSuccess"));
    }



    /**
     * Driver Login
     */

    public function login(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'email'          => 'required',
            'password'       => 'required',
        ], [
            'email.required'        => __("user.reqEmail"),
            'password.required'     => __("user.reqPassword"),
        ]);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            //login

            $credentials = $request->only(['email', 'password']);
            $token = auth('api')->attempt($credentials);  //generate token

            if (!$token)
                return $this->returnError('E001', __('user.false_info'));

            $user = auth('api')->user();
            $user ->api_token = $token;

            $response = [
                'id'        => $user->id,
                'email'     => $user->email,
                'token'     => $token,
            ];
            //return token
            return $this->returnData('user', $response,__('user.login'));  //return json response
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


     /**
     * User Logout
     */

    public function logout(Request $request)
    {
         $token = $request->header('token');
        if($token){
            try {
                JWTAuth::setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this->returnError('', __('user.wrangs'));
            }
            return $this->returnSuccessMessage(__('user.logout'));
        }else{
            return $this->returnError('',__('user.wrangs'));
        }
    }


  /**
  * User forgetpassword process By email
  */
 public function forgetpassword(Request $request)
 {
     $user = User::where('email', $request->email)->first();
     if (!$user) {
         $errormessage = __('user.wrang_email');
         return $this -> returnError('',$errormessage);
    } else {
         $randomcode        = substr(str_shuffle("0123456789"), 0, 4);
         $user->forgetcode  = $randomcode;
         $user->save();
         $successmessage = __('user.sent_email');
         return $this->returnData('code', $user->forgetcode, $successmessage);
     }
 }


  /**
  * User Active code for forget password
  */

  public function activcode(Request $request)
  {
      $user = User::where('email', $request->email)->where('forgetcode', $request->forgetcode)->first();
      if ($user) {
          $successmessage = __('user.active_success');
          return $this->returnData('success', "", $successmessage);
      } else {
          $errormessage = __('user.wrang_code');
          return $this -> returnError('',$errormessage);
      }
  }


    /**
   * User Chanage Password
   */
  public function rechangepass(Request $request)
  {
      $validator = Validator::make(
          $request->all(),
          [
              'new_password'    => 'required|min:6',
          ],[
              'new_password.required' => __('user.reqPassword'),
              'new_password.min'      => __('user.minPassword'),
          ],
      );

      if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

      $member = User::where('email', $request->email)->first();
      if ($member) {
          $member->password = Hash::make($request->new_password);
          $member->save();
          $errormessage = __('user.new_pass');
          return $this->returnData('msg', $errormessage);
      } else {
          $errormessage = __('user.false_info');
          return $this -> returnError('error',$errormessage);
      }
  }
}
