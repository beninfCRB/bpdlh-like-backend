<?php


namespace App\Services;


class ResponseService
{
    private $data;

    private $message;

    private $success;

    public function __construct($data = null)
    {
        $this->data     = $data;
    }

    public function success($message = null, $responseCode = null): object
    {
        $message = (empty($message)) ? 'success' : $message;

        // set the message
        $this->setMessage($message);

        // set response code
        $this->setResponseCode($responseCode);

        $this->success = true;

        return (object) $this->responseWrapper();
    }

    public function error($message = null, $responseCode = null): object
    {
        $message = (empty($message)) ? 'error' : $message;

        // set the message
        $this->setMessage($message);

        // set response code
        $this->setResponseCode($responseCode);

        $this->success = false;

        return (object) $this->responseWrapper();
    }

    private function responseWrapper(): array
    {
        // handle empty data
        $data = (empty($this->data)) ? null : $this->data;

        return [
            'code'      => http_response_code(),
            'success'   => $this->success,
            'message'   => $this->message,
            'data'      => $data
        ];
    }

    private function setMessage($message): void
    {
        // check if message constructed in array format (multiple message)
        if (is_array($message)) {
            $extract = array_values($message);
            $this->message = $extract[0];
        } else {
            $this->message = $message;
        }
    }

    private function setResponseCode($responseCode): void
    {
        if (!empty($responseCode) && is_numeric($responseCode)) {
            http_response_code($responseCode);
        }
    }
}
