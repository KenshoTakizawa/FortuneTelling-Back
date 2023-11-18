<?php


namespace App\Http\Controllers\Users\User\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;
use RuntimeException;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @throws Exception
     */
    public function __invoke(PostControllerRequest $request, User $user): JsonResponse
    {
        try {
            $userWithToken = $this->getUserByToken($request->bearerToken());
        } catch (RuntimeException $e) {
            throw new RuntimeException($e, 'user cannot be found.');
        }

        if ($userWithToken->id !== $user->id) {
            throw new RuntimeException('user does not match.');
        }

        if (UserProfile::existsForUserId($user->id)) {
            throw new RuntimeException('this users profile already exists.');
        }

        $userProfile = UserProfile::create([
            'age' => $request->age,
            'sex' => $request->sex,
            'blood_type' => $request->blood_type,
            'birthday' => $request->birthday,
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'user profile created successfully',
            'user' => $userProfile
        ]);
    }

    /**
     * @throws Exception
     */
    private function getUserByToken(string $token): User
    {
        $tokenModel = PersonalAccessToken::findToken($token);

        if ($tokenModel) {
            return $tokenModel->tokenable;
        }

        throw new RuntimeException('user cannot be found.');
    }
}
