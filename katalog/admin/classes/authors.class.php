<?php

class Authors
{
    public $Db;
    public $id;
    public $author;

    public function getAllAuthors($db)
    {
        $result = $db->getOneField('SELECT * FROM authors');

        return $result;
    }
    public function getAuthorsList($db){
        $query = 'SELECT * FROM authors';
        $result = $db->getOneField($query);
        return $result;
    }

    public function __construct($Db)
    {
        $this->db = $Db;
    }

    public function getCurrentAuthorBooks()
    {
        $query = 'SELECT * FROM books_authors
          LEFT JOIN books ON  books.book_id = books_authors.book_id
          LEFT JOIN authors ON authors.author_id = books_authors.author_id
          WHERE books_authors.author_id=' . $this->id;

        $queryResult = $this->db->getAll($query);
        return $queryResult;
    }

    public function getAuthor($id)
    {
        $author = $this->db->getOne('SELECT * FROM books_authors
          LEFT JOIN books ON  books.book_id = books_authors.book_id
          LEFT JOIN authors ON authors.author_id = books_authors.author_id
          WHERE books_authors.author_id=?', $id);
        if (!empty($author)) {
            $this->id = $id;
        }
        $this->author = $author;
    }

    function getAuthorName()
    {
        return $this->author['author'];
    }

    public function addAuthor($data){
        if (!empty($data) && !empty($data['author'])) {

            $author = $data['author'];

            $query = "INSERT INTO `test`.`authors` (`author`) VALUES ('$author');";

            $this->db->executeQuery($query);

           return 'Автор добавлен успешно';
        } else {
            $error = '';
            $error .= "Поле автор не заполнено или заполнено неверно<br>";

            return $error;
        }
    }
    public function deleteAuthor($author_id){
        if (!empty($author_id)) {
            $query = "DELETE FROM `authors` WHERE author_id=". $author_id;
            $this->db->executeQuery($query);

            return 'Автор удален успешно.';
        } else {
             return 'ID указано неверно. Повторите попытку';
        }
    }
}
