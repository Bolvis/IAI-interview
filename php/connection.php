<?php


class Connection
{
   /* @private  */
    private $connection;

    public function __construct(){
        $this -> connection = mysqli_connect('localhost', 'root','','iai');
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }
}