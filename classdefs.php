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
         * Takes two arrays one containg string and one accosictive array of
         * strings (field => value) and then creates a SQLITE3 query using
         * the statement method.
         * There is an optional flag to enable case insensitivity for string
         * queries.
         */
        function searchAND(array $field, array $keyValuePairs, $collateNocase = false){
            $fields = implode(',',$field);
            $statementString = "SELECT ".$fields." FROM ".$this->table." WHERE ";
            $clauses = array();
            foreach ($keyValuePairs as $key => $value) {
                $clauses[] = $key."=:".$key;
            }
            switch ($collateNocase) {
                case true:
                    $statementString .= implode(' COLLATE NOCASE AND ', $clauses)." COLLATE NOCASE ;";
                    break;

                default:
                    $statementString .= implode(' AND ', $clauses)." ;";
                    break;
            }

            $statement = $this->db->prepare($statementString);
            foreach ($keyValuePairs as $key => $value) {
                $statement->bindValue(':'.$key, $value);
            }
            $result = $statement->execute();
            return $result;
        }

        function searchOR(array $field, array $keyValuePairs, $collateNocase = false){
            $fields = implode(',',$field);
            $statementString = "SELECT ".$fields." FROM ".$this->table." WHERE ";
            $clauses = array();
            foreach ($keyValuePairs as $key => $value) {
                $clauses[] = $key."=:".$key;
            }
            switch ($collateNocase) {
                case true:
                    $statementString .= implode(' COLLATE NOCASE OR ', $clauses)." COLLATE NOCASE ;";
                    break;

                default:
                    $statementString .= implode(' OR ', $clauses)." ;";
                    break;
            }

            $statement = $this->db->prepare($statementString);
            foreach ($keyValuePairs as $key => $value) {
                $statement->bindValue(':'.$key, $value);
            }
            $result = $statement->execute();
            return $result;
        }
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
