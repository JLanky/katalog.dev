<?php

class Authors
{
    public $Db;
    public $id;
    public $author;

    public static function getAllAuthors($db)
    {
        $result = $db->getOneField('SELECT * FROM books
            LEFT JOIN books_authors ON books_authors.book_id = books.book_id
            LEFT JOIN books_genres ON  books_genres.book_id = books.book_id
            LEFT JOIN authors ON authors.author_id = books_authors.author_id
            LEFT JOIN genres ON  genres.id = books_genres.genre_id');

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

            $this->db->addField($query);

            print "<center class=\"t2\">Автор добавлен успешно</center> ";
        } else {
            $error = '';
            $error .= "Поле автор не заполнено или заполнено неверно<br>";

            print "<center class=\"t\">$error</center> ";
        }
    }
    public function deleteAuthor($data){
        if (!empty($data) && !empty($data['author'])) {
            $author_id = $data['author_id'];
            $query = "DELETE FROM `authors` WHERE author_id=". $author_id;

            $this->db->addField($query);

            print "<center class=\"t2\">Автор удален успешно.</center> ";
        } else {
            print'ID указано неверно. Повторите попытку';
        }
    }
}
