<?php

namespace App\Http\Controllers\Web\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
	// 保存路径
    protected $basePath = './template';
    protected $parseUrls;

    public function index(Request $request)
    {
    	$dirname = '1';
    	$url = $request->has('url') ? $request->url : 'https://www.bjtqcy.com/';
    	$this->parseUrls = parse_url($url);
    	$data = \App\Repositories\Tools\NetRequest::curl($url);
    	// gb2312转utf-8
    	$data = iconv('gb2312', 'utf-8//IGNORE', $data);

    	// 可下载的文件类型
    	$suffixs = ['css', 'js', 'jpg', 'png', 'gif', 'jpeg', 'mp4'];
    	// 获取采集页面相关文件
    	preg_match_all('/(?:href|src)="([^>]*?['.implode('|', $suffixs).'])"/', $data, $matches);
    	
    	// 按文件类型，逐一文件保存到本地
    	$result = [];
    	if (isset($matches[1]) && $matches[1]) {
    		$result['urls'] = [];
    		foreach ($matches[1] as $info) {
    			// 后缀名名称(不带点)
    			$suffix = substr(strrchr($info, '.'), 1);
    			// 后缀名不符合下载类型，不做处理
    			if (!$suffix || !in_array($suffix, $suffixs)) {
    				$result['fail'][] = '[continue] '.$info;
    				continue;
    			}
    			// 保存文件
    			$filename = $this->downloadFile($info, $dirname);
    			// 输出结果
    			$result['success'][] = '['.$filename.'] '.$info;

    			// 获取url地址
    			$urls = parse_url($info);
    			if (!isset($urls['scheme'])) {
    				$urls['scheme'] = $this->parseUrls['scheme'];
    			}
    			if (!isset($urls['host'])) {
    				$urls['host'] = $this->parseUrls['host'];
    			}
    			$fullUrl = $urls['scheme'].'://'.$urls['host'];
    			if (!in_array($fullUrl, $result['urls'])) {
    				$result['urls'][] = $fullUrl;
    			}
    		}
    		
    	} else {
    		print_r($matches);
    	}

    	// 保存页面代码
    	$webname = $this->getFileName($url);
    	if (!$webname) {
    		$webname = dirname($url).'_index.html';
    	}
    	if ($result['urls']) {
    		$data = str_replace($result['urls'], '.', $data);
    	}
    	// 替换编码
    	$data = str_replace('gb2312', 'utf-8', $data);
    	$result['file']['name'] = $webname;
    	$result['file']['content'] = $this->saveFile($this->basePath.'/'.$dirname.'/'.$webname, $data);

    	return $result;
    }

    protected function downloadFile($url_file, $dir='1') {
    	$urls = parse_url($url_file);
    	if (!isset($urls['scheme'])) {
    		$urls['scheme'] = $this->parseUrls['scheme'];
    	}
    	if (!isset($urls['host'])) {
    		$urls['host'] = $this->parseUrls['host'];
    	}
    	$url_file = $urls['scheme'].'://'.$urls['host'].$urls['path'];

    	if (!$urls['path']) {
    		return '';
    	}

    	// 拼接文件保存路径，存在直接返回
    	$filename = $this->basePath.'/'.$dir.'/'.ltrim($urls['path'], '/');
    	if (is_file($filename)) {
    		return true;
    	}
    	// 创建目录
    	$dirPath = dirname($filename);
    	if (!is_dir($dirPath)) {
    		mkdir($dirPath, 0777, true);
    	}
    	// 获取远程文件内容
    	$data = \App\Repositories\Tools\NetRequest::curl($url_file);

    	return $this->saveFile($filename, $data);
    }

    protected function saveFile($filename, $content) {
    	return file_put_contents($filename, $content);
    }

    protected function getFileName($url)
    {
    	$filename = basename($url, '.html');
    	return $filename ? $filename.'.html' : str_replace('/', '_', parse_url($url)['path']).'.html';
    }


    public function test()
    {
    	$url = 'https://www.bjtqcy.com/seo/';
    	echo $this->getFileName($url);die;
    	$this->parseUrls = parse_url($url);
    	print_r($this->parseUrls);
    	echo '<br>'.chr(10);
    	echo 'basename='.basename($url, '.html').'<br>'.chr(10);
    	echo 'dirname='.dirname($url).'<br>'.chr(10);
    	die;
    	$file = '/UserEdit/attached/image/20191227/2019122716230919919.jpg';
    	$this->downloadFile($file);die;
    	echo basename($url);die;
    	$str = 'abc3243242dec';
    	$arr = ['abc', 'dec'];
    	print_r(str_replace($arr, '', $str));die;
    	var_dump($this->saveFile('https://www.bjtqcy.com/css/animate.css'));die;
    }
}
