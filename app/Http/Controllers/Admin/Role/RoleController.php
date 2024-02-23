<?php

namespace App\Http\Controllers\Admin\Role;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;

class RoleController extends Controller
{
    

    /**
     * Create/update role
     * 
     * @param \App\Http\Requests\Admin\RoleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(RoleRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                $id = $request->has('id') && $request->id ? $request->id : null;
                Role::updateOrCreate([
                    'id' => $id
                ],[
                    'name' => $request->name,
                    'slug' => sluggify($request->name),
                ]);
            });

            return response()->json(api_response('Role successfully saved.'), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while saving role. Please try again!', 'failed', 'Failed', 400), 400);
        }
    }

    /**
     * Attach role and permission
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAttachRoleAndPermission(Request $request)
    {
        $role = Role::find($request->id);

        $role->permissions()->sync($request->permissions);

        return response()->json(api_response('Permissions have been attached to ' . $role->name . ' Role.'));
    }
}
