<?php

namespace App\Validator;

use function PHPUnit\Framework\assertNotEmpty;

class AbstractValidator
{
    public function checkTextElement($data, $element): ?bool
    {
        $verifyElement = $data[$element];

        if(is_string($verifyElement) && !empty($verifyElement)) {
            return true;
        }

        return false;
    }

    public function checkIntegerElement()
    {

    }
}