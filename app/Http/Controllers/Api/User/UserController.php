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
            return $this->responseBody(false, "Authorization error", 500);
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

            return $this->responseBody(message: 'User Updated Successfully.');

        } catch (\Exception $e) {
            return $this->responseBody(false, $e->getMessage(), 500);
        }
    }

    public function delete()
    {
        auth()->user()->delete();

        return response(null, 204);
    }

}
