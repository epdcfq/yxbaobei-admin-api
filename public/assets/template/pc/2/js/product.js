var Product = {};
$(document).ready(function(){
	var baseUrl = "";
	var domain = document.domain;
    var pro_protocol = document.location.protocol+"//";
	baseUrl= pro_protocol+domain;
	
	Product.getPath = function(){
		var uri = location.href;
		uri = uri.substring(pro_protocol.length);
		var domain = document.domain + "/";
		var pathStr = uri.substring(domain.length,uri.length);
		var wi = pathStr.indexOf('?');
		if(wi != -1){
			//问号以前
			pathStr = pathStr.substring(0,wi);
		}
		var pi = pathStr.indexOf(".");
		if(pi!=-1){
			pathStr = pathStr.substring(0,pi);
		}
		var paths = pathStr.split('/');
		var len = paths.length;
		if(len == 2){
			Product.path=paths[0];
		}else{
			Product.path="";
		}
	}
	
	var searchPath = $("#searchPath")
	if((typeof searchPath == "undefined") || (searchPath == null) || (searchPath == '')){
		Product.getPath();
	}else{
		Product.path = searchPath.val();
	}

	Product.getPsearchUrl = function(){
        //return "/psearch.jspx?rn="+Math.random();
		return pro_protocol+locationDomain+"/ajaxPsearch.jspx?rn="+Math.random();
	}

	Product.getMPsUrl = function(){
        //return "/mPs.jsp?rn="+Math.random();
        return pro_protocol+locationDomain+"/ajaxMobiPsearch.jspx?rn="+Math.random();
	}
	
	/** 分类查询*/
	Product.queryProductByBrand = function(obj,id){
        var url = Product.getPsearchUrl();
		url = url+"&b="+id;
		//库
		var plib_id = $("#plib_id").val();
		if((typeof plib_id != "undefined") && (plib_id != null) && $.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}
	

	/** 分类查询*/
	Product.queryProductByType = function(obj,id,level,pid,libId){
        var url = Product.getPsearchUrl();
		if((typeof libId != "undefined") && (libId != null) && $.trim(libId).length>0){
			url = url+"&l="+libId;
		}
		url = url+"&t="+id;
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}

	/** 标签查询*/
	Product.queryProductByTag = function(obj,tagId,libId){
		var url = Product.getPsearchUrl();
		if((typeof libId != "undefined") && (libId != null) && $.trim(libId).length>0){
			url = url+"&l="+libId;
		}
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		url = url+"&tags="+tagId;
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}

	/** 购买项查询*/
	Product.queryProductByBuyItem = function(obj,buyId,libId){
		var url = Product.getPsearchUrl();
		if((typeof libId != "undefined") && (libId != null) && $.trim(libId).length>0){
			url = url+"&l="+libId;
		}
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		var bisArray = new Array();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			bisArray.push(pbis_ids);
		}
		bisArray.push(buyId);
		url = url+"&bis="+bisArray.join(",");
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}

	/** 价格区间查询*/
	Product.queryProductByPrice = function(obj){
		var pmin = $("#up_pmin").val();
		var pmax = $("#up_pmax").val();
		var url = Product.getPsearchUrl();
		//库
		var plib_id = $("#plib_id").val();
		if((typeof plib_id != "undefined") && (plib_id != null) && $.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceArray = new Array();
		if(pmin!=""&&parseFloat(pmin)>0){
			priceArray.push(pmin);
		}
		if(pmax!=""&&parseFloat(pmax)>0){
			priceArray.push(pmax);
		}
		if(priceArray.length>0){
			url = url +"&priceRange="+priceArray.join("_");
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}
	/** 品牌删除*/
	Product.delProductBrand = function(obj,brandId){
		var url = Product.getPsearchUrl();
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}

	/** 删除库*/
	Product.delProductLib = function(obj,libId){
		var url = Product.getPsearchUrl();
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		Product.goTo(url);
	}
	/** 删除分类*/
	Product.delProductType = function(obj,id,level,pid){
		var url = Product.getPsearchUrl();
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//分类
		if(level>1){
			url = url +"&t="+pid;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}
	/** 删除标签*/
	Product.delProductTag = function(obj,id){
		var url = Product.getPsearchUrl();
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		var tagArray = new Array();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			tagArray = ptag_ids.split(",");
			tagArray = $.map(tagArray,function(o){
				return o==id?null:o;
			});
			url = url+"&tags="+tagArray.join(",");
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}
	/** 删除购买项*/
	Product.delProductBuyItemVal = function(obj,id){
		var url = Product.getPsearchUrl();
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		var bisArray = new Array();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			bisArray = pbis_ids.split(",");
			bisArray = $.map(bisArray,function(o){
				return o===id?null:o;
			});
			url = url+"&bis="+bisArray.join(",");
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}
	/** 删除关键字*/
	Product.delKeyword = function(obj){
		var url = Product.getPsearchUrl();
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}
	/** 删除价格*/
	Product.delProductPrice = function(obj){
		var url = Product.getPsearchUrl();
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		Product.goTo(url);
	}
	/** 翻页*/
	Product.productGoToPage = function(obj,pageNo){
		var url = Product.getPsearchUrl();
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		url = url+"&pageNo="+pageNo;
		Product.goTo(url);
	}
	/** 排序*/
	Product.orderBy = function(obj,k,t){
		$(obj).addClass("selected").siblings().removeClass("selected");
		var skey = "1";
		var stype = "2";
		if((typeof k != "undefined") && (k != null) && $.trim(k).length>0){
			skey = k;
		}
		if((typeof t != "undefined") && (t != null) && $.trim(t).length>0){
			stype = t;
		}
		var url = Product.getPsearchUrl();
		//品牌
		var brandId = $("#pbrand_id").val();
		if(brandId!=""&&$.trim(brandId).length>0){
			url = url+"&b="+brandId;
		}
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(l).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		url = url + "&skey="+skey+"&stype="+stype;
		var pageNo = $("#p_pageNo").val();
		url = url + "&pageNo="+pageNo;
		Product.goTo(url);
	}

	Product.numKeyPress = function(obj){
		//先把非数字的都替换掉，除了数字和.
		obj.value = obj.value.replace(/[^\d.]/g,"");
		//必须保证第一个为数字而不是.
		obj.value = obj.value.replace(/^\./g,"");
		//保证只有出现一个.而没有多个.
		obj.value = obj.value.replace(/\.{2,}/g,".");
		//保证.只出现一次，而不能出现两次以上
		obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
		//只能输入两个小数
		obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');
	}
	
	/** 关键字查询*/
	Product.queryKeyword = function(obj,statics,keys){
		var  searchParam;
		if(keys){
			searchParam=keys;
		}else{
			searchParam= $("#"+obj).val();
		}
	   var q=$.trim(searchParam);
        if(q!=""){
            var url ="";
            if(statics&&statics==1){
                url= "/search/psearch.html?rn="+Math.random();
            }else{
                url= "/psearch.jspx?rn="+Math.random();
            }
            if(q&&url!=""){
                window.open(url+"&key=" +JRF.encodeUrl(JRF.encodeUrl(q)));
                //JRF.top.location.href = url+"&key=" +JRF.encodeUrl(JRF.encodeUrl(q));
            }
        }else{
            if(ismobi){
                JRF.layer("请输入搜索关键词！");
            }else{
                $.alert("请输入搜索关键词！");
            }
        }

	}

    Product.mobiQueryKeyword = function(obj,keys,static){
        var q=$.trim(keys);
        if(q!=""){
        	if(static && static=="1"){
        		var url= "/search/product_search.html?rn="+Math.random();
	            if(q&&url!=""){
	                var url = url+"&key=" +JRF.encodeUrl(JRF.encodeUrl(q));
	            }
	            window.location.href = url;
        	}else{
        		var url= "/mPs.jsp?rn="+Math.random();
	            if(q&&url!=""){
	                var url = url+"&key=" +JRF.encodeUrl(JRF.encodeUrl(q));
	            }
	            window.location.href = url;
        	}
        }else{
            JRF.layer("请输入搜索关键词！");
        }
    }



	
	/** 产品分类点击事件*/
	Product.mobiTypeClick = function(obj,libId,typeId){
		var url = Product.getMPsUrl()+"&from=wap";
		if((typeof libId != "undefined") && (libId != null) && $.trim(libId).length>0){
			url = url+"&l="+libId;
		}
		if((typeof typeId != "undefined") && (typeId != null) && $.trim(typeId).length>0){
			url = url+"&t="+typeId;
		}
		//window.location.href = url;
		Product.mobiProductQuery(url);
	}
	
	/** 手机产品搜索*/
	Product.mobiQueryProduct = function(){
		var path = $("#pchannel_path").val();
		if(!path||$.trim(path)==''){
			path = 'product';
		}
		var url = Product.getMPsUrl();
		var libId = $("#plib_id").val();
		if(libId!=""&&$.trim(libId).length>0){
			url = url+"&l="+libId;
		}
		//产品分类
		var typeId= $("#ptype_id").val();
		if(typeId!=""&&$.trim(typeId).length>0){
			url = url+"&t="+typeId;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		//window.location.href = url;
		Product.mobiProductQuery(url);
	}
	
	
	
	/** 翻页*/
	Product.mobiProductGoToPage = function(obj,pageNo){
		var path = $("#pchannel_path").val();
		if(!path||$.trim(path)==''){
			path = 'product';
		}
		var url = Product.getMPsUrl();
		//库
		var plib_id = $("#plib_id").val();
		if(plib_id!=""&&$.trim(plib_id).length>0){
			url = url+"&l="+plib_id;
		}
		//分类
		var ptype_id = $("#ptype_id").val();
		if(ptype_id!=""&&$.trim(ptype_id).length>0){
			url = url+"&t="+ptype_id;
		}
		//标签
		var ptag_ids = $("#ptag_ids").val();
		if(ptag_ids!=""&&$.trim(ptag_ids).length>0){
			url = url+"&tags="+ptag_ids;
		}
		//购买项
		var pbis_ids = $("#pbis_ids").val();
		if(pbis_ids!=""&&$.trim(pbis_ids).length>0){
			url = url+"&bis="+pbis_ids;
		}
		//价格
		var priceRange = $("#p_priceRange").val();
		if(priceRange!=""&&$.trim(priceRange).length>0){
			url = url+"&priceRange="+priceRange;
		}
		//关键字
		var key = $("#p_key").val();
		if(key!=""&&$.trim(key).length>0){
			url = url +"&key="+JRF.encodeUrl(JRF.encodeUrl(key));
		}
		//排序
		var p_skey = $("#p_skey").val();
		var p_stype = $("#p_stype").val();
		if((p_skey!=""&&$.trim(p_skey).length>0)&&(p_stype!=""&&$.trim(p_stype).length>0)){
			url = url +"&skey="+p_skey+"&stype="+p_stype;
		}
		url = url+"&pageNo="+pageNo;
		Product.mobiProductQuery(url);
		//window.location.href = url;
	}
	
	/** 手机内容页*/
	Product.mobiProductContent=function(pid){
		var url = "/mProduct.jsp?pid="+pid;
		window.location.href = url;
	}
	
	/** 类易迅分类效果*/
	Product.addTypeLayerBySimilarYiXun=function(obj,id,mcId){
		var layerId = "SecList_"+id;
		var left_height = $("#module"+mcId).find(".pro_list").height();
		if($('#'+layerId).length < 1){
			var data = $(obj).attr("data");
			var jsonData = jQuery.parseJSON(data);
			var html = new Array();
			$.each(jsonData,function(i,o){
				html.push('<div class="list_item ovh">');
				html.push('<span class="fl"><a href="javascript:void(0);" onclick="Product.queryProductByType(this,'+o.id+','+o.level+',0,'+o.libId+');">'+o.name+'：</a></span>');
				html.push('<ul class="fl">');
				if(o.children && o.children.length>0){
					$.each(o.children,function(j,p){
						html.push('<li><a href="javascript:void(0);" onclick="Product.queryProductByType(this,'+p.id+','+p.level+','+p.pid+','+p.libId+');">'+p.name+'</a></li>');
					});
				}
				html.push('</ul>');
				html.push('</div>');
			});
			var div = $('<div id="'+layerId+'" div-name="'+id+'" class="second_list03">'+html.join("")+'</div>');
			div.appendTo('body');
			var top = $(obj).parent().offset().top;
			var left = $(obj).parent().offset().left+$(obj).parent().width()-1;
			div.css('left',left);
			div.css("top", top);
			if(div.height()<left_height){
				div.css("height",left_height-22);
			}
			div.css("display","block");
			div.mouseover(function(){
				
			});
			div.mouseleave(function(){
				$("#module"+mcId).find("#li_"+mcId).removeClass('current');
				$("#"+layerId).remove();
			});
		}
	}
	
	/** 类1号店分类效果*/
	Product.addTypeLayerBySimilarOneShop = function(obj,id,mcId){
		var layerId = "SecList_"+id;
		var left_height = $("#module"+mcId).find(".pro_list").height();
		if($('#'+layerId).length < 1){
			var data = $(obj).attr("data");
			var jsonData = jQuery.parseJSON(data);
			var html = new Array();
			$.each(jsonData,function(i,o){
				if(i%2==0){
					html.push('<div class="ovh">');
				}
				html.push('<div class="list_item ovh">');
				html.push('<span class="fl"><a href="javascript:void(0);" onclick="Product.queryProductByType(this,'+o.id+','+o.level+',0,'+o.libId+');">'+o.name+'：</a></span>');
				html.push('<ul class="fl">');
				if(o.children && o.children.length>0){
					$.each(o.children,function(j,p){
						html.push('<li><a href="javascript:void(0);" onclick="Product.queryProductByType(this,'+p.id+','+p.level+','+p.pid+','+p.libId+');">'+p.name+'</a></li>');
					});
				}
				html.push('</ul>');
				html.push('</div>');
				if((i+1)%2==0||(i+1)==jsonData.length){
					html.push('</div>');
				}
			});
			var div = $('<div id="'+layerId+'" div-name="'+id+'" class="second_list02">'+html.join("")+'</div>');
			div.appendTo('body');
			var top = $(obj).parent().offset().top;
			var left = $(obj).parent().offset().left+$(obj).parent().width()-1;
			div.css('left',left);
			div.css("top", top);
			if(div.height()<left_height){
				div.css("height",left_height-20);
			}
			div.css("display","block");
			div.mouseover(function(){
				
			});
			div.mouseleave(function(){
				$("#module"+mcId).find("#li_"+mcId).removeClass('current');
				$("#"+layerId).remove();
			});
		}
	}
	
	/** 产品内容分页*/
	Product.ContentAjaxPage = function(mcId, channelId, pageNo) {
		var _replaceDocumentWriteText = function (b) {
	        var a = b.replace(/(.*?<script[\s\S]*?)document\.write([\s\S]*?<\/script>.*?)/gi, "$1void$2");
	        return a;
	    }
		
	    $('body').showLoading();
	    var url = pro_protocol+locationDomain+'/modulePage.jspx'
	    $.ajax({
			url: url,
	        type: 'post',
	        crossDomain: true,
            xhrFields:{withCredentials:true},
	        data: {
	            "mcId": mcId,
	            "channelId": channelId,
	            "pageNo": pageNo
	        },
	        success: function(data) {
	            $('body').hideLoading();
	            //获取当前模块对象
	            var module = JRF.dom.find("#module" + data.mcId);
	            //获取父级
	            var parent = $(module).parent();
	            //删除旧数据
	            $(module).remove();
	            parent.append(data.html);
	        }
	    });

	}
	
	Product.goTo = function(url){
		$.ajax({
			url:url,
			type:'post',
			dataType:'json',
			crossDomain: true,
            xhrFields:{withCredentials:true},
			success:function(data){
				$("#product_search_module").empty();
				$("#product_search_module").append(data.html);
                scroll(0,0);
                //判断管理员登录模式
                if (logon && logon == 1) {
                    addProductSearchStyleText();
                }
			}
		});
	}
	
	Product.mobiProductQuery = function(url){
		//window.location.href = url;
		$.ajax({
			url:url,
			type:'post',
			dataType:'json',
			crossDomain: true,
            xhrFields:{withCredentials:true},
			success:function(data){
                if(data.code=="200"){
                    $("#filTrate").animate({
                        'right':'-100%'
                    },100);
                    $("#filTrate .filetrate_btns").animate({
                        "right":'-100%'
                    },100);
                    $("#bodyDesign").css({
                        'overflow':'auto'
                    });
                    $("#shadow_win").fadeOut(500);
                    $("#web_product_search").empty();
                    $("#web_product_search").append(data.html);
                    $("#web_background").height("auto");
                    $("#web_product_search").height("auto");
                    scroll(0,0);
                    $(".shadow_win").height(document.body.scrollHeight);

                    // 滑屏
                    $("#filTrate").swipe({
                        swipe:function(event, direction, distance, duration, fingerCount) {
                            if(distance > 5 && duration > 6){
                                if(direction == "right"){
                                    var i = $(window).height();
                                    $("#filTrate").addClass('fadeOutRight');
                                    $("#mobiReviewPageFrame",parent.document).attr("scrolling","yes");
                                    $(".shadow_win").fadeOut(500);
                                }
                            }
                        }
                    });

                    $("#product_search_content").swipe({
                        swipe:function(event, direction, distance, duration, fingerCount) {
                            if(distance > 5 && duration > 6){
                                if(direction == "left"){
                                    var i = $(window).height();
                                    $("#filTrate").removeClass('fadeOutRight').addClass('fadeInRight').height(i);
                                    $("#mobiReviewPageFrame",parent.document).attr("scrolling","no");
                                    $(".shadow_win").fadeIn(500);
                                }
                            }
                        }
                    });
                }
			}
		});
	}
	
	Product.getRequest = function(){
		var url=location.search;
		var Request = new Object();
		if(url.indexOf("?")!=-1)
		{
			var str = url.substr(1);//去掉?号
			strs = str.split("&");
			for(var i=0;i<strs.length;i++)
			{
				Request[strs[i ].split("=")[0]]=unescape(strs[ i].split("=")[1]);
			}
		}
		var url = pro_protocol+locationDomain+'/ajaxPsearch.jspx?rn='+Math.random()+"key="+JRF.encodeUrl(JRF.encodeUrl(Request['key']));
		$.ajax({
			url:url,
			type:'post',
			dataType:'json',
			crossDomain: true,
            xhrFields:{withCredentials:true},
			success:function(data){
				$("#product_search_module").empty();
				$("#product_search_module").append(data.html);
				scroll(0,0);
                //判断管理员登录模式
                if (logon && logon == 1) {
                    addProductSearchStyleText();
                }
			}
		});
	}

    Product.getMobiRequest = function(){
        var url=location.search;
        var Request = new Object();
        if(url.indexOf("?")!=-1)
        {
            var str = url.substr(1);//去掉?号
            strs = str.split("&");
            for(var i=0;i<strs.length;i++)
            {
                Request[strs[i ].split("=")[0]]=unescape(strs[ i].split("=")[1]);
            }
        }
        var url = pro_protocol+locationDomain+'/ajaxMobiPsearch.jspx?rn='+Math.random()+"&key="+JRF.encodeUrl(JRF.encodeUrl(Request['key']));
        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            crossDomain: true,
            xhrFields:{withCredentials:true},
            success:function(data){
                $("#web_product_search").empty();
                $("#web_product_search").append(data.html);
                $("#up_search_keyword").val(JRF.decodeUrl(Request['key']));
                $("#web_background").height("auto");
                $("#web_product_search").height("auto");
                scroll(0,0);
                $(".shadow_win").height(document.body.scrollHeight);
            }
        });
    }

});