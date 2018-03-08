<?php
class Books {


        public static function getAllBooks($Db) {
                $return = array();
                $id=$Db->getOneField('book_id','SELECT * FROM books');


                for($i = 0; $i < count($id); $i++) {
                        $return[] = new Books($Db, $id[$i]);
                }
                return $return;

        }

        public function isCurrentBookAuthor($id){
        for($i = 0, $l = count($this->authors); $i < $l; $i++) {
        if($this->authors[$i]->id == $id) {
        return true;
        }
        }
        return false;
        }
        
        public function isCurrentBookGenre($id){
        for($i = 0, $l = count($this->genres); $i < $l; $i++) {
        if($this->genres[$i]->id == $id) {
        return true;
        }
        }
        return false;
        }

        public function __construct($Db, $id=0) {

                $book=$Db->getOne('SELECT * FROM books WHERE book_id='.$id);
                $this->Db =           $Db;
                $this->id =           $id;
                $this->name =         $book['name'];
                $this->tsena =        $book['tsena'];
                $this->description=   $book['description'];
                $this->authors    = Authors::getAllAuthors($Db,$id);
                $this->genres     = Genres::getAllGenres($Db,$id);
        }

        public function validateBook($data){
                if (!empty($data)) {

                        $name          = trim($data["name"]);
                        $tsena         = trim($data["tsena"]);
                        $description   = trim($data["description"]);
                        $authors       = ($data["author"]);
                        $genres        = ($data["genre"]);
                        $error         = "";
                        $result_author = $this->Db->query('SELECT * FROM authors');
                        $result_genre  = $this->Db->query('SELECT * FROM genres');
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

                        if (!is_numeric($tsena) && !empty($tsena)) {
                                $error .= "в строке цена вводятся только цифры<br>";
                        }

                        if ((strlen($name) == 0)) {
                                $error .= "Вы не заполнили поле 'название'<br>";
                        }
                        if ((strlen($description) == 0)) {
                                $error .= "Вы не заполнили поле 'описание'<br>";
                        }
                        if (empty($tsena)) {
                                $error .= "Вы не заполнили поле 'цена'";
                        }
                        if ($error != "") {
                                print "<center class=\"t\">$error</center> ";

                        }
                        return $error;

                }
        }


       public function addBook($data){
        if(!$this->validateBook($data)&& !empty($data)){
        $name          = trim($data["name"]);
        $tsena         = trim($data["tsena"]);
        $description   = trim($data["description"]);
        $authors       = ($data["author"]);
        $genres        = ($data["genre"]);
        $sqlbooks    = "INSERT INTO `test`.`books` (`name`,`description`,`tsena`) VALUES ('$name','$description','$tsena');";
        $result3     = $this->Db->query($sqlbooks);
        $new_book_id = mysqli_insert_id();

        for ($i = 0; $i < count($authors); $i++) {
            $sql_new_book_author_id = "INSERT INTO `test`.`books_authors` (`author_id`,`book_id`) VALUES ('$authors[$i]','$new_book_id');";
            $result4                = $this->Db->query($sql_new_book_author_id);
        }
        for ($i = 0; $i < count($genres); $i++) {
            $sql_new_book_genre_id = "INSERT INTO `test`.`books_genres` (`genre_id`,`book_id`) VALUES ('$genres[$i]','$new_book_id');";
            $result5               = $this->Db->query($sql_new_book_genre_id);
        }
        print "<center class=\"t2\">Данные добавлены</center> ";
    }

                }



        public function deleteBook($id){
                $book_id           = (int) $id;
                if (!empty($id)) {
                        $del_books         = $this->Db->query('DELETE FROM `books` WHERE book_id="' . $book_id . '"');
                        $del_books_authors = $this->Db->query('DELETE FROM `books_authors` WHERE book_id="' . $book_id . '"');
                        $del_books_genres  = $this->Db->query('DELETE FROM `books_genres` WHERE book_id="' . $book_id . '"');
                        print "<center class=\"t2\">Данные удалены</center> ";
                }
                else{
                        print'ID книги задан неверно';
                }
        }

        public function updateBook($data){
                if(!$this->validateBook($data)){
                        foreach($data AS $k=>$v) {
                                switch($k) {

                                case 'name':
                                case 'description':
                                case 'tsena':

                                        $sqlbooks = $this->Db->query('UPDATE books SET `'.$k.'` = "'.$v.'" WHERE book_id ='.$this->id.' LIMIT 1');

                                        break;
                                case 'author':

                                        $del_books_author = $this->Db->query('DELETE FROM books_authors WHERE book_id='.$this->id.'');

                                        for ($i = 0; $i < count($v); $i++) {
                                                $sql_new_book_author_id = $this->Db->query('INSERT INTO `books_authors` (`author_id`,`book_id`) VALUES ("'.$v[$i].'",'.$this->id.')');

                                        }

                                        break;

                                case 'genre':
                                        $del_books_genre = $this->Db->query('DELETE FROM books_genres WHERE book_id='.$this->id.'');

                                        for ($i = 0; $i < count($v); $i++) {
                                                $sql_new_book_genre_id = $this->Db->query('INSERT INTO `books_genres` (`genre_id`,`book_id`) VALUES ("'.$v[$i].'",'.$this->id.')');
                                        }
                                        break;

                                }

                        }
                        print "<center class=\"t2\">Данные изменены</center> ";
                }
        }
}
