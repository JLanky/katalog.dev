<?php

class Books {
	private $db;
	public $id;

	/**
	 * Books constructor.
	 *
	 * @param Db $db
	 */
	public function __construct( $db ) {
		$this->db = $db;
	}

	/**
	 * Get book
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getBook( $id, $ajax = false ) {
		$book = $this->db->getOne( 'SELECT * FROM books
      JOIN books_authors ON books.book_id = books_authors.book_id
      JOIN authors ON books_authors.author_id = authors.author_id
      JOIN books_genres ON books.book_id = books_genres.book_id
      JOIN genres ON books_genres.genre_id = genres.id
      WHERE books.book_id = ? LIMIT 1 ', $id );

		return (!$ajax) ? $book : json_encode($book, true);
	}

	/**
	 * Get book Authors
	 *
	 * @param $bookId
	 *
	 * @return mixed
	 */
	public function getBookAuthors( $bookId ) {
		$authors = $this->db->getAll( 'SELECT * FROM authors
		LEFT JOIN books_authors ON books_authors.author_id = authors.author_id
		LEFT JOIN books ON books.book_id = books_authors.book_id
		WHERE books.book_id =' . $bookId );
		return $authors;
	}

	public function getBookGenres( $bookId ) {
		$genres = $this->db->getAll( 'SELECT * FROM genres
		LEFT JOIN books_genres ON books_genres.genre_id = genres.id
		LEFT JOIN books ON books.book_id = books_genres.book_id
		WHERE books.book_id =' . $bookId );

		return $genres;
	}

	public function getAllBooks() {
		$books = $this->db->getAll( 'SELECT * FROM books' );

		foreach ( $books as $key => $book ) {
			$books[ $key ]['authors'] = $this->getBookAuthors( $book['book_id'] );
			$books[ $key ]['genres']  = $this->getBookGenres( $book['book_id'] );
		}

		return $books;
	}

	public function isCurrentBookAuthor( $id ) {
		for ( $i = 0, $l = count( $this->authors ); $i < $l; $i ++ ) {
			if ( $this->authors[ $i ]->id == $id ) {
				return true;
			}
		}

		return false;
	}

	public function isCurrentBookGenre( $id ) {
		for ( $i = 0, $l = count( $this->genres ); $i < $l; $i ++ ) {
			if ( $this->genres[ $i ]->id == $id ) {
				return true;
			}
		}

		return false;
	}

	public function validateBook( $data ) {
        $error = '';
		if ( ! empty( $data ) ) {

			$name          = trim( $data["title"] );
			$price         = trim( $data["price"] );
			$description   = trim( $data["description"] );
			$authors       = ( $data["author"] );
			$genres        = ( $data["genre"] );
			$error         = "";
			$result_author = $this->db->executeQuery( 'SELECT * FROM authors' );
			$result_genre  = $this->db->executeQuery( 'SELECT * FROM genres' );
			if ( $authors == 0 && !empty( $result_author ) != 0 ) {
				$error .= "Вы не выбрали автора<br>";
			}
			if ( $genres == 0 && !empty( $result_genre ) != 0 ) {
				$error .= "Вы не выбрали жанр<br>";
			}

			if ( !empty( $result_author ) < 1 ) {
				$error .= "Добавьте авторов, пожалуйста<br>";
			}
			if ( !empty( $result_genre ) < 1 ) {
				$error .= "Добавьте жанры, пожалуйста<br>";
			}

			if ( ! is_numeric( $price ) && ! empty( $price ) ) {
				$error .= "в строке цена вводятся только цифры<br>";
			}

			if ( ( strlen( $name ) == 0 ) ) {
				$error .= "Вы не заполнили поле 'название'<br>";
			}
			if ( ( strlen( $description ) == 0 ) ) {
				$error .= "Вы не заполнили поле 'описание'<br>";
			}
			if ( empty( $price ) ) {
				$error .= "Вы не заполнили поле 'цена'";
			}
			if ( $error != "" ) {
                $error = "<center class=\"t\">.$error.</center> ";

			}

		}
        return $error;
	}

	public function addBook( $data ) {
		$title       = trim( $data["name"] );
		$price       = trim( $data["price"] );
		$description = trim( $data["description"] );
		$authors     = array_filter( $data["author"] );
		$genres      = array_filter( $data["genre"] );

		$query = "INSERT INTO `books` (`title`,`description`,`price`) VALUES ('$title','$description','$price')";

		$resultQuery = $this->db->executeQuery( $query, 'insert' );
		if ( $resultQuery['result'] ) {

			$bookID = $resultQuery;
		} else {
			echo 'Something wrong';
			die();
		}

		foreach ( $authors as $author ) {
			$queryInAuthor = "INSERT INTO books_authors (`book_id`,`author_id`) VALUE ('$bookID','$author')";
			$this->db->executeQuery( $queryInAuthor, 'insert' );
		}

		foreach ( $genres as $genre ) {
			$queryInGenre = "INSERT INTO books_genres (`book_id`,`genre_id`) VALUE ('$bookID','$genre')";

			$this->db->executeQuery( $queryInGenre, 'insert' );
		}

		return 'Данные добавлены';
	}

	public function deleteBook( $id ) {
		$book_id = (int) $id;
		if ( ! empty( $id ) ) {
			$query = "DELETE FROM `books` WHERE book_id='$book_id'";
			$this->db->executeQuery( $query, 'delete' );
			$query = "DELETE FROM `books_authors` WHERE book_id='$book_id'";
			$this->db->executeQuery( $query, 'delete' );
			$query = "DELETE FROM `books_genres` WHERE book_id='$book_id'";
			$this->db->executeQuery( $query, 'delete' );
			$res = array('error' => 0,'msg' => "Данные удалены.");
		} else {
			$res = array('error' => 1,'msg' => "ID книги задан неверно");;
		}

		return json_encode($res, true);
	}

	public function updateBook( $data ) {
		if ( ! $this->validateBook( $data ) ) {

			foreach ( $data AS $k => $v ) {
				switch ( $k ) {

					case 'title':
					case 'description':
					case 'price':
					    $sql = "UPDATE books SET  $k  ='$v' WHERE book_id =  ".$data['book_id'] ." LIMIT 1";

						$sqlbooks = $this->db->executeQuery($sql   );

						break;
					case 'author':

						$del_books_author = $this->db->executeQuery( 'DELETE FROM books_authors WHERE book_id=' . $data['book_id']);

						for ( $i = 0; $i < count( $v ); $i ++ ) {
							$sql_new_book_author_id = $this->db->executeQuery( 'INSERT INTO `books_authors` (`author_id`,`book_id`) VALUES (' . $v[ $i ] . ',' . $data['book_id'] . ')' );

						}

						break;

					case 'genre':
						$del_books_genre = $this->db->executeQuery( 'DELETE FROM books_genres WHERE book_id=' . $data['book_id'] . '' );

						for ( $i = 0; $i < count( $v ); $i ++ ) {
							$sql_new_book_genre_id = $this->db->executeQuery( 'INSERT INTO `books_genres` (`genre_id`,`book_id`) VALUES (' . $v[ $i ] . ',' . $data['book_id'] . ')' );
						}
						break;
				}
			}
			return 'Данные изменены';
		}
	}
}
