<?php

class newsModel extends model
{
	
	public function getNewsCount()
	{
		return SQLManager::NumRows("SELECT * FROM ".Config::$CMS_DB.".._webNews");
	}
	
	public function getlimitedNews(int $limit = 1,int $offset = 0)
	{
		
		$offset = $offset * $limit - $limit;
			
		$totalRecords = $this->getNewsCount();
		
		if($offset == 1)
			$offset = 0;
		
		$resource = SQLManager::Resource
		("
		SELECT TOP $limit News.*
		FROM
		(
			SELECT Subject,
					Article,
					EditDate,
					ROW_NUMBER() OVER (ORDER BY ID ASC) AS RowNumber 
			FROM ".Config::$CMS_DB.".._webNews
			
		) 
		AS News
		WHERE News.RowNumber BETWEEN $offset+1 AND $totalRecords
		");
		$array = [];
		
		while($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
		{
			
			$array[] = ["Subject" => $result['Subject'], "Article" => $result['Article'], "Datetime" => $result['EditDate']->format("d/m/y H:i")];
		}
		
		return $array;
	}
	
	function getallNews()
	{
		/* TO DO LATER */
	}
	
	function addNews($Article)
	{
		/* TO DO LATER */
	}
	
	function deleteNews($ID)
	{
		/* TO DO LATER */
	}
	
}