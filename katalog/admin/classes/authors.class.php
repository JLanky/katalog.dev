<?php
class Authors {


        public static function getAllAuthors($Db,$book_id=0) {
                 if ($book_id!=0){
                 $id=$Db->getOneField('author_id','SELECT * FROM books_authors WHERE book_id=' .$book_id);
                 }else{
                 $id=$Db->getOneField('author_id','SELECT * FROM authors');
                 }
                $return = array();
                for($i = 0; $i < count($id); $i++) {
                        $return[] = new Authors($Db, $id[$i]);
                }
                return $return;

        }


        public function __construct($Db, $id=0) {

                $author=$Db->getOne('SELECT * FROM authors WHERE author_id='.$id);

                $this->Db =           $Db;
                $this->id =           $id;
                $this->author =       $author['author'];

        }

        
         public function deleteAuthor($id){
                $author_id           = (int) $id;
                if (!empty($id)) {
                        $del_author        = $this->Db->query('DELETE FROM `authors` WHERE author_id="' . $author_id . '"');
                        $del_authors_books = $this->Db->query('DELETE FROM `books_authors` WHERE author_id="' . $author_id . '"');
                        print "<center class=\"t2\">Автор удален успешно.</center> ";
                }
                else{
                        print'ID указано неверно. Повторите попытку';
                }
        }
        public function getCurrentAuthorBooks(){
                $return = array();

                $id=$this->Db->getOneField('book_id','SELECT * FROM books_authors WHERE author_id='.$this->id);


                for($i = 0; $i < count($id); $i++) {
                        $return[] = new Books($this->Db, $id[$i]);
                }
                return $return;

        }
        public function addAuthor($data){

        if (!empty($data) && !empty($data['author'])){

        $author=$data['author'];

        $sqlauthors = "INSERT INTO `test`.`authors` (`author`) VALUES ('$author');";

        $result1 = $this->Db->query($sqlauthors);

        print "<center class=\"t2\">Автор добавлен успешно</center> ";
        }
        else{
        $error ='';
        $error .= "Поле автор не заполнено или заполнено неверно<br>";

        print "<center class=\"t\">$error</center> ";
        }
        }


        }
