<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }
    public function updatePassword()
    {
        request()->validate(['password' => 'required|min:8']);
        User::findOrFail(auth()->user()->id)->update(['password' => Hash::make(request()->input('password'))]);
        return redirect()->back()->with('message', 'Password Updated successfully');
    }
    public function update_password()
    {
        return view('update-password');
    }
    public function update_password_handle()
    {
        request()->validate(['password' => 'required|min:8|confirmed']);
        $user=User::findOrFail(auth()->user()->id);
        $user->password= Hash::make(request()->input('password'));
        $user->user_must_change_password=0;
        $user->save();
        return redirect()->back()->with('message', 'Password for '.$user->name.' Updated successfully. You can now click on go to dashboard');
    }
}
