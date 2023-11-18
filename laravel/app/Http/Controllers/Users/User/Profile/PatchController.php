<?php


namespace App\Http\Controllers\Users\User\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\User\Profile\PatchControllerRequest;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Date;
use Laravel\Sanctum\PersonalAccessToken;
use RuntimeException;

class PatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @throws Exception
     */
    public function __invoke(PatchControllerRequest $request, User $user): JsonResponse
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

        if (!$userProfile) {
            throw new RuntimeException('this users profile does not exists.');
        }

        if ($request->has('age')) {
            $userProfile->age = $request->get('age');
        }

        if ($request->has('sex')) {
            $userProfile->sex = $request->get('sex');
        }

        if ($request->has('blood_type')) {
            $userProfile->blood_type = $request->get('blood_type');
        }

        if ($request->has('birthday')) {
            $userProfile->birthday = $request->get('birthday');
        }

        $userProfile->save();

        return response()->json([
            'message' => 'user profile updated successfully',
            'user_profile' => $userProfile
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
