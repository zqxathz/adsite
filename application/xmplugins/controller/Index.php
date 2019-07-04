<?php



namespace app\xmplugins\controller;

use think\Controller;
use think\Response;

class Index extends Controller
{
	public function index(){
		//header( 'Content-Type:text/xml;charset=utf-8');
		//\response(file_get_contents($file),200,[]);
		$res  = new Response();
		$res->contentType('text/plain');
		$file = getcwd().'/static/xmplugins/xmplugins.xml';

		$res->header('Access-Control-Allow-Origin','*');
		$res->data(file_get_contents($file));
		$res->code(200);
		$res->send();
		//return jsonp(file_get_contents($file));

	}

}