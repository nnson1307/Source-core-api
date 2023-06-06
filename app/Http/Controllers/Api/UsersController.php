<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use GuzzleHttp\Client;

class UsersController extends Controller
{
    protected $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    /**
     *
     * Get list user
     */
    public function index(Request $request)
    {
        try {
            //List user
            $listUser = $this->users->getList($request->all());

            return UserResource::collection($listUser)->additional(['status' => Response::HTTP_OK]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                "_errors" => $e->getMessage()
            ]);
        }
    }

    /**
     * Create user
     */
    public function store(StoreRequest $request)
    {
        try {
            $user = $this->users;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);

            $user->save();

            return (new UserResource($user))->additional(['status' => Response::HTTP_OK]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                "_errors" => $e->getMessage()
            ]);
        }
    }

    /**
     * Get info user
     */
    public function show(string $id)
    {
        try {
            return (new UserResource($this->users->find($id)))->additional(['status' => Response::HTTP_OK]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                "_errors" => $e->getMessage()
            ]);
        }
    }

    /**
     * Update user
     */
    public function update(UpdateRequest $request, string $id)
    {
        try {
            $user = $this->users->find($id);
            $user->name = $request->name;
            $user->email = $request->email;

            $user->save();

            return (new UserResource($user))->additional(['status' => Response::HTTP_OK]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                "_errors" => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete user
     */
    public function destroy(string $id)
    {
        try {
            $this->users->find($id)->delete();

            return response()->json([
                'status' => Response::HTTP_OK
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                "_errors" => $e->getMessage()
            ]);
        }
    }
}
