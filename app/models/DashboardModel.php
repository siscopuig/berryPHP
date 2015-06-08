<?php


/**
 * Class DashboardModel - this is under development
 */
class DashboardModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}


    /**
     * Save text in database - return $data on success
     *
     * @param $text
     * @return array|bool
     */
    public function xhrInsert($text)
	{
        $sql = $this->db->prepare("INSERT INTO dashboard (text) VALUES (:text)");
        $sql->execute(array(':text' => $text));
        if ($sql->rowCount() == 1) {
            return $data = array('text' => $text, 'id' => $this->db->lastInsertId());
        }
        return false;
	}


    /**
     * Get a list of records in table data - return $data on success
     *
     * PDOStatement::setFetchMode — Set the default fetch mode for this statement
     * PDO::FETCH_ASSOC: returns an  associative array indexed by column name as returned in your result set.
     *
     * @return array|bool
     */
    public function xhrGetListings()
	{
        $sql = $this->db->prepare('SELECT * FROM dashboard');
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $sql->execute();
        return $data = $sql->fetchAll();

	}


    /**
     * Delete last entry by id
     *
     * @param $id
     */
    function xhrDeleteListing($id)
	{
        $sql = "DELETE FROM dashboard WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
	}
}