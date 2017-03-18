<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Validate;
use app\index\model\User;
class Auth extends Controller
{

	protected $is_check_login = [''];

	public function _initialize()
	{
		if(!$this->checkLogin() && (in_array(Request::instance()->action(), $this->is_check_login) || $this->is_check_login[0] == '*'))
		{
			$this->error('您还没有登录请先登录', url('index/auth/login'));
		}
	}

	public function checkLogin()
	{
		// if(session('?user'))
		// {
		// 	return true;
		// }else{
		// 	return false;
		// }

		return session('?user');
	}


	public function login()
	{
		$this->assign('title', '登录页面');
		return $this->fetch();
	}

	public function doLogin()
	{
		$info = User::where(['email' => input('post.email'), 'password' => md5(input('post.password'))])->find();
		if($info){
			session('user', $info->toArray());
			$this->success('登录成功,欢迎回来');
		}else{
			$this->error('登录失败,fucking out');
		}
	}

	
	public function logout()
	{
		session(null);

		$this->success('退出成功！');
	}


	public function register()
	{
		$this->assign('title', '注册页面');

		return $this->fetch();
	}

	public function doRegister()
	{
		//定义验证规则
		$validate = new Validate([
			'username' => 'require|max:25',
			'email' => 'email',
			'password' => 'require|length:6,18'
			]);
			$data = [
			'username' => input('post.username'),
			'email' => input('post.email'),
			'password' => input('post.password')
			];
			if (!$validate->check($data)) {
				return json(['status' => 0, 'msg' => $validate->getError()]);
			}else{
				$data['password'] = md5($data['password']);
				$result = User::create($data);
				if($result){
					return json(['status' => 1, 'msg' => '注册成功', 'redirect_url' => url('index/index/index') ]);
				}else{
					return json(['status' => 0, 'msg' => '注册失败，请刷新页面重试 ！']);

				}
			}
	}
}
