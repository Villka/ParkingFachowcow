<?php

namespace app\libs\PDOHandler;

class PDOHandler extends SQL
{

	private $pdoDriver;

	function __construct($drv=null)
	{
		parent::__construct();
		$this->pdoDriver = DB_DRV_SQLITE;
	}

	public function doQuery()
	{
		$dbh = new \PDO($this->pdoDriver . ':' . DB_PATH);
		$selectResult = [];
		if ($this->query)
		{
			$stmt = $dbh->prepare($this->query);
			$queryResult = $stmt->execute();
			// print_r($stmt->errorInfo());
			$i = 0;
	        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) 
	        {
	            $selectResult[$i] = $row;
	            $i++;
	        }
	        if($queryResult)
	        {
	        	if($selectResult)
	        	{
	        		$dbh = null;
	        		$stmt = null;
	        		return $selectResult;
	        	}
	        	$dbh = null;
	        	$stmt = null;
	        	return true;
	        }
	        $dbh = null;
	        $stmt = null;
	        return false;
		}
	}
}