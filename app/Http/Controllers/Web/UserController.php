<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService ;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function index(){
       
        (object)$data['rules'] = Rule::all();
        $data['users'] = $this->userService->getUsers()->with('rule')->paginate(1)->appends(request()->query());
        return view("user.index",$data);
    }
    public function show(){}
    public function create(){}
    public function store(Request $request){}
    public function distroy($id){
        $distroyUser = User::find($id)->delete();
        if(!$distroyUser){
            return redirect()->back()->withErrors("لم يتم ايقاف الحساب");
        }
        return redirect()->back()->with("succ","تم ايقاف الحساب");

    }
    public function restore($id){
        $restoreUser = User::onlyTrashed()->find($id)->restore();
        if(!$restoreUser){
            return redirect()->back()->withErrors("لم يتم تنشيط الحساب ");
        }
        return redirect()->back()->with("succ","تم تنشيط الحساب");

    }
}
