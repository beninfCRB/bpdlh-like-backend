<?php


namespace App\Services\Akseslh;


use App\Models\UserAkseslh;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegisterNotification;
use App\Services\EmailPhpService;

class UserAkseslhService extends AppService implements AppServiceInterface
{
    protected $emailPhpService;

    public function __construct(UserAkseslh $model, EmailPhpService $emailPhpService)
    {
        parent::__construct($model);
        $this->emailPhpService = $emailPhpService;
    }

    public function getAll()
    {
        $model = $this->model->query()->whereNull('data_pic_kelompok_masyarakat_id')->with(['master_user_jenis_kelompok.jenis_kelompok_masyarakat:id,jenis_kelompok_masyarakat'])->whereNotIn('role_user', ['maker'])->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                 => $items->id,
                'jenis_kegiatan'     => $items->jenis_kegiatan,
            ];
        });

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $page = null, $perPage = null, $lang = null)
    {
        $result =   $this->switchLang($search, $page, $perPage, $lang);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result =   $this->model->newQuery()->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        // Make default password for first login
        $default_password =
            crypt($data['email'] . Carbon::now()->format('d M Y H:i:s'), $data['email']);

        \DB::beginTransaction();

        try {

            // Insert data to database
            $newData = $this->model->newQuery()->create([
                'email'                             => $data['email'],
                'password'                          => Hash::make($default_password),
                'nama_pic'                          => $data['nama_pic'],
                'role_user'                         => $data['role_user'],
                'status_user'                       => 'ACTIVE',
                'flag'                              => 1,
            ]);

            // Send password default to email
            // Notification::route('mail', $data['email'])->notify(new RegisterNotification($default_password));
            $this->emailPhpService->sendEmail($data['email'], 'Register Notification', $newData, $default_password, null, 'mail.seeder-register-mail');

            \DB::commit(); // commit the changes
            return $this->sendSuccess($newData);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function update($id, $data)
    {
        $read   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $read->email                            = $data['email'];
            $read->nama_pic                         = $data['nama_pic'];
            $read->role_user                        = $data['role_user'];
            $read->status_user                      = $data['status_user'];
            $read->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function delete($id)
    {
        $read   =   $this->model->newQuery()->find($id);

        try {
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }
}
