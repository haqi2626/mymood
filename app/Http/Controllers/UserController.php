<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
            });
        }
        
        $users = $query->with('avatar')->latest()->paginate(10);
        // $users = User::with('avatar')->get();
        $totalUser = User::count();
        return view('users.index', compact('users', 'totalUser'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $avatars = Avatar::all(); // Ambil semua avatar nya
        return view('users.create', compact('avatars'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,admin',
            'avatar_id' => 'nullable|exists:avatars,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar_id' => $request->avatar_id,
        ];

        // Set email_verified_at if the checkbox is checked
        if ($request->has('verify_email')) {
            $userData['email_verified_at'] = Carbon::now();
        }


        User::create($userData);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
            $user->load('avatar');
    return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $avatars = Avatar::all();
        return view('users.edit', compact('user', 'avatars'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|string|in:user,admin',
            'avatar_id' => 'nullable|exists:avatars,id',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'avatar_id' => $request->avatar_id,
        ];

        // Only update password if it's provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Update email verification status
        if ($request->has('verify_email') && !$user->email_verified_at) {
            $userData['email_verified_at'] = Carbon::now();
        } elseif (!$request->has('verify_email') && $user->email_verified_at) {
            $userData['email_verified_at'] = null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar_id) {
                $this->deleteAvatar($user->avatar_id);
            }
            
            $avatarId = $this->handleAvatarUpload($request->file('avatar'));
            $userData['avatar_id'] = $avatarId;
        }

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Delete avatar if exists
        if ($user->avatar_id) {
            $this->deleteAvatar($user->avatar_id);
        }
        
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Handle avatar upload and return avatar ID
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return int
     */
    private function handleAvatarUpload($file)
    {
        // Store the file
        $path = $file->store('avatars', 'public');
        
        // Create avatar record
        $avatar = Avatar::create([
            'avatar' => $path
        ]);
        
        return $avatar->id;
    }

    /**
     * Delete avatar by ID
     *
     * @param  int  $avatarId
     * @return void
     */
    private function deleteAvatar($avatarId)
    {
        $avatar = Avatar::find($avatarId);
        
        if ($avatar) {
            // Delete the file
            Storage::disk('public')->delete($avatar->avatar);
            
            // Delete the record
            $avatar->delete();
        }
    }
}