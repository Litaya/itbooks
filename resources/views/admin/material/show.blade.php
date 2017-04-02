@extends('admin.layouts.frame')

@section('title','书圈-微信素材管理')
@section('content')
    <style>
        .category-item{
            padding:5px 10px;
            border-radius: 5px;
            font-size: 10px;
            border: 1px solid #33CC99;
            color:#33CC99;
            margin-right: 5px;
        }
        .smaller{
            font-size:12px;
        }
        #comment-box{
            background-color: #ffffff;
            box-shadow: 0 0 5px #cccccc;
        }
    </style>
    <div class="row" >
        <h4>
            雷军送武大一栋楼，武大回赠雷军一本书，它成就了雷军的百亿身价&nbsp;&nbsp;&nbsp;&nbsp;
            <small class="smaller">阅读: <a href="javascript:void(0)">0</a>&nbsp; 评论: <a href="javascript:void(0)">0</a>&nbsp; 收藏: <a href="javascript:void(0)">0</a>&nbsp; 来源: 微信</small>
        </h4>
        <p>
            <small style="margin-right: 20px; padding: 5px 0">分类：其他 <a href="javascript:void(0)" class="smaller">修改</a></small>
            <span class="category-item">标签1</span>
            <span class="category-item">标签2</span>
            <a href="javascript:void(0)"><span class="category-item"><i class="fa fa-plus"></i></span></a>
        </p>
        <hr>
    </div>
    <div class="row">
        <p>去年10月份，雷军向母校武汉大学捐赠了1亿元人民币用来建造科技楼。今天，武汉大学校长亲自到京拜访小米公司，并为雷军带了一份特殊的礼物。</p>
        <p>这份特殊的礼物虽然没有1亿元人民币那么贵重，但是正是它，成就了雷军的百亿身家。这就是雷军在武大就读时在图书馆借读的《硅谷之火》。</p>
        <p>雷军多次在采访中表示，点燃他创业梦想的正是这本书。</p>
        <p>《硅谷之火》通过一个个生动有趣的故事，讲述了个人计算机的发展史。其中比尔盖茨、乔布斯的故事激励了一代又一代的创业者。可以说，这本书记录了硅谷“激情燃烧的岁月”，也在当时引导着越来越多的年轻人将激情挥洒在硅谷这篇神奇的土地上。</p>
        <p>雷军曾表示，自己是在18岁的时候读到过这本书。在读书馆看完之后，他曾连续三个晚上辗转反侧，难以入眠。比尔盖茨、乔布斯的故事深深刺激着这位武大计算机系当时的学霸。</p>
        <p>根据雷军武大校友的回忆。刚进入大学的雷军表现出来的更多的是自己的天赋，上课随便一听，考试基本上就能拿满分。雷军至今仍然是武大计算机系《汇编语言及程序设计》这门功课唯一的两个满分获得者之一。当时的雷军喜欢看电影，每天做完功课基本上都泡在电影院中。</p>
        <p>但是，自从看完《硅谷之火》之后，根据同学的回忆，雷军整个人脱胎换骨。聪明的雷军变成了勤奋的雷军。这之后，雷军很少看电影，即便完成功课，依然泡在自习室学习，他用两年的时间修完了大学四年所有的学分，并且拿到所有奖学金。</p>
        <p>因为《硅谷之火》的影响，雷军大学里最常出现的地方就是计算机实验室。为了能长时间待在机房，雷军主动提出帮助导师编写程序。根据当时带过雷军的老师的回忆，当时的雷军往往在计算机房一待就是一个晚上，第二天上课依然精神饱满。很多人不明白中关村IT劳模为何会有如此旺盛的精力，可能勤奋也需要天赋吧。</p>
        <p><a href="javascript:void(0)" target="_blank">阅读原文</a></p>
        <hr>
    </div>
    <div class="row" id="comment-box">
        <div class="col-lg-12">
            <h4>评论列表</h4>
            <hr>
            <div class="col-lg-12" style="padding: 0;height: 70px;">
                <img src="/img/avatar.png" alt="" style="width:50px;height: 50px;border-radius: 25px; position: absolute; left: 0;"/>
                <p style="position: absolute;left: 70px;"><a href="javascript:void(0)">张馨如</a>：雷哥很给力
                    <br>
                    <small>2015-01-01&nbsp;12:32:12</small>
                </p>
            </div>
            <div class="col-lg-12" style="padding: 0;height: 70px;">
                <img src="/img/avatar.png" alt="" style="width:50px;height: 50px;border-radius: 25px; position: absolute; left: 0;"/>
                <p style="position: absolute;left: 70px;"><a href="javascript:void(0)">张馨如</a>回复<a href="javascript:void(0)">丛硕</a>：我同意
                    <br>
                    <small>2015-01-01&nbsp;12:32:12</small>
                </p>
            </div>
        </div>
    </div>
@endsection