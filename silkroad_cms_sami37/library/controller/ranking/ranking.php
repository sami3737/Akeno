<?php
class Ranking extends Controller
{

	public function index()
	{
		
		$this->view("ranking/index");
	}
	
	public function search($searchType = 'charname',$searchValue = "")
	{
		global $config;

		$rankingModel = $this->model("ranking");
		

		$Type;
		switch(strtolower($searchType))
		{
			case "charname":
			{
				//run charname searcher
				$Type = "Player";
			}
			break;
			
			case "guild":
			{
				//run guildnam searcher
				$Type = "Guild";
			}
			break;
			
			default:
			{
				//run charname searcher
				$Type = "Player";	

			}
			break;
		}
		if(strlen($searchValue) == 0)
		{
			$this->view("ranking/top-players",["object"=>$rankingModel->getTopPlayers($config->ranking_totalRecords,1), "totalPage" => $rankingModel->getPlayersCount(), "page" => 1, "totalRecords" => $config->ranking_totalRecords]);
			return;
		}
		$this->view("ranking/search",["object"=>$rankingModel->search($Type,$searchValue),"searchType"=>$Type]);
	}
	
	public function Player($searchValue = "")
	{
		//echo $searchValue;
		if(!is_numeric($searchValue))
			die;
		
		$dataModel = $this->model("data");
		/*
		$this->view("ranking/chardata",["object"=>$dataModel->getPlayerData($searchValue),"inventory"
																			"slot0" => $dataModel->getCharItembySlot($searchValue,0),
																			"slot1" => $dataModel->getCharItembySlot($searchValue,1),
																			"slot2" => $dataModel->getCharItembySlot($searchValue,2),
																			"slot3" => $dataModel->getCharItembySlot($searchValue,3),
																			"slot4" => $dataModel->getCharItembySlot($searchValue,4),
																			"slot5" => $dataModel->getCharItembySlot($searchValue,5),
																			"slot6" => $dataModel->getCharItembySlot($searchValue,6),
																			"slot7" => $dataModel->getCharItembySlot($searchValue,7),
																			"slot9" => $dataModel->getCharItembySlot($searchValue,9),
																			"slot10" => $dataModel->getCharItembySlot($searchValue,10),
																			"slot11" => $dataModel->getCharItembySlot($searchValue,11),
																			"slot12" => $dataModel->getCharItembySlot($searchValue,12)]);
																			*/
		$this->view("ranking/chardata",["object"=>$dataModel->getPlayerData($searchValue),"inventory" => $dataModel->getPlayerItems($searchValue), "avatar" => $dataModel->getPlayerAvatars($searchValue)]);
																			
	}
	
	public function topPlayers($param1 = "page",$page = 1)
	{
		global $config;
		
		if(!is_numeric($page))
			die;
		$rankingModel = $this->model("ranking");
		
		$this->view("ranking/top-players",
		["object"=>$rankingModel->getTopPlayers($config->ranking_totalRecords,$page), 
		"totalPage" => $rankingModel->getPlayersCount(),
		"page" => $page,
		"totalRecords" => $config->ranking_totalRecords]);
	}

	public function topGuilds($param1 = "page",$page = 1)
	{
		global $config;
		
		if(!is_numeric($page))
			die;
		$rankingModel = $this->model("ranking");
		
		$this->view("ranking/top-guilds",
		["object"=>$rankingModel->getTopGuilds($config->ranking_totalRecords,$page), 
		"totalPage" => $rankingModel->getGuildsCount(),
		"page" => $page,
		"totalRecords" => $config->ranking_totalRecords]);
	}
	
	public function topJobbers()
	{
		
	}
	
	public function topTraders()
	{
		
	}
	
	public function topHunters()
	{
		
	}
	
	public function topThieves()
	{
		
	}
	
	public function topHonor()
	{
		
	}
	
	public function topUniques()
	{
		
	}
	
	
	
}