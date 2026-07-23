<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Checkout {
    use IdTrait;
    use UuidTrait;

    #[ORM\ManyToOne(targetEntity: BookCopy::class, inversedBy: 'checkouts')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private BookCopy $bookCopy;

    #[ORM\ManyToOne(targetEntity: Borrower::class, inversedBy: 'checkouts')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Borrower $borrower = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $start;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $end;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTime $expectedReturnDate = null;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
    }

    public function getBookCopy(): BookCopy {
        return $this->bookCopy;
    }

    public function setBookCopy(BookCopy $bookCopy): Checkout {
        $this->bookCopy = $bookCopy;
        return $this;
    }

    public function getBorrower(): ?Borrower {
        return $this->borrower;
    }

    public function setBorrower(?Borrower $borrower): Checkout {
        $this->borrower = $borrower;
        return $this;
    }

    public function getStart(): DateTime {
        return $this->start;
    }

    public function setStart(DateTime $start): Checkout {
        $this->start = $start;
        return $this;
    }

    public function getEnd(): ?DateTime {
        return $this->end;
    }

    public function setEnd(DateTime $end): Checkout {
        $this->end = $end;
        return $this;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function setComment(?string $comment): Checkout {
        $this->comment = $comment;
        return $this;
    }

    public function getExpectedReturnDate(): ?DateTime {
        return $this->expectedReturnDate;
    }

    public function setExpectedReturnDate(?DateTime $expectedReturnDate): Checkout {
        $this->expectedReturnDate = $expectedReturnDate;
        return $this;
    }

    public function isOverdue(DateTime $today): bool {
        if($this->getExpectedReturnDate() === null) {
            return false;
        }

        return $this->getExpectedReturnDate() < $today;
    }
}