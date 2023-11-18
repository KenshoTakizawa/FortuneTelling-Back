<?php


namespace App\Http\Controllers\Users\User\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use RuntimeException;

class GetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request, User $user): JsonResponse
    {
        try {
            $userWithToken = $this->getUserByToken($request->bearerToken());
        } catch (RuntimeException $e) {
            throw new RuntimeException($e, 'user cannot be found.');
        }

        if ($userWithToken->id !== $user->id) {
            throw new RuntimeException('user does not match.');
        }

        if (!UserProfile::existsForUserId($user->id)) {
            throw new RuntimeException('this users profile does not exists.');
        }

        $userProfile = UserProfile::where('user_id', $user->id)->first();

        return response()->json([
            'message' => 'user',
            'user' => $user,
            'user_profile' => $userProfile,
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
