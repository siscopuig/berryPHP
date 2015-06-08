<?php
	
	
	
class Model
{
	function __construct()
	{
		 // Here we have a database object to use in every model class
		$this->db = new Database();
	}
}