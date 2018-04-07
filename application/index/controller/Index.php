<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        header("Content-type: text/html; charset=utf-8");
        return $this->fetch('index');
    }

    public function get(){
        header("Content-type: text/html; charset=utf-8");
    	$data = $this->request->post();


    	$area_array= explode("\n",$data['two']);


    	$d = $this->getlocation($area_array);
    	$result['response']='success';
    	$result['data'] = $d;
    	return json($result);
    }

    private function getlocation($data){
    	define("AMAP_URL", 'http://restapi.amap.com/v3/geocode/geo?key=aa3d69b27a7fb90c88cf383140fdf165&address=%s&city=wuhan&batch=true');
    	$a = ''; $i = 0; $c = 0;
    	$b= [];
    	foreach ($data as $item)
    	{
    		$a=$a.'|'.$item;
    		$i++;
		    if ($i==10) {
			    $a=mb_substr($a,1);
			    $url1 = str_replace('%s',$a,AMAP_URL);
			    $a='';
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, $url1);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			    curl_setopt($ch, CURLOPT_HEADER, 0);
			    $output = curl_exec($ch);
			    curl_close($ch);
			    $output_array = json_decode($output);
			    if ($output_array){
                    foreach ($output_array->geocodes as $item1){
                        if (!empty($item1->location)){
                            $b[$c]['name']=$item1->formatted_address;
                            $b[$c]['center']=$item1->location;
                            $c++;
                        }

                    }
                }
			    $i=-1;
		    }
	    }

	    if (!empty($a)){
		    $a=mb_substr($a,1);

		    $url1 = str_replace('%s',$a,AMAP_URL);

		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    $output = curl_exec($ch);
		    curl_close($ch);
		    $output_array = json_decode($output);

		    foreach ($output_array->geocodes as $item1){
			    $b[$c]['name']=$item1->formatted_address;
			    $b[$c]['center']=$item1->location;
			    $c++;
		    }
	    }
		return $b;
    }
}
