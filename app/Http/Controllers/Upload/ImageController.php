<?php 
/**
 * 图片上传类
 * 
 */

namespace app\Http\Controllers\Upload;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UploadRepositories;

class ImageController extends Controller
{
	public function index(Request $request, $path)
	{
		$uploadObj = new UploadRepositories();
		$result = $uploadObj->uploadImg($request, $path);
		if ($result['code'] == 200) {
			return $this->success($result['data']);
		} else {
			return $this->fail($result['code'], $result['message']);
		}
	}
}