<?php

class Authors
{
    public static function getAllAuthors($db)
    {
            $id = $db->getOneField('SELECT * FROM books
            LEFT JOIN books_authors ON books_authors.book_id = books.book_id
            LEFT JOIN books_genres ON  books_genres.book_id = books.book_id
            LEFT JOIN authors ON authors.author_id = books_authors.author_id
            LEFT JOIN genres ON  genres.id = books_genres.genre_id');

        $result = array();
        for ($i = 0; $i < count($id); $i++){
            $result[] = new Authors($db, $id[$i]);
        }
        return $result;
    }

    public function __construct($Db, $id = 0)
    {
        $author = $Db->getOne('SELECT * FROM authors WHERE author_id=' . $id);
        $this->Db = $Db;
        $this->id = $id;
        $this->author = $author['author'];
    }


    public function deleteAuthor($id)
    {
        $author_id = (int)$id;
        if (!empty($id)) {
            $del_author = $this->Db->query('DELETE FROM `authors` WHERE author_id="' . $author_id . '"');
            $del_authors_books = $this->Db->query('DELETE FROM `books_authors` WHERE author_id="' . $author_id . '"');
            print "<center class=\"t2\">Автор удален успешно.</center> ";
        } else {
            print'ID указано неверно. Повторите попытку';
        }
    }

    public function getCurrentAuthorBooks()
    {
        $return = array();

        $id = $this->Db->getOneField('book_id', 'SELECT * FROM books_authors WHERE author_id=' . $this->id);


        for ($i = 0; $i < count($id); $i++) {
            $return[] = new Books($this->Db, $id[$i]);
        }
        return $return;

    }

    public function addAuthor($data)
    {

        if (!empty($data) && !empty($data['author'])) {

            $author = $data['author'];

            $sqlauthors = "INSERT INTO `test`.`authors` (`author`) VALUES ('$author');";

            $result1 = $this->Db->query($sqlauthors);

            print "<center class=\"t2\">Автор добавлен успешно</center> ";
        } else {
            $error = '';
            $error .= "Поле автор не заполнено или заполнено неверно<br>";

            print "<center class=\"t\">$error</center> ";
        }
    }


}
