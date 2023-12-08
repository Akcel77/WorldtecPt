<?php

namespace App\Service;

use App\Repository\OptionRepository;

class OptionService
{

    public function __construct(
        private OptionRepository $optionRepository
    )
    {
    }

    public function findAll(): array
    {
        return $this->optionRepository->findAllForTwig();
    }

    public function getValue(string $name): string|int|bool|null|float
    {
        return $this->optionRepository->getValue($name);
    }
}