<?php

// require '/Volumes/MacHD/Users/sisco/localhost/mvc/libs/Database.php';

class Validate {

    private $_passed = false,
            $_errors = array();

    public function __construct(){
        $this->db = new Database();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function check( $source, $items = array() )
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                // Here echo out item array followed by rule and the rule_value that we set.
                // echo "{$item} {$rule} must be {$rule_value} <br>";

                 // This echo out the value for each item and rule. Also trim spaces surround.
                 $value = trim($source[$item]);

                 // Only meant for debugging purposes.
                 // echo "$value\n";

                // here we check if the exist or not
                if($rule === 'required' && empty($value)) {
                    $this->add_error("{$item} is required");
                }
                else if(!empty($value)) {
                    switch($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->add_error("{$item} must be a minimum of {$rule_value} characters.");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->add_error("{$item} must be a maximum of {$rule_value} characters.");
                            }
                        break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->add_error("{$rule_value} must match {$item}.");
                            }
                        break;
                        case 'unique';
                            $sth = $this->db->prepare("SELECT $item FROM $rule_value WHERE $item = :{$value}");
                            $sth->bindParam($value, $value);
                            $sth->execute();
                            if($sth->rowCount() > 0) {
                                $this->add_error("{$item} {$value} already exist.");
                            }
                        break;
                    }
                }
            }
        }

        // check whether _errors array is empty and set _passed to true.
        if(empty($this->_errors)) {
            $this->_passed = true;
        }

        // we have to return this anyway
        return $this;
    }

    // function to add errors
    private function add_error($error)
    {
        $this->_errors[] = $error;
    }

    // @return  array _errors array
    public function errors()
    {
        return $this->_errors;
    }

    // @return bool _passed state
    public function passed()
    {
        return $this->_passed;
    }




}