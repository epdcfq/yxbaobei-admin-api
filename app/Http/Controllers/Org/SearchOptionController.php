<?php 
/** 
 * 搜索项管理类
 * 
 */
namespace App\Http\Controllers\Org;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchOptionController extends Controller
{
	public function article(Request $request)
	{
		return $this->success([], 'search_option for article');
	}

	public function category(Request $request)
	{
		return $this->success([], 'search_option for article');
	}

	public function product(Request $request)
	{
		return $this->success([], 'search_option for article');
	}

}