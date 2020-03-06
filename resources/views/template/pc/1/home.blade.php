@extends('template.pc.1.layouts.main')
@section('body_head')
	<div class="header">
	  <div class="headertop container">
	    <div class="logo"><a class="logo_url" href="/" ><img src="{{$assetsPath}}/img/logo-boweixue.png" ></a> </div>
	    <div class="logo_right">
	      <div class="headnav">
	        <ul id="nav" class="nav clearfix">
	          <li class="nLi">
	            <h3><a href="/" class='cur' >网站首页</a></h3>
	          </li>
	          <li class="nLi">
	            <h3><a class="" href="/a/guanyuwomen/">关于我们</a></h3>
	          </li><li class="nLi">
	            <h3><a class="" href="/a/chanpinzhongxin/">产品中心</a></h3>
	          </li><li class="nLi">
	            <h3><a class="" href="/a/xinwenzhongxin/">新闻中心</a></h3>
	          </li><li class="nLi">
	            <h3><a class="" href="/a/rongyuzizhi/">荣誉资质</a></h3>
	          </li><li class="nLi">
	            <h3><a class="" href="/a/lianxiwomen/">联系我们</a></h3>
	          </li>
	        </ul>
	        <script id="jsID" type="text/javascript">
				
				jQuery("#nav").slide({ 
					type:"menu",// 效果类型，针对菜单/导航而引入的参数（默认slide）
					titCell:".nLi", //鼠标触发对象
					targetCell:".sub", //titCell里面包含的要显示/消失的对象
					effect:"slideDown", //targetCell下拉效果
					delayTime:300 , //效果时间
					triggerTime:0, //鼠标延迟触发时间（默认150）
					returnDefault:true //鼠标移走后返回默认状态，例如默认频道是“预告片”，鼠标移走后会返回“预告片”（默认false）
				});
			</script> 
	      </div>
	      <div class="head_tel">188-1027-6686</div>
	    </div>
	  </div>
	</div>
@endsection
