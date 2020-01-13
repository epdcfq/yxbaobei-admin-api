<?php 
/** 
 * 上传相关基类
 * 
 */
namespace App\Repositories;

use Storage;
use Illuminate\Http\Request;

class UploadRepositories
{
	protected $basePath = './uploads';
	protected $allow_exts = [
		'image'=>['jpg','jpeg','png','gif']
	];
	//在控制器写一个上传方法
	public function uploadImg(Request $request, $path='')
    {
    	// 请求方式验证
    	if (!$request->isMethod('POST')) {
    		return [
        		'code'=> 1000,
        		'message' => '上传请求方式不正确'
        	];
    	}

    	// 上传文件有效性校验
        $tmp = $request->file('file');
    	//判断文件上传是否有效
        $fileType = $tmp->getClientOriginalExtension(); //获取文件后缀
        // 可信任上传类型
        $minmeType = $tmp->getClientMimeType();

        if (!$tmp->isValid()) {
        	return [
        		'code'=> 1001,
    			'message' => '上传文件安全未验证通过'
        	];
        }

        if (!$this->isAllowType($minmeType)) {
        	return [
        		'code'=> 1001,
    			'message' => $fileType.'此类型文件不允许上传('.$minmeType.')'
        	];
        }


        $FilePath = $tmp->getRealPath(); //获取文件临时存放位置
        $FileName = date('Ymd') . uniqid() . '.' . $fileType; //定义文件名

		//public下的上传目录
        $path = $this->buildPath($path, 'image'); 
        $result = false;
        $result = $tmp->move($path, $FileName); //存储文件

        $fullPath = ltrim($path . '/' . $FileName, '.');
        return [
            'code' 		=> 200,
            'message' 	=> 'success',
            'data'		=> [
	            'minmetype'	=> $minmeType,
	            'filetype'	=> $fileType,
	            'path' 		=> $fullPath, //文件路径
	            'fullUrl'	=> env('APP_IMG_URL', 'http://127.0.0.1:8000').$fullPath
            ]
        ];
    }

    protected function isAllowType($fileType, $allow_type='image') {
    	if (!strpos($fileType, '/')) {
    		return false;
    	}
    	list($type, $ext) = explode('/', strtolower($fileType));
    	if ($type == 'image' && in_array($ext, $this->allow_exts[$allow_type])) {
    		return true;
    	} else {
    		return false;
    	}
    }

    /**
     * 创建上传路径
     * 
     */
    protected function buildPath($path, $prefix)
    {
    	$path = $path ? $path : 'other';
    	$fullPath = $this->basePath . '/' . $prefix . '/' . $path . '/' . date('Ymd');
    	if (!is_dir($fullPath)) {
    		mkdir($fullPath, 0777, true);
    	}

    	return $fullPath;
    }

}