<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(){
        $this->middleware("guest")->only(['login','index']);
        $this->middleware('auth')->only('logout');
    }

    public function index(){
        return view("auth.login");
    }
    public function login(LoginRequest $request){
        $credentionls = $request->validated();
        if(auth()->attempt($credentionls)){
            return redirect()->route("home");
        }
        return redirect()->route("login")->withErrors('البيانات غير صحيحة');
    }

    public function logout(){
        if(auth()->check()){
            auth()->logout();
            return redirect()->route("login");
        }
        return redirect()->route("home");
        
    }
}
