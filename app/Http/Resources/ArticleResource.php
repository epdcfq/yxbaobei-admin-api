<?php
/**
 * 
 * 命令
 * php artisan make:resource ArticleContentResource
 * 
 */
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\ArticleContentResource;

class ArticleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // 获取Article主表数据
        $data = $this->getAttributes();
        if (isset($data['focus']) && $data['focus']) {
            $data['focus_url'] = env('APP_IMG_URL').$data['focus'];
        }
        // 拼接内容表
        $data['content'] = new ArticleContentResource($this->contents);

        return $data;
    }
}
