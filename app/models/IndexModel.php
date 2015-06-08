<?php
	
// THIS PAGE PRETEND DISPLAY SOME CONTENT IN INDEX PAGE

class IndexModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	
	public function display()
	{

		$sth = $this->db->prepare('SELECT id, title, content, dateAdded FROM post');
		$sth->execute();
		return $sth->fetchAll();

	}
}