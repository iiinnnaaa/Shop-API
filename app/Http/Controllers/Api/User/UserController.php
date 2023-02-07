<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $id)
    {
        if ($id->id == auth()->id()) {
            return new UserResource($id);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Authorization error"
            ]);
        }
    }

    public function update(UserUpdateRequest $request)
    {
        $filePath = null;
        if ($request->hasFile('image')) {
            $uid = auth()->id();
            $fileName = date("Y-m-d-H-i-s") . '_' . $request->image->getClientOriginalName();
            $filePath = $request->image->storeAs("/files/{$uid}/images", $fileName, 'public');
        }

        try {
            auth()->user()->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'image' => $filePath,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Updated Successfully',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 201);
        }
    }

    public function delete()
    {
        auth()->user()->delete();

        return response(null, 204);
    }


}
