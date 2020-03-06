<div class="header">
    <div class="yd_nav"><span></span> <span></span> <span></span></div>
    <div class="headerhx">&times;</div>
    <div class="middle"><a href="./" title="网站建设">
            <h1 class="logo">网站建设</h1>
        </a>
        <div class="nav_nav_logo"></div>
        <div class="headerfr">
            <div class="top">
                <p class="top1">{{ $_globals['orgInfo']['line_phone'] }}</p>
                <p class="top3"><i class="iconfont">&#xe631;</i> <span><img src="./images/web_shouji.jpg"
                                                                            alt="手机版"/></span></p>
                <p class="top3"><i class="iconfont">&#xe65d;</i> <span><img src="./images/weinxin_tq.jpg"
                                                                            alt="公众号"/></span></p>
                <p class="clear"></p>
            </div>
            <div class="m_nav">
                <div class="nav-wrap">
                    <ul class="group" id="example-one">
                        @foreach($_globals['orgInfo']['templates'] as $item)
                        @if(!$item['status'])
                            @continue;
                        @endif
                        @if($item['nav'] && !$item['parent_id'])
                            @if($item['id']==$_globals['curNav']['id'])
                            <li  class="current_page_item"><a href="{{ $item['route']}}" title="{{ $item['zh_name'] }}">{{ $item['zh_name'] }} </a></li>
                            @else
                            <li><a href="{{ $item['route']}}" title="{{ $item['zh_name'] }}">{{ $item['zh_name'] }} </a></li>
                            @endif
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
