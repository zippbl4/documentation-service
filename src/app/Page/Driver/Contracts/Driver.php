<?php

namespace App\Page\Driver\Contracts;

use App\Page\Driver\DTO\DriverRequestDTO;
use App\Page\Driver\DTO\DriverResponseDTO;
use App\Page\Driver\Exceptions\DriverException;

interface Driver
{
    /**
     * @param DriverRequestDTO $request
     * @return DriverResponseDTO
     * @throws DriverException
     */
    public function showPage(DriverRequestDTO $request): DriverResponseDTO;
}
