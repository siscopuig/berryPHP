<?php

/*PDO::FETCH_* constants

Constant 			Row format

 PDO::FETCH_BOTH 		Array with both numeric and string (column names) keys. The default format.
 PDO::FETCH_NUM 		Array with numeric keys.
 PDO::FETCH_ASSOC 	Array with string (column names) keys.
 PDO::FETCH_OBJ 		Object of class stdClass with column names as property names.*/

//	The prepare() and execute() methods are especially useful for queries that you want
//	to execute multiple times. Once you’ve prepared a query, you can execute it with new
//	values without re preparing it



class Database extends PDO
{
    // initialize connection
    function __construct(){
        parent::__construct(Config::get('DB_TYPE').':host='.Config::get('DB_HOST').';dbname='.Config::get('DB_NAME'), Config::get('DB_USER'), Config::get('DB_PASS'));

        try {
            $dsn="mysql:dbname=".Config::get('DB_NAME').";host=".Config::get('DB_HOST');
            $this->db = new PDO($dsn, Config::get('DB_USER'), Config::get('DB_PASS'));

            //Turn OFF emulated prepared statements! and show the errors by default. Turned True will emulate
            // prepare statements and not show up the ERRORS.
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // $this->db->query('SET NAMES GBK');

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}

