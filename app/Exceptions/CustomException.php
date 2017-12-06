<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    protected $errors = "";

    public function __construct($message = "", $errors = "")
    {
        parent::__construct($message);
        if (!$errors) {
            $errors = $message;
        }
        $this->errors = $errors;
    }

    public function render(Request $request)
    {
        return [
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ];
    }
}
