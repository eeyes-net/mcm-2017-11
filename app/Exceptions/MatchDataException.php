<?php

namespace App\Exceptions;

use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MatchDataException extends Exception
{
    protected $errors;
    protected $spreadsheet;

    public function __construct(array $errors = [], Spreadsheet $spreadsheet = null)
    {
        $this->errors = $errors;
        $this->spreadsheet = $spreadsheet;
        parent::__construct('Match exports to spreadsheet error.');
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }
}
