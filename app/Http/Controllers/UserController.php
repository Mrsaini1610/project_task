<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleBase;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   
    public function users_recode()
    {
        $users = User::with('role')->get();
        return response()->json($users);
    }
    public function rolebase_recode()
    {
        $users = RoleBase::all();
        return response()->json($users);
    }
    
    public function store(Request $request)
    {
        // Server-side validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'regex:/^[6-9]\d{9}$/'], // Indian phone no validation
            'description' => 'nullable|string',
            'role_id' => 'required|integer',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // save image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        // Save user data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description,
            'role_ID' => $request->role_id,
            'profile_image' => $imageName ?? null,
        ]);

        return response()->json(['success' => 'User created successfully!', 'user' => $user]);
    }
}
