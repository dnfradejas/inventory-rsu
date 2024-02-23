<?php

namespace App\Http\Controllers\Admin\User;

use Exception;
use App\Models\Role;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Repositories\AdminUserRepository;

class UserController extends Controller
{
    

    /**
     * Display user list
     * 
     * @param \App\Repositories\AdminUserRepository $adminUserRepository
     *
     * @return \Illuminate\View\View
     */
    public function listing(AdminUserRepository $adminUserRepository)
    {
        $view_data = [
            'users' => $adminUserRepository->get(),
        ];
        return view('admin.pages.user.listing', $view_data);
    }

    /**
     * Display form
     *
     * @param int|null $id
     * @return \Illuminate\View\View
     */
    public function displayForm($id = null)
    {
        $roles = Role::get();
        $user = $id ? AdminUser::find($id) : new AdminUser();
        return view('admin.pages.user.form', [
            'roles' => $roles,
            'user' => $user,
            'cardTitle' => $id ? 'Update user' : 'Add new user',
        ]);
    }

    /**
     * Create/Update admin user
     * 
     * @param \App\Http\Requests\Admin\UserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(UserRequest $request)
    {

        try {
            $id = null;
            DB::transaction(function() use (&$id, $request) {
                $data = [
                    'role_id' => $request->role,
                    'fullname' => $request->fullname,
                    'username' => $request->username,
                    'password' => bcrypt($request->password),
                    'status' => $request->status,
                ];
                

                if ($request->has('id') && $request->id) {
                    $id = $request->id;
                    if (!$request->has('password')) {
                        unset($data['password']);
                    }
                }

                AdminUser::updateOrCreate(['id' => $id], $data);
            });

            // logout user if he updated his own password
            $currentSession = session()->get('admin_session');
            if ($currentSession && $id == $currentSession->id && $request->has('password')) {
                $request->session()->forget('admin_session');
                return response()->json(api_response(['url' => route('admin.get.secure.login')]));
            }

            return response()->json(api_response('User successfully saved!'));
        } catch (Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(api_response('Error while saving user!', 'failed', 'Failed', 400), 400);

        }
    }

    /**
     * Delete user
     * 
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id)
    {
        $currentSession = session('admin_session');
        
        if ($currentSession && $currentSession->id != $id) {
            AdminUser::find($id)->delete();
            return response()->json(api_response('User has been deleted!'));
        }

        return response()->json(api_response('Cannot delete currently logged in user!', 'failed', 'Failed', 400), 400);
    }
}
