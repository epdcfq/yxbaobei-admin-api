<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ArticleContentResource extends Resource
{
    // 可返回的字段
    protected $filter = ['id', 'body'];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->getAttributes();
        return $this->filter ? array_intersect_key($data, array_flip($this->filter)) : $data;
    }
}
