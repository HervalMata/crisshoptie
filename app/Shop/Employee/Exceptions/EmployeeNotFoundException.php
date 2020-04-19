<?php


namespace App\Shop\Employee\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct("Empregado não encontrado.");
    }
}
