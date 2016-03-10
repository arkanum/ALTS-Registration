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
         * Takes two arrays one containing string and one associative array of
         * strings (field => value) and then creates a SQLITE3 query using
         * the statement method.
         * There is an optional flag to enable case insensitivity for string
         * queries.
         */
        function search(array $fields, array $keyValuePairs, $extras = ""){
            $columns = implode(', ',$fields);
            $statementString = "SELECT ".$columns." FROM ".$this->table;
            if (!empty($keyValuePairs)){
                $statementString.= " WHERE ";
                $clauses = array();
                foreach ($keyValuePairs as $key => $value) {
                    $clauses[] = $key."=:".$key;
                }
                $statementString .= implode(' AND ', $clauses);
            }
            $statementString .= " ".$extras." ;";

            $statement = $this->db->prepare($statementString);
            foreach ($keyValuePairs as $key => $value) {
                $statement->bindValue(':'.$key, $value);
            }
            $result = $statement->execute();
            if ($result instanceof SQLite3Result){
                return $result;
            }else{
                exit("Something went wrong with the database query try again.");
            }
        }

        /**
         * Takes two arrays one containing string and one associative array of
         * strings (field => value) and then creates a SQLITE3 query using
         * the statement method.
         * There is an optional flag to enable case insensitivity for string
         * queries.
         */
        function searchAND(array $fields, array $keyValuePairs, $collateNocase = false){
            $columns = implode(', ',$fields);
            $statementString = "SELECT ".$columns." FROM ".$this->table." WHERE ";
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
            if ($result instanceof SQLite3Result){
                return $result;
            }else{
                exit("Something went wrong with the database query try again.");
            }
        }

        /**
         * Takes two arrays one containing string and one associative array of
         * strings (field => value) and then creates a SQLITE3 query using
         * the statement method.
         * There is an optional flag to enable case insensitivity for string
         * queries.
         */
        function searchOR(array $fields, array $keyValuePairs, $collateNocase = false){
            $columns = implode(', ',$fields);
            $statementString = "SELECT ".$columns." FROM ".$this->table." WHERE ";
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
            if ($result instanceof SQLite3Result){
                return $result;
            }else{
                exit("Something went wrong with the database query try again.");
            }
        }

        function update(array $updateData, array $keyValuePairs){
            $data = array();
            $conditions = array();
            foreach ($updateData as $key => $value) {
                $data[] = $key."=:".$key;
            }
            foreach ($keyValuePairs as $key => $value) {
                $conditions[] = $key."=:".$key;
            }
            $statementString = "UPDATE ".$this->table." SET ".implode(", ",$data)." WHERE ".implode(" AND ",$conditions).";";

            $statement = $this->db->prepare($statementString);
            foreach (array_merge($updateData, $keyValuePairs) as $key => $value) {
                $statement->bindValue(':'.$key, $value);
            }
            $result = $statement->execute();
            if ($result instanceof SQLite3Result){
                return true;
            }else{
                exit("Something went wrong with the database query try again.");
            }
        }

        function insert(array $fields, array $keyValuePairs){
            $columns = implode(',',$fields);
            foreach ($keyValuePairs as $key => $value) {
                $values[] = ":".$key;
            }
            $statementString = "INSERT INTO ".$this->table. "(".$columns.") VALUES (".implode(',', $values).");";
            $statement = $this->db->prepare($statementString);
            foreach ($keyValuePairs as $key => $value) {
                $statement->bindValue(':'.$key, $value);
            }
            $result = $statement->execute();
            if ($result instanceof SQLite3Result){
                return $result;
            }else{
                exit("Something went wrong with the database query try again.");
            }
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

    //Used on summary page for uksort. Most recent date fisrt
    function datecompare($date1,$date2){
            return strtotime($date2)-strtotime($date1);
        }
?>
