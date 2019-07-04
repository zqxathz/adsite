<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    protected $user_list =[
      'admin' => 'admin123',
      'user' => 'user123'
    ];
    public function index()
    {

        header("Content-type: text/html; charset=utf-8");
        $template= 'login';
        $data = input();
        $islogin = session('islogin');


	    if ($islogin!=1){
		    $template= 'login';
	    } elseif ($islogin==1){
		    $template='index';
	    }
	    return $this->fetch($template);


    }

    public function checkuser(){
        $data = input();
        $info=[
            'info'=>'ok',
            'status'=>'n'
        ];

        session('islogin',null);

        foreach ($this->user_list as $key=>$item) {
            if (strtolower($data['username'])==$key && strtolower($data['password'])==$item){
                $info['status']='y';
                session('islogin',1);
                break;
            }
        }
        return json($info);
    }

    private function hello($name = 'ThinkPHP5')
    {
        header("Content-type: text/html; charset=utf-8");
        return $this->fetch('index');
    }

    public function get(){
        header("Content-type: text/html; charset=utf-8");
    	$data = $this->request->post();


    	$area_array= explode("\n",$data['two']);


    	$d = $this->getlocation($area_array,$data['city']);
    	$result['response']='success';
    	$result['data'] = $d;
    	return json($result);
    }

    private function getlocation($data,$city=''){
    	define("AMAP_URL", 'http://restapi.amap.com/v3/geocode/geo?key=aa3d69b27a7fb90c88cf383140fdf165&address=%s&city=%c&batch=true');
        $url2 = str_replace('%c',$city,AMAP_URL);

    	$a = ''; $i = 0; $c = 0;
    	$b= [];
    	foreach ($data as $item)
    	{
    		$a=$a.'|'.$item;
    		$i++;
		    if ($i==10) {
			    $a=mb_substr($a,1);
			    $url1 = str_replace('%s',$a,$url2);
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
			    $i=0;
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
