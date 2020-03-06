$(".xw_bt").click(function(){
				var i=$(this).index();
				console.log(i)
				$(".xw_bt a").css("color","#666")
				$(".xw_bt a").eq(i).css("color","#df5e1d")
			})