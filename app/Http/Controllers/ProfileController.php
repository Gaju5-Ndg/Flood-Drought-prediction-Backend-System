<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\Auth;
use App\Models\User;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($id)
    {
        $user = User::find($id);
       
        return view('profile.profile',compact('user',));
    }

    public function editprofile(User $user )
    {
        $user = User::all();
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('profile.edit',compact('user','roles','userRole'));
    }

    public function update(User $user, Request $request)
    {   
       
            request()->validate([
                'name' => 'required',
                'email' =>'required',
                'avatar'=>'required',
                'updated_at'=> now()
                
        ]);

        $user->update($request->all());
        return view('profile.profile')->with('profile','Profile updated successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image',
        ]);
  
        $avatarName = time().'.'.$request->avatar->getClientOriginalName();
        $request->avatar->move(public_path('avatars'), $avatarName);
  
        $user = User::user();
        $user->avatar = $avatarName;
        $user->save();
  
        return back()->with('success', 'Avatar updated successfully.');
    }

    public function updatePicture(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Check if the request has a file
        if ($request->hasFile('avatar')) {
            // Get the uploaded file
            $avatar = $request->file('avatar');

            // Generate a unique file name for the uploaded file
            $filename = time() . '_' . $avatar->getClientOriginalName();

            // Move the uploaded file to the desired location (e.g., public/avatars)
            $avatar->move(public_path('avatars'), $filename);

            // Update the user's avatar field in the database
            $user->avatar = $filename;
            $user->save();

            // Redirect back with a success message
            return back()->with('success', 'Profile picture updated successfully.');
        }

        // If no file is uploaded or an error occurs, redirect back with an error message
        return back()->with('error', 'Failed to update profile picture.');
    }
}

