<?php
class db {
    //Staff DB
    public static $con;
    private static $isCon = false;
    private static $addUserAttempt = 0;


    public static function connect() {
        if (self::$isCon) return;
        self::$con = mysqli_connect('localhost','db_bot','dragon','Invoice');
        if (self::$con->connect_errno) {
            echo "Failed to connect to MySQL: (".self::$con->connect_errno.")".self::$con->connect_error;
        }
        //mysqli_set_charset(self::$con, "utf-8");
        self::$isCon = true;
    }
    public static function disconnect() {
        if (!self::$isCon) return;
        mysqli_close(self::$con);
        self::$isCon = false;
    }



}

db::connect();

?>
