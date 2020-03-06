<?php 
/** 
 * 素材管理类
 * 
 */

namespace App\Repositories\Wechat;

use App\Repositories\BaseRepository;
use App\Repositories\Tools\EncryptTool;
use EasyWeChat\Kernel\Messages\Article;
use App\Models\WechatEventModel;
use App\Repositories\Wechat\WxAuthorizeRepository;

class WxMaterialRepository extends BaseRepository
{
	protected $wxevent;
	protected $wxauthorize;
	protected $app;

	public function __construct(WechatEventModel $wxevent, WxAuthorizeRepository $wxauthorize)
	{
		$this->wxevent = $wxevent;
		$this->wxauthorize = $wxauthorize;
		$this->app = app('wechat.official_account');
	}

	/******************** [1] 临时素材基本操作 ********************/
	// 上传临时素材
	public function uploadTmpImage($path)
	{
		return $this->app->media->uploadImage($path);
	}
	// 上传临时声音
	public function uploadTmpVoice($path)
	{
		return $this->app->media->uploadVoice($path);
	}
	// 上传临时视频
	public function uploadTmpVideo($path, $title, $description)
	{
		return $this->app->media->uploadVideo($path, $title='', $description='');
	}
	// 上传群发临时视频
	public function uploadTmpVideoForBroadcasting($path, $title='', $description='')
	{
		return $this->app->media->uploadVideoForBroadcasting($path, $title, $description);
	}
	// 创建群发消息
	public function createTmpVideoForBroadcasting($mediaId, $title='', $description='')
	{
		return $this->app->media->createVideoForBroadcasting($mediaId, $title, $description);
	}
	// 上传临时缩略图
	public function uploadTmpThumb($path)
	{
		return $this->app->media->uploadThumb($path);
	}
	// 根据meidaId获取临时素材内容
	public function getTmpMediaById($mediaId)
	{
		if (!$mediaId) {
			return [];
		}
		return $this->app->media->get($mediaId);
	}
	// 获取JSSDK上传的高清语音
	public function getTmpJssdkMeida($mediaId)
	{
		return $this->app->media->getJssdkMedia($mediaId);
	}

	/******************** [2] 永久素材基本操作 ********************/
	// 上传永久图片
	public function uploadForeverImage($path) 
	{
		return $this->app->material->uploadImage($path);
	}
	// 上传永久音频
	public function uploadForeverVoice($path) 
	{
		return $this->app->material->uploadVoice($path);
	}
	// 上传永久视频
	public function uploadForeverVideo($path) 
	{
		return $this->app->material->uploadVideo($path);
	}
	// 上传永久缩略图
	public function uploadForeverThumb($path) 
	{
		return $this->app->material->uploadThumb($path);
	}
	
	public function uploadForeverArticle($title, $thumb_media_id, $show_cover, $digest='', $content='', $author='', $source_url='')
	{
		$article = new Article([
			'title'				=> $title,
			'thumb_media_id'	=> $thumb_media_id,
			'show_cover'		=> $show_cover,
			'digest'			=> $digest,
			'content'			=> $content,
			'author'			=> $author,
			'source_url'		=> $source_url
		]);
		return $this->app->material->uploadArticle($article);
	}
	// 上传永久图文消息
	public function uploadForeverArticle($title, $thumb_media_id, $show_cover, $digest='', $content='', $author='', $source_url='')
	{
		$article = new Article([
			'title'				=> $title,
			'thumb_media_id'	=> $thumb_media_id,
			'show_cover'		=> $show_cover,
			'digest'			=> $digest,
			'content'			=> $content,
			'author'			=> $author,
			'source_url'		=> $source_url
		]);
		return $this->app->material->uploadArticle($article);
	}

	// 更新永久图文消息
	public function updateForeverArticle($mediaId, $title, $thumb_media_id, $show_cover, $digest='', $content='', $author='', $source_url='')
	{
		$article = new Article([
			'title'				=> $title,
			'thumb_media_id'	=> $thumb_media_id,
			'show_cover'		=> $show_cover,
			'digest'			=> $digest,
			'content'			=> $content,
			'author'			=> $author,
			'source_url'		=> $source_url
		]);
		return $this->app->material->updateArticle($mediaId, $article);
	}
	
}