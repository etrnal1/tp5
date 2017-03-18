<?php
namespace app\index\controller;

use app\index\model\User;
use think\View;
use app\index\model\Article;
use think\Controller;
class Index extends Controller
{
	/**
	 * 这是首页
	 */ 
	public function index()
	{
		return $this->fetch();
	}


}
