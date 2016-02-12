<?php
    class DatabaseHandler{
        var $currentRequestMethod;
        var $table;
        var $db;

        function __construct($databasefile,$tablename){
            $this->currentRequestMethod = $_SERVER['REQUEST_METHOD'];
            $this->table = $tablename;
            $this->db = new SQLite3($databasefile);
            if (!isset($this->db)){
                exit('<h1>Could not connect to database!!!</h1>');
            }
        }

        function __destruct(){
            $this->db->close();
        }

        /**
         * This function will take a 1D and a 2D array and run a search using them
         */
        function searchONE(array $field,array $keyValuePairs){
            $
            $statement = $this->db->prepare('SELECT :field FROM :table WHERE ')

        }

        function searchAND(){}

        function searchOR(){}
    }

    class dateList {

        var $list;
        var $currentDate;

        function __construct($serial = null){
            if($serial!=null){//Unserializing serialized null yeilds flase not null.
                $this->list = unserialize($serial);
            }else{
                $this->list = array();
            }

            $this->currentDate = $GLOBALS['date'];
        }
        function updateList(){
            if(in_array($this->currentDate, $this->list)){
                return 0;
            }else{
                $this->list[] = $this->currentDate;
                return 1;
            }
        }

        function outputForStorage(){
            return serialize($this->list);
        }

    }


?>
