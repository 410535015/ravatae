<?php session_start();?>
<?php
class auth{
    protected $msg_fail = "";
    function success(){
        $_SESSION['uid'] = $_POST['uid'];
        $_SESSION['pwd'] = $_POST['pwd'];
    }
    function fail(){
        session_destroy();
        echo "<script>".$this -> $msg_fail."</script>";
        exit;
    }
    function __construct(){
        $encode_uid =urlencode($_POST["uid"]);
        $encode_pwd =urlencode($_POST["pwd"]);
        
        $url = "http://ldap.ndhu.edu.tw/octopus/modauth/LdapADOSet_V2.pl?type=Auth&pusrname=$encode_uid&ppasswd=$encode_pwd";
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        
        curl_close($ch);
        
        if (strpos($output,'rawmessage="Success"')!= FALSE)
            $this -> succeess();
        else
            $this -> fail();
    }
}
?>