<?php

namespace App\Http\Controllers\Admin\Login;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    

    public function getLogin(Request $request)
    {
        $request->session()->forget('admin_session');

        return view('admin.login');
    }

    public function fakeLogin(Request $request)
    {
        return redirect()->route('admin.get.secure.login');
        // $request->session()->forget('admin_session');
        
        // return view('admin.fakelogin');
    }

    public function postLogin(Request $request, AdminUser $adminUser)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $findUser = $adminUser->where('username', $request->username)->first();

        if ($findUser) {
            if (Hash::check($request->password, $findUser->password)) {
                $request->session()->put('admin_session', $findUser);
                return redirect()->route('admin.dashboard.listing');
            }

        }


        return redirect()->back()->withErrors(['msg' => 'Cannot login! Invalid username or password!']);

    }

    public function getLogout(Request $request)
    {
        $request->session()->forget('admin_session');
        return redirect()->route('admin.get.secure.login');
    }
}
