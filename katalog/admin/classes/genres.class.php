<?php

class Genres
{
    private $db;
    private $id;
    private $genre;

    public static function getAllGenres($Db, $book_id = 0)
    {
        if ($book_id != 0) {
            $id = $Db->executeQuery( 'SELECT * FROM books_genres WHERE book_id=' . $book_id);
        } else {
            $id = $Db->executeQuery( 'SELECT * FROM genres');
        }

        return $id;
    }


    public function __construct($Db, $id = 0)
    {
        $this->db = $Db;
        $this->id = $id;
    }

    public function getGenreBooks()
    {

        $query = 'SELECT * FROM books_genres
          LEFT JOIN genres ON  genres.id = books_genres.genre_id
          LEFT JOIN books ON books.book_id = books_genres.book_id
          WHERE books_genres.genre_id=' . $this->id;

        $result = $this->db->getAll($query);

        return $result;

    }
    public function getGenresList($db){
        $query = 'SELECT * FROM genres';
        $result = $db->getOneField($query);
        return $result;
    }

    public function getGenre($id)
    {

        $genre = $this->db->getOne('SELECT * FROM genres WHERE id=?', $id);
        $this->genre = $genre['genre'];
        return $this->genre;
    }

    public function deleteGenre($id)
    {
        $genre_id = (int)$id;

        if (!empty($id)) {
            $del_genre = $this->db->executeQuery('DELETE FROM `genres` WHERE id="' . $genre_id . '"');
            $del_genres_books = $this->db->executeQuery('DELETE FROM `books_genres` WHERE genre_id="' . $genre_id . '"');
            print "<center class=\"t2\">Данные успешно удалены</center> ";
        } else {
            print'ID жанра задан неверно';
        }
    }

    public function addGenre($data)
    {

        if (!empty($data) && !empty($data['genre'])) {

            $genre = $data['genre'];
            $query = "INSERT INTO `genres` (`genre`) VALUES ('$genre');";
            $this->db->executeQuery($query);

            print "<center class=\"t2\">Данные успешно добавлены</center> ";
        } else {
            $error = '';
            $error .= "Вы не заполнили поле жанр<br>";

            print "<center class=\"t\">$error</center> ";
        }
    }

}
