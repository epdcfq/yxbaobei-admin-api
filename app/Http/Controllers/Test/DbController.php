<?php 
/** 
 * DB库测试类
 * 
 */

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;

class DbController extends Controller {
	public function index()
	{
		$t = new \App\Repositories\ArticleRepository(new \App\Models\ArticleModel());
		$result = $t->list();
		print_r($result);
	}
}