<?php


namespace App\Services;


use Illuminate\Database\Eloquent\Model;

class AppService
{
    protected $model;

    protected $guard = null;

    protected $debug;

    public function __construct(Model $model)
    {
        $this->model     = $model;
        $this->debug    = config('app.debug', false);
    }

    protected function sendSuccess($data = null, $message = null, $statusCode = null): object
    {
        return (new ResponseService($data))->success($message, $statusCode);
    }

    /**
     * Send Response Error
     *
     * @param null $data
     * @param null $message
     * @param null $statusCode
     * @return  object
     */
    protected function sendError($data = null, $message = null, $statusCode = null): object
    {
        return (new ResponseService($data))->error($message, $statusCode);
    }
}
