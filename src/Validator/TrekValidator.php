<?php

namespace App\Validator;

class TrekValidator extends AbstractValidator
{
    public function verifyDataTrek($data): array
    {
        $element = $this->checkTextElement($data, "name");

        if(!$element) {
            return array("error : " . "name", $data["name"]);
        }

        return $data;
    }
}