<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\WebsiteMail;
use Hash;
use Auth;

class WebsiteController extends Controller
{
    public function index(){
        return view('home');
    }

    public function dashboard(){
        return view('dashboard');
    }

    public function login(){
        return view('login');
    }

    public function login_submit(Request $request){
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'Active'
        ];

        if(Auth::attempt($credentials)){
            return redirect()->route('dashboard');
        }else{
            return redirect()->route('login');
        }
    }

    public function logout(){
        Auth::guard('web')->logout();

        return redirect()->route('login');
    }

    public function register(){
        return view('register');
    }

    public function register_submit(Request $request){

      $token = hash('sha256', time());

      $user = new User();
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = Hash::make($request->password);
      $user->status = 'Pending';
      $user->token = $token;
      $user->save();

      $verification_link = url('register/verify/'.$token.'/'.$request->email);
      $subject = 'Registration Confirmation';
      $message = 'Please click the link to verify your email <br> <a href="'.$verification_link.'">Click here</a>';

      \Mail::to($request->email)->send(new WebsiteMail($subject, $message));

      echo 'Email is sent';

    }

    public function register_verify($token, $email){
        $user =  User::where('token', $token)->where('email', $email)->first();
        if(!$user){
            return redirect()->route('login');
        }

        $user->status = 'Active';
        $user->token = '';
        $user->update();


        echo 'Registration verification is successfull';
    }

    public function forget_password(){
        return view('forget_password');
    }
}
