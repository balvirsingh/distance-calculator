<?php

namespace App\Interface;

interface HttpInterface
{
    public function requestApi(array $data): array;
}
