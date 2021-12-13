<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Validator;


class AuthController extends Controller
{
    use VerifiesEmails;
    public $successStatus = 200;

    public function register(Request $request){
        
        
        $registrationData = $request->all();

        if($request->hasFile('img_user')){
            $destination_path = 'public/images/users';
            $image = $request->file('img_user');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('img_user')->storeAs($destination_path,$image_name);
            $registrationData['img_user']=$path;
        }
        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns',
            'password' => 'required',            
            'country' => 'required',
            'city' => 'required',
            'phone' => 'required',
        ]); 
        if($validate->fails())
            return response(['message'=> $validate->errors()],400); 
        
        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        $user->sendApiEmailVerificationNotification();
        $success['message'] = 'link verifikasi telah dikirim ke email anda';
         return response([
            'message' => 'Register Success',
            'user' => $user,
        ],200);
        
    }

    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); //membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return invalid input

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'],401); //return eror gagal login

        if(Auth::attempt($loginData)){
            $user = Auth::user();
            
            if($user->email_verified_at !== NULL){ 
                $token = $user->createToken('Authentication Token')->accessToken; //generate token
                return response([
                    'message' => 'Authenticated',
                    'user' => $user,
                    'token_type'=>'Bearer',
                    'access_token' => $token
                ]); //retunr data user dan token dalam bentuk json
            }else{
                return response()->json(['error'=>'Please Verify Email'], 401);
            }
        }

    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);

    }

    public function logout(Request $request){
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
    }

    public function detailUser()
    {
        $user = Auth::user();
        return response([
            'message' => 'Detail',
            'user' => $user
        ]);
    }

    public function update(Request $request, $id) 
    {
        $user = User::find($id); //mencari data product berdasar id
        if(is_null($user)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        } //return message saat data tidak ditemukan

        $updateData = $request->all(); //abil semua input dari api client
        $validate = VAlidator::make($updateData, [
            'email' =>'required|email:rfc,dns',
            'name' => 'required',
            'phone' => 'required',
            'photo' => '',
            'city' => 'required',
            'country' => 'required'
        ]); //rule validasi input

        if($validate->fails()) 
            return response(['message' => $validate->errors()],400); //return error invalid input
        
        
        $user->email = $updateData['email']; //edit nama_produk
        $user->name = $updateData['name'];
        $user->phone = $updateData['phone'];
        $user->city = $updateData['city'];
        $user->country = $updateData['country'];

        if($updateData['picture'] != null) {
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $filename = 'uploads/'.time().'.'.$extension;
            $pathImage = $file->move(public_path("uploads"), $filename);
            $user->picture = url($filename);
        }
      
        if($user->save()) {
            return response([
                'message' => 'Update User Success',
                'data' => $user
            ], 200);
        } //return data yang telah diedit dalam bentuk json


        return response([
            'message' => 'Update User Failed',
            'data' => $user
        ], 400);  //return message saat produk gagat diedit
    }

}
