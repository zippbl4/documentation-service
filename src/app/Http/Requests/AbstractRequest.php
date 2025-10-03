<?php

namespace App\Http\Requests;

use App\ObjectMapper\Contracts\JsonDeserializerInterface;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    private JsonDeserializerInterface $deserializer;

    public function setDeserializer(JsonDeserializerInterface $deserializer): void
    {
        $this->deserializer = $deserializer;
    }

    public function deserializer(): JsonDeserializerInterface
    {
        return $this->deserializer;
    }
}
