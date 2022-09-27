<?php
class Book
{
    protected string $bookName;
    protected string $author;
    protected int $year;

    #region getters
    public function getBookName() { return $this->bookName; }
    public function getAuthor() { return $this->author; }
    public function getYear() { return $this->year; }
    #endregion
    #region setters
    public function setBookName($bookName) { $this->bookName = $bookName; }
    public function setAuthor($author) { $this->author = $author; }
    public function setYear($year) { $this->year = $year; }
    #endregion
}
