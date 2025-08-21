<?php

namespace App\Controllers;

use App\Http\Exceptions\BadRequestException;
use App\Http\Exceptions\MethodNotAllowedException;
use App\Http\Exceptions\UnknownException;
use App\Services\EmployeeService;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeService $employeeService = new EmployeeService()
    )
    {
    }

    /**
     * @throws MethodNotAllowedException
     */
    public function list(): array
    {
        $this->verifyRequestMethod('GET');

        return $this->employeeService->getList();
    }

    /**
     * @throws MethodNotAllowedException
     * @throws BadRequestException|UnknownException
     */
    public function updateEmail(string $employeeId)
    {
        $this->verifyRequestMethod('POST');

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            $data = $_POST;
        }

        $id = is_numeric($employeeId) ? (int)$employeeId : 0;
        $email = isset($data['email']) ? trim((string)$data['email']) : '';

        if (!$id || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestException('Invalid id or email');
        }

        $this->employeeService->updateEmail($id, $email);
    }

}