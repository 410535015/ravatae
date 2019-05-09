<?php

class sql{
    private $error_msg;
    private $host = "";
    private $username = "";
    private $password = "";
    private $dbName = "";
    private $link;
    private $last_sql = "";
    private $last_id = 0;
    private $last_num_rows = 0;
    private $error_message = "";

    
    public function __construct($host, $username, $password, $dbName){
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbName = $dbName;
        
        $this -> connect();
    }
    function connect(){
        $this->link = ($GLOBALS["___mysqli_ston"] = mysqli_connect($this->host, $this->username, $this->password));
        if (mysqli_connect_errno()){
            $this->error_msg = "Failed to Connect to MySQL Server:" . mysqli_connect_error;
            return false;
        }
        
        mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES utf8");
        mysqli_query($this->link, "SET NAMES utf8");
        mysqli_query($this->link, "SET CHARACTER_SET_database= utf8");
        mysqli_query($this->link, "SET CHARACTER_SET_CLIENT= utf8");
        mysqli_query($this->link, "SET CHARACTER_SET_RESULTS= utf8");
        
        if(!(bool)mysqli_query($this->link, "USE ".$this->dbName))$this->error_message = 'Database '.$this->dbName.' does not exist!';
    }
    
    public function __destruct(){
        mysqli_close($this->link);
    }
    
    public function execute($sql = null){
        if ($sql===null) return false;
        $this->last_sql = str_ireplace("DROP","",$sql);
        $result_set = array();
        
        $result = mysqli_query($this->link, $this->last_sql);
        
        if (((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) {
            $this->error_message = "MySQL ERROR: " . ((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
        } else {
            $this->last_num_rows = @mysqli_num_rows($result);
            for ($xx = 0; $xx < @mysqli_num_rows($result); $xx++) 
                $result_set[$xx] = mysqli_fetch_assoc($result);
            if(isset($result_set)) 
                return $result_set;
            else
                $this->error_message = "result: zero";
        }
    }
    
    public function query($table = null, $condition = 1, $orderby = 1, $fields = '*', $limit = ''){
        $sql = "SELECT {$fields} FROM {$table} WHERE {$condition} ORDER BY {$orderby} {$limit}";
        return $this->execute($sql);
    }
    
    public function insert($table = null, $data_array = array()){
        if($table===null)return false;
        if(count($data_array) == 0) return false;
        
        $tmp_col = array();
        $tmp_dat = array();
        
        foreach ($data_array as $key => $value) {
            $value = mysqli_real_escape_string($this->link, $value);
            $tmp_col[] = $key;
            $tmp_dat[] = "'$value'";
        }
        $columns = join(",", $tmp_col);
        $data = join(",", $tmp_dat);
        
        $this->last_sql = "INSERT INTO " . $table . "(" . $columns . ")VALUES(" . $data . ")";
        
        mysqli_query($this->link, $this->last_sql);
        
        if (((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) {
            echo "MySQL Update Error: " . ((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
        } else {
            $this->last_id = mysqli_insert_id($this->link);
            return $this->last_id;
        }
    }
    
    public function update($table = null, $data_array = null, $key_column = null, $id = null) {
        if($table == null){
            echo "table is null";
            return false;
        }
        if($id == null) return false;
        if($key_column == null) return false;
        if(count($data_array) == 0) return false;
        
        $id = mysqli_real_escape_string($this->link, $id);
        
        $setting_list = "";
        for ($xx = 0; $xx < count($data_array); $xx++) {
            list($key, $value) = each($data_array);
            $value = mysqli_real_escape_string($this->link, $value);
            $setting_list .= $key . "=" . "\"" . $value . "\"";
            if ($xx != count($data_array) - 1)
                $setting_list .= ",";
        }
        $this->last_sql = "UPDATE " . $table . " SET " . $setting_list . " WHERE " . $key_column . " = " . "\"" . $id . "\"";
        $result = mysqli_query($this->link, $this->last_sql);
        
        if (((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) {
            echo "MySQL Update Error: " . ((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
        } else {
            return $result;
        }
    }

    public function delete($table = null, $key_column = null, $id = null) {
        if ($table===null) return false;
        if($id===null) return false;
        if($key_column===null) return false;

        return $this->execute("DELETE FROM $table WHERE " . $key_column . " = " . "\"" . $id . "\"");
    }

    public function getLastSql() {
        return $this->last_sql;
    }

    public function getLastId() {
        return $this->last_id;
    }

    public function getLastNumRows() {
        return $this->last_num_rows;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }
}

class get_election_list extends sql{
    public function getCurrentElecList(){
        $result = $this->query("election","start_time <= CURRENT_DATE() AND expert_time >= CURRENT_DATE()");
        return $result;
    }

    public function getElecListByDate($fromDate = null, $toDate = null){
        if ($fromDate===null || $toDate===null) return false;
        $result = $this->query("election","start_time >= $fromDate AND expert_time <= $toDate");
        return $result;
    }

    public function getElecListByTitle($title = null){
        if($title === null) return false;
        $result = $this->query("election","title_zh LIKE %$title% OR title_en LIKE %$title%");
        return $result;
    }

    public function getElecTitleByDate($fromDate = null, $toDate = null, $lang = "zh_TW"){
        if ($fromDate === null || $toDate === null) return false;
        if ($lang == "zh_TW") $fields = "title_zh";
        else $fields = "title_en";

        $result = $this->query("election","title_zh LIKE %$title OR title_en LIKE %$title%",1,$fields);
        return $result;
    }
}

?>