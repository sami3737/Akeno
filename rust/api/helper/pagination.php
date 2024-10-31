<?php
class paginator
{
	public static function getPagination($itemperPage, $curPage, $totalRecords,$type)
	{
		if(!is_numeric($curPage) || !is_numeric((int)$itemperPage) || !is_numeric($totalRecords))
		{
			exit;
		}

		$totalPages = ceil($totalRecords / $itemperPage);

		$pages = '<center>
					<ul class="pagination">';
					
		$curPage = $curPage <= 0 ? 1 : $curPage;
		$left_links		= $curPage - 6;
		$right_links = $curPage + 6; 
		

		if($left_links < 0)
			$left_links = 0;
		

		for($i = $left_links+1; $i < $curPage; $i++)
		{
			$pages .= "<li onclick='switchPage(\"".$type."\",".$i.")'><a>".$i."</a></li>";		
		}
		

		
		for($i = $curPage; $i < $right_links;$i++)
		{

			$class = $i == $curPage ? "active" : "";
			if($i <= $totalPages)
				$pages .= "<li class='".$class."' onclick='switchPage(\"".$type."\",".$i.")'><a>".$i."</a></li>";
		}		

		$pages .= '
					</ul>
			</center>';
			
		return $pages;
		
		/* TO DO 
			SOME FIXES IN PREV AND LIMIT BLA BLA 
		*/
	}
}
