<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * The required message in every API response
     *
     * @var string
     */
    private string $message;

    /**
     * @param string $message
     * @param null $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @param false $json
     */
    public function __construct(string $message, $data = null, $status = 200, $headers = [], $options = 0, $json = false)
    {
        $this->message = $message;

        $apiData = [
            'message' => $this->message,
            'data' => $data
        ];

        parent::__construct($apiData, $status, $headers = [], $options = 0, $json = false);
    }
}
