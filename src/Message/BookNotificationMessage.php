<?php
namespace App\Message;

class BookNotificationMessage
{
    private $bookId;


    public function __construct(int $bookId)
    {
        $this->bookId = $bookId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }
}
