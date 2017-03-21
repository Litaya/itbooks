<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/styles.css">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <title> @yield('title') </title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body>

    <div class="container">

        <div class="row">

        <div class="box" style="margin-top:50px;"> 
            <div class="picbox"> 
                <ul class="piclist mainlist"> 
                    <li><a href="#" target="_blank"><img src="images/1.jpg" width="220" height="105" /></a></li> 
                    <li><a href="#" target="_blank"><img src="images/2.jpg" /></a></li> 
                    <li><a href="#" target="_blank"><img src="images/3.jpg" /></a></li> 
                    <li><a href="#" target="_blank"><img src="images/4.jpg" /></a></li> 
                    <li><a href="#" target="_blank"><img src="images/1.jpg" /></a></li> 
                    <li><a href="#" target="_blank"><img src="images/2.jpg" /></a></li> 
                    <li><a href="#" target="_blank"><img src="images/3.jpg" /></a></li> 
                    <li><a href="#" target="_blank"><img src="images/4.jpg" /></a></li> 
                </ul> 
                <ul class="piclist swaplist"></ul> 
            </div> 
            <div class="og_prev"></div> 
            <div class="og_next"></div> 
        </div> 




        </div>

    </div>

<script src="/js/app.js"></script>
<script>
    $(document).ready(function(e) { 
        /***不需要自动滚动，去掉即可***/ 
        time = window.setInterval(function(){ 
            $('.og_next').click();   
        },5000); 
        /***不需要自动滚动，去掉即可***/ 
        linum = $('.mainlist li').length;//图片数量 
        w = linum * 250;//ul宽度 
        $('.piclist').css('width', w + 'px');//ul宽度 
        $('.swaplist').html($('.mainlist').html());//复制内容 
        
        $('.og_next').click(function(){ 
            
            if($('.swaplist,.mainlist').is(':animated')){ 
                $('.swaplist,.mainlist').stop(true,true); 
            } 
            
            if($('.mainlist li').length>4){//多于4张图片 
                ml = parseInt($('.mainlist').css('left'));//默认图片ul位置 
                sl = parseInt($('.swaplist').css('left'));//交换图片ul位置 
                if(ml<=0 && ml>w*-1){//默认图片显示时 
                    $('.swaplist').css({left: '1000px'});//交换图片放在显示区域右侧 
                    $('.mainlist').animate({left: ml - 1000 + 'px'},'slow');//默认图片滚动                 
                    if(ml==(w-1000)*-1){//默认图片最后一屏时 
                        $('.swaplist').animate({left: '0px'},'slow');//交换图片滚动 
                    } 
                }else{//交换图片显示时 
                    $('.mainlist').css({left: '1000px'})//默认图片放在显示区域右 
                    $('.swaplist').animate({left: sl - 1000 + 'px'},'slow');//交换图片滚动 
                    if(sl==(w-1000)*-1){//交换图片最后一屏时 
                        $('.mainlist').animate({left: '0px'},'slow');//默认图片滚动 
                    } 
                } 
            } 
        }) 
        $('.og_prev').click(function(){ 
            
            if($('.swaplist,.mainlist').is(':animated')){ 
                $('.swaplist,.mainlist').stop(true,true); 
            } 
            
            if($('.mainlist li').length>4){ 
                ml = parseInt($('.mainlist').css('left')); 
                sl = parseInt($('.swaplist').css('left')); 
                if(ml<=0 && ml>w*-1){ 
                    $('.swaplist').css({left: w * -1 + 'px'}); 
                    $('.mainlist').animate({left: ml + 1000 + 'px'},'slow');                 
                    if(ml==0){ 
                        $('.swaplist').animate({left: (w - 1000) * -1 + 'px'},'slow'); 
                    } 
                }else{ 
                    $('.mainlist').css({left: (w - 1000) * -1 + 'px'}); 
                    $('.swaplist').animate({left: sl + 1000 + 'px'},'slow'); 
                    if(sl==0){ 
                        $('.mainlist').animate({left: '0px'},'slow'); 
                    } 
                } 
            } 
        })     
    }); 
    
    $(document).ready(function(){ 
        $('.og_prev,.og_next').hover(function(){ 
                $(this).fadeTo('fast',1); 
            },function(){ 
                $(this).fadeTo('fast',0.7); 
        }) 
    
    }) 
</script>

</body>

</html>