<!--左侧浮动窗-->
    <script>
        $(document).ready(function () {
            $(".top_fh ul li").hover(function () {
                $(this).find(".right_1").stop().animate({"width": "150px"}, 200).css({
                    "opacity": "1",
                    "filter": "Alpha(opacity=100)",
                    "background": "#c1082b"
                })
            }, function () {
                $(this).find(".right_1").stop().animate({"width": "62px"}, 200).css({
                    "opacity": "0.8",
                    "filter": "Alpha(opacity=80)",
                    "background": "#656565"
                })
            });

        });

        function goTop() {
            $('html,body').animate({'scrollTop': 0}, 500);
        }
    </script>
    <div class="top_fh" id="zxqq1" style="display:block">
        <ul>
            <!-- <li><a href="./url.asp?u=http://p.qiao.baidu.com/cps/chat?siteId=00000&userId=00000" target="_blank"
                   class="right_1" rel="nofollow"><i>1</i><img src="./images/aa.gif" alt="在线咨询"/>&nbsp;&nbsp;&nbsp;点击咨询</a>
            </li> -->
            <li><a class="right_1 right_2"><img src="./images/wx.png" width="微信公众号"/>&nbsp;&nbsp;&nbsp;<img
                            src="./images/ewmg.png" class="xfimg" alt="微信公众号" width="130"/></a></li>
            <li><a href="tel://18810276686" class="right_1 telphone" rel="nofollow"><img src="./images/xr3.png" alt="联系电话"/>&nbsp;&nbsp;&nbsp;188-1027-6686</a>
            </li>
            <li><a href="javascript:goTop();" class="right_1" rel="nofollow"><img src="./images/xr4.png" alt="返回页面顶部"/>&nbsp;&nbsp;&nbsp;返回顶部</a>
            </li>
        </ul>
    </div>
    <script src="./js/wow.js"></script>
    <script>
        wow = new WOW(
            {
                animateClass: 'animated',
                offset: 100,
                callback: function (box) {
                    console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
                }
            }
        );
        wow.init();
        // document.getElementById('moar').onclick = function () {
        //     var section = document.createElement('section');
        //     section.className = 'section--purple wow fadeInDown';
        //     this.parentNode.insertBefore(section, this);
        // };
        $.each($('.telphone'), function(k,v){
            console.log(v, 'telphone')
        });
    </script>
    <!-- <script src="./js/foot_yqlj.js"></script> -->