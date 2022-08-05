<?php

namespace App\Http\Resources\V1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 *  This is to set a standard json response object for all resource classes
 *
 */
class BaseApiResource extends JsonResource
{
    /**
     *  Either 0 or 1 indicating whether the request was successful or not
     *
     * @var int
     */
    private int $success = 1;
    /**
     *  An error message if the request was unsuccessful
     *
     * @var string
     */
    private string $message;
    /**
     *  An error message if the request was unsuccessful
     *
     * @var mixed
     */
    private mixed $data;
    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @param mixed $resource
     */
    public function __construct($resource = [])
    {
        $this->data = $resource;
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(
            [
                'success' => $this->success,
                'message' => $this->message ?? null,
                'data' => $this->data ?? null,
                'errors' => $this->errors
            ],
            parent::toArray($request)
        );
    }

    /**
     * @param int $success
     * @return $this
     */
    public function success(int $success)
    {
        $this->success = $success;
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function message(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param array $errors
     * @return $this
     */
    public function errors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }


    public function resource($resource)
    {
        $this->resource = $resource;
        return $this;
    }
}
