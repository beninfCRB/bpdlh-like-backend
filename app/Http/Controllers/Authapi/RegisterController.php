<?php

namespace App\Http\Controllers\Authapi;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Notifications\RegisterNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Authapi\RegisterRequest;
use App\Models\DataPicKelompokMasyarakat;
use App\Models\UserAkseslh;
use App\Models\UserEksternal;
use App\Services\EmailPhpService;

class RegisterController extends ApiController
{
    protected $emailPhpService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EmailPhpService $emailPhpService)
    {
        $this->middleware('guest');
        $this->emailPhpService = $emailPhpService;
    }

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        // Make default password for first login
        $default_password =
            crypt($request->email_pic . Carbon::now()->format('d M Y H:i:s'), $request->email_pic);

        // Get all input
        $input = $request->all();

        // Begin db transaction
        \DB::beginTransaction();
        try {

            $user = DataPicKelompokMasyarakat::where([
                'email_pic'              => $input['email_pic'],
            ])
                ->orWhere([
                    'nohp_pic' => $input['nohp_pic']
                ])
                ->first();

            if ($user) {
                // Change user status to active
                $user->user_akseslh->password       = Hash::make($default_password);
                $user->user_akseslh->status_user    = 'ACTIVE';
                $user->user_akseslh->save();

                //Send email notification
                // Notification::send($user->user_akseslh, new RegisterNotification($default_password));
                $this->emailPhpService->sendEmail($input['email_pic'], 'Register Notification', $user, $default_password);

                // Create token for user to access dashboard
                $token = $user->user_akseslh->createToken("auth")->plainTextToken;

                // Commit Change
                \DB::commit();
                // Return token to frontend
                return $this->sendSuccess(['token' => $token]);
            } else {

                \DB::rollBack(); // rollback the changes
                return $this->sendError(null, "User not found");
            }
        } catch (\Throwable $th) {

            \DB::rollBack(); // rollback the changes

            // return error
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }
}
