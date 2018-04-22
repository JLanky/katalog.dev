<?php
include "config.php";

$bookObject = new Books( $Db );

if ( !empty( $_REQUEST['book_id'] ) && empty($_POST['saveBookData'])) {
	$book_id = (int) trim( $_REQUEST['book_id'] );

	$book            = $bookObject->getBook( $book_id );
	$book['authors'] = $bookObject->getBookAuthors( $book_id );
	$book['genres'] = $bookObject->getBookGenres( $book_id );

	$authorObject = new Authors( $Db );
	$genreObject  = new Genres( $Db );

	$authors = $authorObject->getAuthorsList( $Db );
	$genres  = $genreObject->getGenresList( $Db );
}
elseif (!empty( $_REQUEST['book_id'] ) && !empty($_POST['saveBookData'])){

        $bookObject->updateBook( $_POST );
    $book_id = (int) trim( $_REQUEST['book_id'] );

    $bookObject = new Books( $Db );

    $book            = $bookObject->getBook( $book_id );
    $book['authors'] = $bookObject->getBookAuthors( $book_id );
    $book['genres'] = $bookObject->getBookGenres( $book_id );

    $authorObject = new Authors( $Db );
    $genreObject  = new Genres( $Db );

    $authors = $authorObject->getAuthorsList( $Db );
    $genres  = $genreObject->getGenresList( $Db );
}
else {
	header( 'Location:index.php' );
}
require_once( 'templates/update_books.phtml' );



