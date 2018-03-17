<?php
class Genres {


    public static function getAllGenres($Db,$book_id=0) {
        if ($book_id!=0){
            $id=$Db->getOneField('genre','SELECT * FROM books_genres WHERE book_id='.$book_id);
        }else{
            $id=$Db->getOneField('genre','SELECT * FROM genres');
        }
       // var_dump($id);
        return $id;
    }


    public function __construct($Db, $id=0) {

        $genre=$Db->getOne('SELECT * FROM genres WHERE genre_id='.$id.'');
        $this->Db =           $Db;
        $this->id =           $id;
        $this->genre =        $genre['genre'];

    }
    public function getCurrentGenreBooks(){
        $return = array();

        $id=$this->Db->getOneField('book_id','SELECT * FROM books_genres WHERE genre_id='.$this->id.'');


        for($i = 0; $i < count($id); $i++) {
            $return[] = new Books($this->Db, $id[$i]);
        }
        return $return;

    }
    public function deleteGenre($id){
        $genre_id           = (int) $id;
        if (!empty($id)) {
            $del_genre        = $this->Db->query('DELETE FROM `genres` WHERE genre_id="' . $genre_id . '"');
            $del_genres_books = $this->Db->query('DELETE FROM `books_genres` WHERE genre_id="' . $genre_id . '"');
            print "<center class=\"t2\">Данные успешно удалены</center> ";
        }
        else{
            print'ID жанра задан неверно';
        }
    }

    public function addGenre($data){

        if (!empty($data) && !empty($data['genre'])){

            $genre=$data['genre'];

            $sqlgenres = "INSERT INTO `test`.`genres` (`genre`) VALUES ('$genre');";

            $result1 = $this->Db->query($sqlgenres);

            print "<center class=\"t2\">Данные успешно добавлены</center> ";
        }
        else{
            $error = '';
            $error .= "Вы не заполнили поле жанр<br>";

            print "<center class=\"t\">$error</center> ";
        }
    }


}
