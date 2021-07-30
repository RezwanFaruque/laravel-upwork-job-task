<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendRegisterLink;
use App\Mail\SendPinAfterRegistration;
use Illuminate\Support\Facades\Session;

class UserAuthController extends Controller
{
    

    // handle login function for admin and user
    public function login(Request $request){

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6'
        ]);


        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){

            $accessToken = auth()->user()->createToken('authToken')->accessToken;


            if(auth()->user()->user_role == 'admin'){
                $data = [
                    'status' => 'success',
                    'message' => 'You are loged in as an admin',
                    'user' => auth()->user(),
                    'token' => $accessToken,
                ];
            }else{
                $data = [
                    'status' => 'success',
                    'message' => 'You are logged in as a user',
                    'user' => auth()->user(),
                    'token' => $accessToken,
                ];
            }


            return response()->json($data);
        }else{

            $data = [
                'status' => 'error',
                'message' => 'login Unsuccessfull',
               
            ];

            return response()->json($data);
        }
    }


    // send register link to user for register 
    public function sendRegisterInvitation(Request $request){

        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $emailto = $request->email;

        if($emailto){
            Mail::to($emailto)->send(new SendRegisterLink);

            $data = [
                'status' => 'success',
                'message'=> 'Email Sent successfully',
                'data' => 'Email Send To '.$emailto,
            ];

        }else{
            $data = [
                'status' => 'Failed',
                'message' => 'Please Provide User email'
            ];
        }

        return response()->json($data);
    }

    public function register(Request $request){

        $request->validate([

            
            'user_name' => 'required|min:4|max:20',
            'email' => 'email|unique:users',
            'password' => 'required|min:6',
           

        ]);


        $user = new User();

        

        $user->user_name = $request->user_name;

        $user->email = $request->email;

        $user->password = Hash::make($request->password);
        
        // $user->register_at = $request->register_at;

        $user->save();

        if($user->save()){
            $pin = rand(100000,999999);
            $request->session()->put('PIN', $pin);
            Mail::to($user->email)->send(new SendPinAfterRegistration($pin));

            $data = [
                'status' => 'success',
                'message' => '6 Digit Pin send to your email Please check and then confirm it'
            ];
        }else{
            $data =[
                'status' => 'error',
                'message' => 'registration failed'
            ];
        }

        return response()->json($data);
        

    }


    // confirm pin number 

    public function confrimPin(Request $request){

        $pin = $request->session()->get('PIN');

       $inputpin = $request->pin;

       if($inputpin == $pin){
           $data = [
               'status'  => 'success',
               'message' => 'Pin match You are registered'
           ];
       }else{
           $data = [
             'status' => 'error',
             'message' => 'Pin does not match please put right one',
           ];
       }


       return response()->json($data);
    }



    // update user profile information
    public function updateProfile(Request $request){

        $id = Auth::user()->id;

        $user = User::find($id);

        if($user){
            $user->name = $request->name;

            $user->user_name = $request->user_name;

            $user->email = $request->email;

            if($request->user_role){
                $user->user_role = $request->user_role;

            }else{
                $user->user_role = 'user';

            }

            $user->register_at = $request->register_at;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('assets/images');
                $file->move($path, $filename);
                $user->avater = 'assets/images' . $filename;
            }

            $user->update();


            if($user->update()){
                $data = [
                    'status' => 'success',
                    'message' => 'User Information Updated Successfully',
                ];
            }else{
                $data = [
                    'status' => 'error',
                    'message' => 'User Information Update Failed',
                ];
            }

        }

        return response()->json($data);
    }



}
