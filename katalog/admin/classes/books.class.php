<?php

class Books
{
    private $db;
    public $id;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getBook($id)
    {
        $book = $this->db->getOne('SELECT * FROM books
            WHERE books.book_id = ?', $id);
        return $book;
    }

    public function getBookAuthors($bookId)
    {
        $authors = $this->db->getAll('SELECT * FROM authors
LEFT JOIN books_authors ON books_authors.author_id = authors.author_id
LEFT JOIN books ON books.book_id = books_authors.book_id
WHERE books.book_id =' . $bookId);
        return $authors;
    }

    public function getBookGenres($bookId)
    {
        $genres = $this->db->getAll('SELECT * FROM genres
LEFT JOIN books_genres ON books_genres.genre_id = genres.id
LEFT JOIN books ON books.book_id = books_genres.book_id
WHERE books.book_id =' . $bookId);
        return $genres;
    }

    public function getAllBooks()
    {
        $books = $this->db->getAll('SELECT * FROM books');
        foreach ($books as $key => $book) {
            $books[$key]['authors'] = $this->getBookAuthors($book['book_id']);
            $books[$key]['genres'] = $this->getBookGenres($book['book_id']);
        }
        return $books;
    }

    public function isCurrentBookAuthor($id)
    {
        for ($i = 0, $l = count($this->authors); $i < $l; $i++) {
            if ($this->authors[$i]->id == $id) {
                return true;
            }
        }
        return false;
    }

    public function isCurrentBookGenre($id)
    {
        for ($i = 0, $l = count($this->genres); $i < $l; $i++) {
            if ($this->genres[$i]->id == $id) {
                return true;
            }
        }
        return false;
    }

    public function validateBook($data)
    {
        if (!empty($data)) {

            $name = trim($data["name"]);
            $price = trim($data["price"]);
            $description = trim($data["description"]);
            $authors = ($data["author"]);
            $genres = ($data["genre"]);
            $error = "";
            $result_author = $this->db->query('SELECT * FROM authors');
            $result_genre = $this->db->query('SELECT * FROM genres');
            if ($authors == 0 && mysqli_num_rows($result_author) != 0) {
                $error .= "Вы не выбрали автора<br>";
            }
            if ($genres == 0 && mysqli_num_rows($result_genre) != 0) {
                $error .= "Вы не выбрали жанр<br>";
            }

            if (mysqli_num_rows($result_author) < 1) {
                $error .= "Добавьте авторов, пожалуйста<br>";
            }
            if (mysqli_num_rows($result_genre) < 1) {
                $error .= "Добавьте жанры, пожалуйста<br>";
            }

            if (!is_numeric($price) && !empty($price)) {
                $error .= "в строке цена вводятся только цифры<br>";
            }

            if ((strlen($name) == 0)) {
                $error .= "Вы не заполнили поле 'название'<br>";
            }
            if ((strlen($description) == 0)) {
                $error .= "Вы не заполнили поле 'описание'<br>";
            }
            if (empty($price)) {
                $error .= "Вы не заполнили поле 'цена'";
            }
            if ($error != "") {
                print "<center class=\"t\">$error</center> ";

            }
            return $error;

        }
    }

    public function addBook($data)
    {
        $title = trim($data["name"]);
        $price = trim($data["price"]);
        $description = trim($data["description"]);
        $authors = trim($data["author"]);
        $genres = trim($data["genre"]);

        $query = "INSERT INTO `books` (`title`,`description`,`price`) VALUES ('$title','$description','$price')";
        $this->db->addField($query);

        $queryGetID = "SELECT book_id FROM books WHERE title = '$title'
        AND description = '$description'";
        $newBookId = $this->db->getOneField($queryGetID);
        $newBookId = (String) $newBookId[0]['book_id'];

        $queryInAuthor = "INSERT INTO books_authors (`book_id`,`author_id`) VALUE ('$newBookId','$authors')";
        $this->db->addField($queryInAuthor);
        $queryInGenre = "INSERT INTO books_genres (`book_id`,`genre_id`) VALUE ('$newBookId','$genres')";
        $this->db->addField($queryInGenre);

        print "<center class=\"t2\">Данные добавлены</center> ";
    }

    public function deleteBook($id)
    {
        $book_id = (int)$id;
        if (!empty($id)) {
            $query = "DELETE FROM `books` WHERE book_id= . ' $book_id'";
            $this->db->query($query);
            $query = "DELETE FROM `books_authors` WHERE book_id= .'$book_id'";
            $this->db->query($query);
            $query = "DELETE FROM `books_genres` WHERE book_id= .'$book_id'";
            $this->db->query($query);
            print "<center class=\"t2\">Данные удалены</center> ";
        } else {
            print'ID книги задан неверно';
        }
    }

    public function updateBook($data)
    {
        if (!$this->validateBook($data)) {
            foreach ($data AS $k => $v) {
                switch ($k) {

                    case 'name':
                    case 'description':
                    case 'price':

                        $sqlbooks = $this->db->query('UPDATE books SET `' . $k . '` = "' . $v . '" WHERE book_id =' . $this->id . ' LIMIT 1');

                        break;
                    case 'author':

                        $del_books_author = $this->db->query('DELETE FROM books_authors WHERE book_id=' . $this->id . '');

                        for ($i = 0; $i < count($v); $i++) {
                            $sql_new_book_author_id = $this->db->query('INSERT INTO `books_authors` (`author_id`,`book_id`) VALUES ("' . $v[$i] . '",' . $this->id . ')');

                        }

                        break;

                    case 'genre':
                        $del_books_genre = $this->db->query('DELETE FROM books_genres WHERE book_id=' . $this->id . '');

                        for ($i = 0; $i < count($v); $i++) {
                            $sql_new_book_genre_id = $this->db->query('INSERT INTO `books_genres` (`genre_id`,`book_id`) VALUES ("' . $v[$i] . '",' . $this->id . ')');
                        }
                        break;

                }

            }
            print "<center class=\"t2\">Данные изменены</center> ";
        }
    }
}
