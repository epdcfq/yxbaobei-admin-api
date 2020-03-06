@extends('template.1.pc.layouts.main')

@section('content')

	@include('template.1.pc.layouts.index.focus')

<div class="index_p1" id="go_in">
	<div class="middle">
		<div class="home_title wow slideInDown">
			<h2 class="home_titlejc">业务范围</h2>
			<p class="home_titleyw">SERVICE COVERAGE</p>
			<p class="home_titlex"></p>
			<p class="home_titleslg">	从PC、手机网站到微信公众号、小程序；博维才科技为您打造全方位的互联网营销体系<br>定制一站式管理系统，快速提升企业营销额和品牌影响力，让您的营销更精准、灵活<br>
				众多的成熟行业案例,有效把控每一条商机,提升签单转化率
			</p>
		</div>
		<div class="syfw wow slideInUp">
			<dl>
				<dd></dd>
				<dd></dd>
				<dd></dd>
				<dd></dd>
			</dl>
			<ul class="syfwul">
				<p class="syfwul_img1"><img src="./images/index_fwfw_06.png"/></p>
				<li>
					<p class="syfwul_img"><img src="./images/index_fwfw_02.jpg"/></p>
					<div class="syfw_h3">
						<i></i>
						<h3>网站建设</h3>
						<em>
							网站定制开发设计，自适应响应式网页、H5、移动端开发，千万用户的信赖选择
						</em>
					</div>
					<div class="syfw_text">
						高端定制 打造高端品牌网站<br>
						范围：企业网站、门户网站、网页设计与原型制作、网站策划、功能站、商城网站、OA管理系统开发等
					</div>
				</li>

				<li>
					<p class="syfwul_img"><img src="./images/index_fwfw_04.jpg"/></p>
					<div class="syfw_h3">
						<i></i>
						<h3>微信开发</h3>
						<em>公众号+小程序一站式服务</em>
					</div>
					<div class="syfw_text">
						微信营销成本低廉、定位准确、方式多元化、人性化、信息到达率高，如此优势，让您告别传统的高成本营销推广，更加节约公司营销成本。
					</div>
				</li>
				<li>
					<p class="syfwul_img"><img src="./images/index_fwfw_03.jpg"/></p>
					<div class="syfw_h3">
						<i></i>
						<h3>网站运营</h3>
						<em>网络推广 网站代运营服务</em>
					</div>
					<div class="syfw_text">
						安全、稳定、持久的网站代运营服务，建立稳健的地基，做到无死角、精准营销，以承载企业网络品牌持续性发展。
					</div>
				</li>
				<li>
					<p class="syfwul_img"><img src="./images/index_fwfw_05.jpg"/></p>
					<div class="syfw_h3">
						<i></i>
						<h3>域名主机</h3>
						<em>域名注册 主机服务 ICP备案</em>
					</div>
					<div class="syfw_text">
						阿里云域名+服务器；免费备案；高端团队为您服务，大厂值得信赖。
					</div>
				</li>
			</ul>
		</div>
		<script>
			$(document).ready(function () {
				$(".syfw dl dd").mouseover(function () {
					var a = $(this).index();
					$('.syfw dl dd').removeClass('syfwd');
					$('.syfw dl dd').addClass('syfwd1');
					$(this).addClass('syfwd');
					$('.syfwul li').removeClass("active");
					$('.syfwul li').addClass("active1");
					$('.syfwul li:eq(' + a + ')').addClass("active");
					$('.syfwul_img1').hide();
				});
				$(".syfw dl dd").mouseout(function () {
					var a = $(this).index();
					$('.syfw dl dd').removeClass('syfwd');
					$('.syfw dl dd').removeClass('syfwd1');
					$(this).removeClass('syfwd');
					$('.syfwul li').removeClass("active");
					$('.syfwul li').removeClass("active1");
					$('.syfwul li:eq(' + a + ')').removeClass("active");
					$('.syfwul_img1').show();
				});
			});
		</script>
	</div>
</div>
<div class="syyh">
	<div class="middle">
		<h2 class="index_tc_h2">解决企业<span>“营销难”</span>和<span>“用户分散”</span>的难题</h2>
		<div class="syyh1">
			<h3 class="syyh1_h3">一站式营销体系 / <span style="font-size:12px;">CUSTOM MANAGEMENT SYSTEM</span></h3>
		</div>
		<ul class="syyh1_ul">
			<li>
				<span></span>
				<p>行业经验十多年</p>
				<b>我们深知长久稳定的为客户提供服务是我们对客户最大的负责，凭借十几年CRM行业经验为客户提供全面的顾问式服务。</b>
			</li>
			<li>
				<span></span>
				<p>网站+公众号+小程序</p>
				<b>企业营销利器，一站式系统，以用户体验为导向高端定制；打造微信生态圈，抢占微信10亿流量红利 </b>
			</li>
			<li>
				<span></span>
				<p>全站托管</p>
				<b>网站更新, 安全检测, 网站升级, 维护, 一条龙全方位服务</b>
			</li>
			<p class="clear"></p>
		</ul>
		<div class="syyh2">
			<ul class="syyh2_ul">
				<li class="fl">
					<p class="syyh2_ulp">
						<img src="./images/1903syyh_02.png"/>
						<span><i>关键词</i>关键词推广服务</span>
					</p>
					<p class="syyh2_ulp">
						<img src="./images/1903syyh_03.png"/>
						<span><i>营销</i>为营销而做网站</span>
					</p>
				</li>
				<li class="fr">
					<p class="syyh2_ulp">
						<img src="./images/1903syyh_04.png"/>
						<span><i>引流</i>为引流而做网站</span>
					</p>
					<p class="syyh2_ulp">
						<img src="./images/1903syyh_05.png"/>
						<span><i>品牌</i>全面提升企业品牌形象</span>
					</p>
				</li>
			</ul>
			<div class="syyh3">
				<div class="syyh3k">
					<img src="./images/1903syyh_06.png"/>
					<div class="syyh3_bc syyh3_bc1"><p></p></div>
					<div class="syyh3_bc syyh3_bc2"><p></p></div>
					<div class="syyh3_bc syyh3_bc3"><p></p></div>
					<div class="syyh3_bc syyh3_bc4"><p></p></div>
					<ul class="syyh3_nr">
						<li>关键词</li>
						<li>引流</li>
						<li>营销</li>
						<li>品牌</li>
						<p class="syyh3_jt"><img src="./images/1903syyh_07.png"/></p>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$(".syyh3_nr li").mouseover(function () {
			var a = $(this).index();
			$('.syyh3_nr li').removeClass('active');
			$(this).addClass('active');


			$('.syyh3_bc').removeClass("active");
			$('.syyh3_bc:eq(' + a + ')').addClass("active");
		});
	});
	$(document).ready(function () {
		$(".syyh3_bc:eq(0)").addClass('active');
		$(".syyh3_jt:eq(0)").addClass('active1');
		$(".syyh3_nr li:nth-child(1)").mouseover(function () {
			$(".syyh3_jt").addClass("active1");
			$(".syyh3_jt").removeClass("active2");
			$(".syyh3_jt").removeClass("active3");
			$(".syyh3_jt").removeClass("active4");
		});
		$(".syyh3_nr li:nth-child(2)").mouseover(function () {
			$(".syyh3_jt").addClass("active2");
			$(".syyh3_jt").removeClass("active1");
			$(".syyh3_jt").removeClass("active3");
			$(".syyh3_jt").removeClass("active4");
		});
		$(".syyh3_nr li:nth-child(3)").mouseover(function () {
			$(".syyh3_jt").addClass("active3");
			$(".syyh3_jt").removeClass("active1");
			$(".syyh3_jt").removeClass("active2");
			$(".syyh3_jt").removeClass("active4");
		});
		$(".syyh3_nr li:nth-child(4)").mouseover(function () {
			$(".syyh3_jt").addClass("active4");
			$(".syyh3_jt").removeClass("active1");
			$(".syyh3_jt").removeClass("active2");
			$(".syyh3_jt").removeClass("active3");
		});
	});
</script>

@endsection