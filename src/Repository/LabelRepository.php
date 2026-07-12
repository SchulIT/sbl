<?php

namespace App\Repository;

use App\Entity\LabelTemplate;

class LabelRepository extends AbstractRepository implements LabelRepositoryInterface {

    public function findOneById(int $id): ?LabelTemplate {
        return $this->em->getRepository(LabelTemplate::class)->findOneBy([
            'id' => $id
        ]);
    }

    public function findAll(): array {
        return $this->em->getrepository(LabelTemplate::class)->findBy([], [
            'name' => 'asc'
        ]);
    }

    public function persist(LabelTemplate $label): void {
        $this->em->persist($label);
        $this->em->flush();
    }

    public function remove(LabelTemplate $label): void {
        $this->em->remove($label);
        $this->em->flush();
    }
}