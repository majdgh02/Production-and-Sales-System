<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function get_my_info(Request $request)
    {
        $user1 = $request->user();
        $user = [
            'id' => $user1->id,
            'name' => $user1->name,
            'age' => $user1->age,
            'role_name' => $user1->role->name,
            'username' => $user1->username,
        ];
        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function create_account(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer|min:0',
            'role' => 'required|string|exists:roles,name',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $rolename = $request->role;
        $role = Role::where('name', $rolename)->first();

        $user = User::create([
            'name' => $request->name,
            'age' => $request->age,
            'username' => $request->username,
            'password' => $request->password,
            'role_id' => $role->id,
        ]);

        return response()->json([
            'message' => 'تم انشاء المستخدم بنجاح',
            'user' => $user
        ],200);
    }

    public function edit_account(Request $request)
    {
        $fields = $request->validate([
            'id' => ['required' , 'integer', 'exists:users,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:0'],
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users')->ignore($request->id),
            ],
            'role' => ['sometimes', 'string'],
        ]);

        $user = User::where('id', $request->id)->first();

        if ($request->has('role')){
            $rolename = $request->role;
            $role = Role::where('name', $rolename)->first();
            $user->role_id = $role->id;
        }

        $user->update($request->except(['role']));

        return response()->json([
            'message' => 'تم تعديل معلومات المستخدم بنجاح',
            'user' => $user
        ],200);
    }

    public function deleteUser(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'You cannot delete your own account through this endpoint.'], 403);
        }

        $user->delete();


        return response()->json([
            'message' => 'تم حذف الحساب.'
        ], 200);
    }

    public function showAccounts(Request $request)
    {
        $users = User::with('role')->get();

        return response()->json([
            'message' => 'Users retrieved successfully.',
            'users' => $users,
        ], 200);
    }
}
