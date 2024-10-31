<?php
class news extends controller{
	
	public function index($param1 = "page",$page = 1)
	{
		global $config;

		$newsModel = $this->model("news");	

		$this->view("news/index",['object'=>$newsModel->getlimitedNews($config->news_totalRecords,$page),'totalPage'=>$newsModel->getNewsCount(),'page'=>$page,'totalRecords'=>$config->news_totalRecords]);
	}
}