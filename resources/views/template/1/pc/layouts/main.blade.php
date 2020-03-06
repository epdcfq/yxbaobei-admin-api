<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="applicable-device" content="pc,mobile" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="format-detection" content="telephone=no">
    <meta name="Robots" contect="all" />
    <meta name="author" content="天晴创艺" />
    <title>{{ $_globals['orgInfo']['short_name'] }}</title>
    <meta name="description" content="关于我们" />
    <meta name="keywords" content="网站建设,网页制作" />
    <link rel="canonical" href="./about" />
    <link rel="alternate" type="application/rss+xml" title="RSS" href="./rss/" />
    <link rel="dns-prefetch" href="./"/>
    <link rel="stylesheet" type="text/css" href="./css/animate.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/css.css">
    <link rel="stylesheet" type="text/css" href="./css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="./css/response.css">
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery.color-rgba-patch.js"></script>
    <script type="text/javascript" src="./js/example.js"></script>
    <script>
        $(document).ready(function(){
            $(".yd_nav").click(function(){
                $(".header").addClass("headerh");
            });
            $(".headerhx").click(function(){
                $(".header").removeClass("headerh");
            });
        });
    </script>
</head>

<body>
    <!--加载头部视图-->
    @include('template.1.pc.layouts.header')

    @yield('content')
    
    @include('template.1.pc.layouts.footer')
    @include('template.1.pc.layouts.sidebar')
</body>
</html>
