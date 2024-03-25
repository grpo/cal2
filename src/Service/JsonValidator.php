<?php

namespace App\Service;

class JsonValidator
{
    public function validate(string $requestContent): string
    {
        if (!json_validate($requestContent)) {
            throw new \InvalidJsonException();
        }
        return  $requestContent;
    }
}
