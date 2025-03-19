<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \App\Traits\AppResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use AppResponse;
    /**
     * Display a listing of the resource.
     */





    public function index()
    {

        if (Auth()->check()) {
            $users = User::latest()->paginate(6);
            return response()->json(['message' => 'All Users', 'users' => $users]);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Auth()->check()) {
            $user = User::find($id);
            return response()->json(['message' => 'User Details', 'user' => $user]);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return $this->errorResponse('Unauthorized', 401);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'address' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Failed', 422, $validator->errors());
        }

        // Find user
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse('User Not Found', 404);
        }

        // Update user details
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'address' => $request->address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'country' => $request->country,
            'state' => $request->state,
        ]);

        return $this->successResponse($user, 'User Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {



        if (Auth()->check()) {
            $user = User::find($id);
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }
            $user->delete();
            return $this->successResponse('User deleted successfully', 204);  // No Content status code 204  for delete operation.
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $users = User::where('first_name', 'like', '%' . $search . '%')
            ->orWhere('last_name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->paginate(5);
        return response()->json(['message' => 'Search Results', 'users' => $users]);
    }










}
