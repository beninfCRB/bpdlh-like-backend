<?php

namespace App\Services\Akseslh;

use App\Models\File as FileTable;
use App\Services\FileUploadService;
use App\Models\Video;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class VideoService extends AppService implements AppServiceInterface
{
    protected $fileUploadService;
    protected $fileTable;

    public function __construct(
        Video $model,
        FileUploadService $fileUploadService,
        FileTable $fileTable
    ) {
        parent::__construct($model);
        $this->fileUploadService = $fileUploadService;
        $this->fileTable = $fileTable;
    }

    public function getAll()
    {
        $model = $this->model->query()->withTrashed()->orderBy('created_at', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getPaginated($search = null, $perPage = 15)
    {
        throw new \Exception('Not implemented');
    }

    public function getById($id)
    {
        $result =   $this->model->newQuery()->find($id);

        return $this->sendSuccess($result);
    }

    public function getApiAll($input = null)
    {
        $cacheKey = 'video';

        if (!is_null($input)) {
            $tahapan = strtolower(str_replace(' ', '_', $input));
            $cacheKey .= '_' . $tahapan;
        }

        $result = Cache::remember($cacheKey, now()->addDays(7), function () use ($input) {

            $data  = $this->model->newQuery()
                ->when(!is_null($input), function ($query) use ($input) {
                    $tahapan = strtolower(str_replace(' ', '_', $input));
                    $query->where('title', $tahapan);
                })
                ->orderBy('created_at', 'ASC')
                ->get();

            return $data->transform(function ($items, $key) {
                return [
                    'id'                            => $items->id,
                    'title'                         => $items->title,
                    'description'                   => $items->description,
                    'file'                          => $items->file,
                ];
            });
        });


        return $this->sendSuccess($result);
    }

    public function create($input)
    {
        \DB::beginTransaction();

        try {

            $data = $this->model->newQuery()->create([
                'title'         =>  $input['title'],
                'description'   =>  $input['description'],
                'username'      =>  auth()->user()->id,
            ]);

            if (!empty($input['fileVideo'])) {
                $group = strtolower(str_replace(' ', '_', $input['title']));

                $upload = $this->fileUploadService->handleFile($input['fileVideo'])->saveToDb($group);

                if (!empty($upload)) {
                    $video = $this->fileTable->newQuery()->find($upload->id);
                    $video->update([
                        'fileable_type' => get_class($data),
                        'fileable_id'   => $data->id,
                    ]);
                }
            }

            \DB::commit(); // commit the changes
            if (!empty($input['title'])) {
                $tahapan = strtolower(str_replace(' ', '_', $input['title']));
                Cache::forget('video_' . $tahapan);
            }
            Cache::forget('video');
            return $this->sendSuccess($data);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function update($id, $input)
    {
        $read   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $read->title         =   $input['title'];
            $read->description   =   $input['description'];
            $read->username      =   auth()->user()->id;
            $read->save();

            \DB::commit(); // commit the changes
            if (!empty($input['title'])) {
                $tahapan = strtolower(str_replace(' ', '_', $input['title']));
                Cache::forget('video_' . $tahapan);
            }
            Cache::forget('video');
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
            if (!empty($input['title'])) {
                $tahapan = strtolower(str_replace(' ', '_', $input['title']));
                Cache::forget('video_' . $tahapan);
            }
            Cache::forget('video');
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function restore($id)
    {
        $read   =   $this->model->newQuery()->withTrashed()->find($id);
        try {
            $read->restore();
            \DB::commit(); // commit the changes
            Cache::forget('video');
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }
}
