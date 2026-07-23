<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class BookCopy {

    public const int BARCODE_PADDING = 5;

    use IdTrait;
    use UuidTrait;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'copies')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Book $book;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $canCheckout = true;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private string|null $comment;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime|null $labelPrintedAt = null;

    /**
     * @var Collection<Checkout>
     */
    #[ORM\OneToMany(targetEntity: Checkout::class, mappedBy: 'bookCopy')]
    #[ORM\OrderBy(['id' => 'DESC'])]
    private Collection $checkouts;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
        $this->checkouts = new ArrayCollection();
    }

    public function getBarcodeId(): string {
        return self::computeBarcodeId($this->getId());
    }

    public function getBook(): Book {
        return $this->book;
    }

    public function setBook(Book $book): BookCopy {
        $this->book = $book;
        return $this;
    }

    public function canCheckout(): bool {
        return $this->canCheckout;
    }


    public function setCanCheckout(bool $canCheckout): BookCopy {
        $this->canCheckout = $canCheckout;
        return $this;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function getLabelPrintedAt(): ?DateTime {
        return $this->labelPrintedAt;
    }

    public function setLabelPrintedAt(?DateTime $labelPrintedAt): BookCopy {
        $this->labelPrintedAt = $labelPrintedAt;
        return $this;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function setComment(?string $comment): BookCopy {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return Collection<Checkout>
     */
    public function getCheckouts(): Collection {
        return $this->checkouts;
    }

    public function addCheckout(Checkout $checkout): void {
        $checkout->setBookCopy($this);
        $this->checkouts->add($checkout);
    }

    public function removeCheckout(Checkout $checkout): void {
        $this->checkouts->removeElement($checkout);
    }

    public static function computeBarcodeId(int $id): string {
        return mb_str_pad($id, self::BARCODE_PADDING, '0', STR_PAD_LEFT);
    }
}