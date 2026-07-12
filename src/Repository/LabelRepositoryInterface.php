<?php

namespace App\Repository;

use App\Entity\LabelTemplate;

interface LabelRepositoryInterface {

    public function findOneById(int $id): ?LabelTemplate;

    /**
     * @return LabelTemplate[]
     */
    public function findAll(): array;

    public function persist(LabelTemplate $label): void;

    public function remove(LabelTemplate $label): void;
}