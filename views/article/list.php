<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">
    <meta name="description" content="个人旅游地图管理"/>
    <meta name="keywords" content=""/>
    <meta content="旅游地图管理" name="鲁婷婷"/>
    <title>旅游地图管理-留下脚印</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/list.css?_=<?php echo time();?>">
    <link rel="stylesheet" href="../plugins/layui/css/layui.css">
    <script type="text/javascript" src="../plugins/jquery/jquery.js"></script>
</head>
<body id="body-info">
<div class="go-back">×</div>
    <iframe src="#" frameborder="0" class="frm-box" id="frm-box"></iframe>
</body>
<script type="text/javascript" src="../js/flexible.js"></script>
<script type="text/javascript">flexible(375,375);</script>
<script type="text/javascript" src="../plugins/layui/layui.all.js"></script>
<script>
    $().ready(
        function(){
            var account = "<?php echo $account;?>";
            var index   = "<?php echo $index;?>";

            var timer = setTimeout(
                function(){
                    il = layer.load(3, {shade: [0.1,'#000']});
                    request();
                },
                300
            );
            request = function(since_id,fy){
                if(!since_id) since_id = -1;
                if(!fy) fy = null;
                $.ajax({
                    url: '/article/list',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {account:account,index:index,since_id:since_id},
                    success: function(d){
                        if(d.status==0) {
                            if(d.data.length<1) {
                                layer.msg("没有更多数据啦。");
                                //$('#body-info').append('<div class="item-box">暂无数据</div><div class="item-line"></div>');
                                layer.close(il);
                                layer.close(fy);
                            } else
                                $.each(d.data,function(m,n){
                                    var str = '';
                                    str += '<div class="item-box get-item" data-id="'+n.id+'">';
                                    str +=   '<table class="item-table">';
                                    str +=      '<tr>';
                                    str +=          '<td class="item-img-box">';
                                    str +=             '<img src="http://39.106.28.143/img/article/2018/03/17/1521272438.1685aacc6762905b.thumb1.jpg" alt="">';
                                    str +=          '</td>';
                                    str +=          '<td>';
                                    str +=              '<div class="item-right-top">'+n.content+'</div>';
                                    str +=              '<div class="item-right-bottom">'+n.created+' 赞('+n.thumbs+') 评论('+n.comments+')</div>';
                                    str +=          '</td>';
                                    str +=      '</tr>';
                                    str +=  '</table>';
                                    str += '</div>';
                                    str += '<div class="item-line"></div>';
                                    $('#body-info').append(str);
                                    layer.close(il);
                                    layer.close(fy);
                                });
                        } else {
                            layer.msg(d.message);
                            layer.close(il);
                            layer.close(fy);
                        }
                    },
                    error: function(){
                        layer.msg('服务器内部错误。');
                        layer.close(il);
                        layer.close(fy);
                    }
                });
            }// request

            // 页面滚动到底部时分页加载
            $(window).scroll(function(){
                var sTop = $(this).scrollTop();
                var wHeight = $(window).height();
                var dHeight = $(document).height();
                if(parseInt(sTop) + parseInt(wHeight) >= parseInt(dHeight)) {
                    fy = 0;
                    layer.msg('loading...');
                    var since_id = $('.get-item:last').attr('data-id');
                    console.log(since_id);
                    request(since_id,fy);
                }
            });

            // 查看详情
            $(document).on('click',function(){
                var dload = layer.load(3, {shade: [0.1,'#000']});
                $('#frm-box').attr('src','/article/detail?account=xushunbin');
                $('#frm-box').on('load', function(){
                        dHeight = $(window).height();
                        layer.msg(dHeight);
                        $('body').css('overflow-y','hidden');
                        $('#frm-box').css('display','block');
                        $('.go-back').css('visibility','visible');
                        $('#frm-box').height(dHeight);
                        layer.close(dload);
                    }
                );

            });
            // 阻止冒泡
            $('.go-back').bind('click',function(e){
                e.stopPropagation();
                $(this).css('visibility','hidden');
                $('#frm-box').css('display','none');
                $('body').css('overflow-y','auto');
            });

        }// ready
    );


</script>
</html>