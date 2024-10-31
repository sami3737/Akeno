<?php
class Home extends Controller{
	
	public function index()
	{
		$newsModel = $this->model("news");
		
		global $config;


		$this->view("news/index",['object'=>$newsModel->getlimitedNews($config->news_totalRecords,1),'totalPage'=>$newsModel->getNewsCount(),'page'=>1,'totalRecords'=>$config->news_totalRecords]);
	}
}