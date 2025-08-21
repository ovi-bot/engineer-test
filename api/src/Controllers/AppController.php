<?php

namespace App\Controllers;

use App\Http\Exceptions\MethodNotAllowedException;
use App\Services\ResetService;

class AppController extends Controller
{
    /**
     * @throws MethodNotAllowedException
     */
    public function reset(): array
    {
        $this->verifyRequestMethod('DELETE');

        $service = new ResetService();
        $service->resetAll();

        return [
            'message' => 'All data reset successfully.',
        ];
    }
}