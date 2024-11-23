<?php

namespace App\Http\Controllers\api\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    use GeneralTrait;
  
    public function index()
    {
        try {
            $admins = User::paginate();
            return $this->successResponse($admins, 'all Admins recived successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }


    public function store(AdminStoreRequest $request)
    {
        try {
            $user = User::create($request->validated());
            return $this->successResponse($user, 'Admin careateded successfully.',201);
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }


    public function show(string $id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $$request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|string',
        ]);
        try {

            $user = User::find($id);

            if ($user) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                ]);
                return response()->json([
                    'seccess' => true,
                    'message' => 'admin updated',
                    'admin' => $user
                ]);
            } else
                response(401);
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
