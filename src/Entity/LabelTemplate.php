<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class LabelTemplate implements Stringable {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $description = null;

    #[ORM\Column(name: '`rows`', type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(1)]
    private int $rows = 8;

    #[ORM\Column(name: '`columns`', type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(1)]
    private int $columns = 3;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\GreaterThanOrEqual(0)]
    private float $topMarginMM = 4;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\GreaterThanOrEqual(0)]
    private float $bottomMarginMM = 4;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\GreaterThanOrEqual(0)]
    private float $leftMarginMM = 1;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\GreaterThanOrEqual(0)]
    private float $rightMarginMM = 1;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\GreaterThanOrEqual(0)]
    private float $cellWidthMM = 70;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\GreaterThanOrEqual(0)]
    private float $cellHeightMM = 36;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\GreaterThanOrEqual(0)]
    private float $cellPaddingMM = 5;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): LabelTemplate {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): LabelTemplate {
        $this->description = $description;
        return $this;
    }

    public function getRows(): int {
        return $this->rows;
    }

    public function setRows(int $rows): LabelTemplate {
        $this->rows = $rows;
        return $this;
    }

    public function getColumns(): int {
        return $this->columns;
    }

    public function setColumns(int $columns): LabelTemplate {
        $this->columns = $columns;
        return $this;
    }

    public function getTopMarginMM(): float {
        return $this->topMarginMM;
    }

    public function setTopMarginMM(float $topMarginMM): LabelTemplate {
        $this->topMarginMM = $topMarginMM;
        return $this;
    }

    public function getBottomMarginMM(): float {
        return $this->bottomMarginMM;
    }

    public function setBottomMarginMM(float $bottomMarginMM): LabelTemplate {
        $this->bottomMarginMM = $bottomMarginMM;
        return $this;
    }

    public function getLeftMarginMM(): float {
        return $this->leftMarginMM;
    }

    public function setLeftMarginMM(float $leftMarginMM): LabelTemplate {
        $this->leftMarginMM = $leftMarginMM;
        return $this;
    }

    public function getRightMarginMM(): float {
        return $this->rightMarginMM;
    }

    public function setRightMarginMM(float $rightMarginMM): LabelTemplate {
        $this->rightMarginMM = $rightMarginMM;
        return $this;
    }

    public function getCellWidthMM(): float {
        return $this->cellWidthMM;
    }

    public function setCellWidthMM(float $cellWidthMM): LabelTemplate {
        $this->cellWidthMM = $cellWidthMM;
        return $this;
    }

    public function getCellHeightMM(): float {
        return $this->cellHeightMM;
    }

    public function setCellHeightMM(float $cellHeightMM): LabelTemplate {
        $this->cellHeightMM = $cellHeightMM;
        return $this;
    }

    public function getCellPaddingMM(): float {
        return $this->cellPaddingMM;
    }

    public function setCellPaddingMM(float $cellPaddingMM): LabelTemplate {
        $this->cellPaddingMM = $cellPaddingMM;
        return $this;
    }

    public function __toString(): string {
        return $this->getName();
    }
}