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

	/** 
	 * 门店列表option
	 * @return    [type]      [description]
	 */
	public function orglist()
	{
		$result = [
			'options' => [
				[
					'label'=>'门店',
					'name'=>'keyword',
					'type'=>'input',
					'placeholder'=>'请输入门店名称',
					'span' => 10
				],
				[
					'label'=>'状态',
					'name'	=> 'status',
					'type'	=> 'select',
					'placeholder' => '状态',
					'span'=>8,
					'list' 	=> [
						['id'=>1, 'name'=>'有效'],
						['id'=>0, 'name'=>'已失效']
					]
				]
			],
			'query' => [
				'keyword' 	=> '',
				'status'	=> 1
			],
			'page'	=> $this->page,
			'per_page' => $this->per_page
		];

		return $this->success($result);
	}

	public function customerlist()
	{
		return $this->success();
	}
}