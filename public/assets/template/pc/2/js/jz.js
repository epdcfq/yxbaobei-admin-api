/**
 * Created by Administrator on 19-10-31.
 */
(function(){
    try {
        var g = document
            , f = window
            , n = encodeURIComponent
            , r = "unknow"
            , l = null
            , t = function() {
                this.c()
            };
        t.prototype = {
            c: function() {
                var a = f.navigator.userAgent;
                l = g.documentElement && 0 !== g.documentElement.clientHeight ? g.documentElement : g.body;
                a = a ? a.toLowerCase().replace(/-/g, "") : "";
                for (var b = "netscape;se 1.;se 2.;saayaa;360se;tencent;qqbrowser;mqqbrowser;maxthon;myie;theworld;konqueror;firefox;chrome;safari;msie 5.0;msie 5.5;msie 6.0;msie 7.0;msie 8.0;msie 9.0;msie 10.0;Mozilla;opera".split(";"), d = 0; d < b.length; d += 1)
                    if (-1 !== a.indexOf(b[d])) {
                        r = b[d];
                        break
                    }
                var j =[];
                j.push("referrer=" + n(g.referrer));
                j.push("page=" + n(f.location.href));
                j.push("title=" + n(g.title));
                j.push("host=" + n(f.location.host));
                j.push("path=" + n(f.location.pathname));
                j.push("browser="+r);
                this.b(f.location.pathname,j);

                var h = new Image;
                h.onload = h.onerror = h.onabort = function() {
                    h = h.onload = h.onerror = h.onabort = null
                };
                h.src = "/stat.jspx?" + j.join("&")+ "&rnd=" + Math.floor(2147483648 * Math.random());
            },
            b: function(pathname,j){

                var pathType = 0  // 0 其他   1 首页  2栏目  3 文章 4 产品
                    ,pageType = 1;// 1 pc页面  2 手机页面

                if(/^\/(index\.(j)?html)?$/.test(pathname) || /^\/m[iI]ndex\.(html|jsp)$/.test(pathname)){  //首页
                    pathType = 1;
                    if(/^\/m[iI]ndex\.(html|jsp)$/.test(pathname)){
                        pageType = 2;
                    }
                }else if(/^\/[^/]*\/(index\.(j)?html)?$/.test(pathname) || /^\/mChannel(_|\/)\d+\.(j)?html$/.test(pathname)){ //栏目
                    pathType = 2;
                    if(/^\/mChannel(_|\/)\d+\.(j)?html$/.test(pathname)){
                        pageType = 2;
                        var channelId = pathname.match(/^\/mChannel(?:_|\/)(\d+)\.(j)?html$/)[1];
                        j.push("channelId=" + channelId);
                    }else{
                        var channelPath = pathname.match(/^\/([^/]*)\/(index\.(j)?html)?$/)[1];
                        j.push("channelPath=" + channelPath);
                    }
                }else if(/^\/[^/]*\/(\d+)\.(j)?html$/.test(pathname) || /^\/mContent(?:_|\/)(\d+)\.(j)?html$/.test(pathname)) { //文章
                    pathType = 3;
                    if(/^\/mContent(?:_|\/)(\d+)\.(j)?html$/.test(pathname)){
                        pageType = 2;
                        var contentId = pathname.match(/^\/mContent(?:_|\/)(\d+)\.(j)?html$/)[1];
                        j.push("contentId=" + contentId);
                    }else{
                        var contentId = pathname.match(/^\/[^/]*\/(\d+)\.(j)?html$/)[1];
                        j.push("contentId=" + contentId);
                    }
                }else if(/^\/[^/]*\/product_(\d+)\.(j)?html$/.test(pathname) || /^\/mProduct(?:_|\/)(\d+)\.(j)?html$/.test(pathname)) { //产品
                    pathType = 4;
                    if(/^\/mProduct(?:_|\/)(\d+)\.(j)?html$/.test(pathname)){
                        pageType = 2;
                        var contentId = pathname.match(/^\/mProduct(?:_|\/)(\d+)\.(j)?html$/)[1];
                        j.push("contentId=" + contentId);
                    }else{
                        var contentId = pathname.match(/^\/[^/]*\/product_(\d+)\.(j)?html$/)[1];
                        j.push("contentId=" + contentId);
                    }

                }
                j.push("pathType=" + pathType);
                j.push("pageType=" + pageType);
            }
        };
        new t
    } catch (a) {}

})();