@extends('admin.layouts.frame')

@section('title','书圈-微信素材管理')
@section('content')
    <style>
        #pages{
            text-align: center;
        }
        #pages > .item{
            padding:5px 10px;
            color: #999;
            background-color: white;
            box-shadow: 1px 1px 3px #ccc;
        }
        #pages > .active{
            padding:5px 10px;
            color: #999;
            background-color: #FAFAFA;
            box-shadow: inset 0 0 3px #ccc;
        }
        #start_datetime,#end_datetime{
            border:1px solid #cccccc;
            background-color: #FAFAFA;
            box-shadow: none;
        }
        .datetimepicker{padding:4px;margin-top:1px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;direction:ltr}.datetimepicker-inline{width:220px}.datetimepicker.datetimepicker-rtl{direction:rtl}.datetimepicker.datetimepicker-rtl table tr td span{float:right}.datetimepicker-dropdown,.datetimepicker-dropdown-left{top:0;left:0}[class*=" datetimepicker-dropdown"]:before{content:'';display:inline-block;border-left:7px solid transparent;border-right:7px solid transparent;border-bottom:7px solid #ccc;border-bottom-color:rgba(0,0,0,0.2);position:absolute}[class*=" datetimepicker-dropdown"]:after{content:'';display:inline-block;border-left:6px solid transparent;border-right:6px solid transparent;border-bottom:6px solid #fff;position:absolute}[class*=" datetimepicker-dropdown-top"]:before{content:'';display:inline-block;border-left:7px solid transparent;border-right:7px solid transparent;border-top:7px solid #ccc;border-top-color:rgba(0,0,0,0.2);border-bottom:0}[class*=" datetimepicker-dropdown-top"]:after{content:'';display:inline-block;border-left:6px solid transparent;border-right:6px solid transparent;border-top:6px solid #fff;border-bottom:0}.datetimepicker-dropdown-bottom-left:before{top:-7px;right:6px}.datetimepicker-dropdown-bottom-left:after{top:-6px;right:7px}.datetimepicker-dropdown-bottom-right:before{top:-7px;left:6px}.datetimepicker-dropdown-bottom-right:after{top:-6px;left:7px}.datetimepicker-dropdown-top-left:before{bottom:-7px;right:6px}.datetimepicker-dropdown-top-left:after{bottom:-6px;right:7px}.datetimepicker-dropdown-top-right:before{bottom:-7px;left:6px}.datetimepicker-dropdown-top-right:after{bottom:-6px;left:7px}.datetimepicker>div{display:none}.datetimepicker.minutes div.datetimepicker-minutes{display:block}.datetimepicker.hours div.datetimepicker-hours{display:block}.datetimepicker.days div.datetimepicker-days{display:block}.datetimepicker.months div.datetimepicker-months{display:block}.datetimepicker.years div.datetimepicker-years{display:block}.datetimepicker table{margin:0}.datetimepicker td,.datetimepicker th{text-align:center;width:20px;height:20px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;border:0}.table-striped .datetimepicker table tr td,.table-striped .datetimepicker table tr th{background-color:transparent}.datetimepicker table tr td.minute:hover{background:#eee;cursor:pointer}.datetimepicker table tr td.hour:hover{background:#eee;cursor:pointer}.datetimepicker table tr td.day:hover{background:#eee;cursor:pointer}.datetimepicker table tr td.old,.datetimepicker table tr td.new{color:#999}.datetimepicker table tr td.disabled,.datetimepicker table tr td.disabled:hover{background:0;color:#999;cursor:default}.datetimepicker table tr td.today,.datetimepicker table tr td.today:hover,.datetimepicker table tr td.today.disabled,.datetimepicker table tr td.today.disabled:hover{background-color:#fde19a;background-image:-moz-linear-gradient(top,#fdd49a,#fdf59a);background-image:-ms-linear-gradient(top,#fdd49a,#fdf59a);background-image:-webkit-gradient(linear,0 0,0 100%,from(#fdd49a),to(#fdf59a));background-image:-webkit-linear-gradient(top,#fdd49a,#fdf59a);background-image:-o-linear-gradient(top,#fdd49a,#fdf59a);background-image:linear-gradient(to bottom,#fdd49a,#fdf59a);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fdd49a',endColorstr='#fdf59a',GradientType=0);border-color:#fdf59a #fdf59a #fbed50;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.datetimepicker table tr td.today:hover,.datetimepicker table tr td.today:hover:hover,.datetimepicker table tr td.today.disabled:hover,.datetimepicker table tr td.today.disabled:hover:hover,.datetimepicker table tr td.today:active,.datetimepicker table tr td.today:hover:active,.datetimepicker table tr td.today.disabled:active,.datetimepicker table tr td.today.disabled:hover:active,.datetimepicker table tr td.today.active,.datetimepicker table tr td.today:hover.active,.datetimepicker table tr td.today.disabled.active,.datetimepicker table tr td.today.disabled:hover.active,.datetimepicker table tr td.today.disabled,.datetimepicker table tr td.today:hover.disabled,.datetimepicker table tr td.today.disabled.disabled,.datetimepicker table tr td.today.disabled:hover.disabled,.datetimepicker table tr td.today[disabled],.datetimepicker table tr td.today:hover[disabled],.datetimepicker table tr td.today.disabled[disabled],.datetimepicker table tr td.today.disabled:hover[disabled]{background-color:#fdf59a}.datetimepicker table tr td.today:active,.datetimepicker table tr td.today:hover:active,.datetimepicker table tr td.today.disabled:active,.datetimepicker table tr td.today.disabled:hover:active,.datetimepicker table tr td.today.active,.datetimepicker table tr td.today:hover.active,.datetimepicker table tr td.today.disabled.active,.datetimepicker table tr td.today.disabled:hover.active{background-color:#fbf069}.datetimepicker table tr td.active,.datetimepicker table tr td.active:hover,.datetimepicker table tr td.active.disabled,.datetimepicker table tr td.active.disabled:hover{background-color:#006dcc;background-image:-moz-linear-gradient(top,#08c,#04c);background-image:-ms-linear-gradient(top,#08c,#04c);background-image:-webkit-gradient(linear,0 0,0 100%,from(#08c),to(#04c));background-image:-webkit-linear-gradient(top,#08c,#04c);background-image:-o-linear-gradient(top,#08c,#04c);background-image:linear-gradient(to bottom,#08c,#04c);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0088cc',endColorstr='#0044cc',GradientType=0);border-color:#04c #04c #002a80;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false);color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25)}.datetimepicker table tr td.active:hover,.datetimepicker table tr td.active:hover:hover,.datetimepicker table tr td.active.disabled:hover,.datetimepicker table tr td.active.disabled:hover:hover,.datetimepicker table tr td.active:active,.datetimepicker table tr td.active:hover:active,.datetimepicker table tr td.active.disabled:active,.datetimepicker table tr td.active.disabled:hover:active,.datetimepicker table tr td.active.active,.datetimepicker table tr td.active:hover.active,.datetimepicker table tr td.active.disabled.active,.datetimepicker table tr td.active.disabled:hover.active,.datetimepicker table tr td.active.disabled,.datetimepicker table tr td.active:hover.disabled,.datetimepicker table tr td.active.disabled.disabled,.datetimepicker table tr td.active.disabled:hover.disabled,.datetimepicker table tr td.active[disabled],.datetimepicker table tr td.active:hover[disabled],.datetimepicker table tr td.active.disabled[disabled],.datetimepicker table tr td.active.disabled:hover[disabled]{background-color:#04c}.datetimepicker table tr td.active:active,.datetimepicker table tr td.active:hover:active,.datetimepicker table tr td.active.disabled:active,.datetimepicker table tr td.active.disabled:hover:active,.datetimepicker table tr td.active.active,.datetimepicker table tr td.active:hover.active,.datetimepicker table tr td.active.disabled.active,.datetimepicker table tr td.active.disabled:hover.active{background-color:#039}.datetimepicker table tr td span{display:block;width:23%;height:54px;line-height:54px;float:left;margin:1%;cursor:pointer;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}.datetimepicker .datetimepicker-hours span{height:26px;line-height:26px}.datetimepicker .datetimepicker-hours table tr td span.hour_am,.datetimepicker .datetimepicker-hours table tr td span.hour_pm{width:14.6%}.datetimepicker .datetimepicker-hours fieldset legend,.datetimepicker .datetimepicker-minutes fieldset legend{margin-bottom:inherit;line-height:30px}.datetimepicker .datetimepicker-minutes span{height:26px;line-height:26px}.datetimepicker table tr td span:hover{background:#eee}.datetimepicker table tr td span.disabled,.datetimepicker table tr td span.disabled:hover{background:0;color:#999;cursor:default}.datetimepicker table tr td span.active,.datetimepicker table tr td span.active:hover,.datetimepicker table tr td span.active.disabled,.datetimepicker table tr td span.active.disabled:hover{background-color:#006dcc;background-image:-moz-linear-gradient(top,#08c,#04c);background-image:-ms-linear-gradient(top,#08c,#04c);background-image:-webkit-gradient(linear,0 0,0 100%,from(#08c),to(#04c));background-image:-webkit-linear-gradient(top,#08c,#04c);background-image:-o-linear-gradient(top,#08c,#04c);background-image:linear-gradient(to bottom,#08c,#04c);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0088cc',endColorstr='#0044cc',GradientType=0);border-color:#04c #04c #002a80;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false);color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25)}.datetimepicker table tr td span.active:hover,.datetimepicker table tr td span.active:hover:hover,.datetimepicker table tr td span.active.disabled:hover,.datetimepicker table tr td span.active.disabled:hover:hover,.datetimepicker table tr td span.active:active,.datetimepicker table tr td span.active:hover:active,.datetimepicker table tr td span.active.disabled:active,.datetimepicker table tr td span.active.disabled:hover:active,.datetimepicker table tr td span.active.active,.datetimepicker table tr td span.active:hover.active,.datetimepicker table tr td span.active.disabled.active,.datetimepicker table tr td span.active.disabled:hover.active,.datetimepicker table tr td span.active.disabled,.datetimepicker table tr td span.active:hover.disabled,.datetimepicker table tr td span.active.disabled.disabled,.datetimepicker table tr td span.active.disabled:hover.disabled,.datetimepicker table tr td span.active[disabled],.datetimepicker table tr td span.active:hover[disabled],.datetimepicker table tr td span.active.disabled[disabled],.datetimepicker table tr td span.active.disabled:hover[disabled]{background-color:#04c}.datetimepicker table tr td span.active:active,.datetimepicker table tr td span.active:hover:active,.datetimepicker table tr td span.active.disabled:active,.datetimepicker table tr td span.active.disabled:hover:active,.datetimepicker table tr td span.active.active,.datetimepicker table tr td span.active:hover.active,.datetimepicker table tr td span.active.disabled.active,.datetimepicker table tr td span.active.disabled:hover.active{background-color:#039}.datetimepicker table tr td span.old{color:#999}.datetimepicker th.switch{width:145px}.datetimepicker th span.glyphicon{pointer-events:none}.datetimepicker thead tr:first-child th,.datetimepicker tfoot th{cursor:pointer}.datetimepicker thead tr:first-child th:hover,.datetimepicker tfoot th:hover{background:#eee}.input-append.date .add-on i,.input-prepend.date .add-on i,.input-group.date .input-group-addon span{cursor:pointer;width:14px;height:14px}
    </style>
    {{-- 页头： 阅读量、同步列表等组件 --}}
    <div class="row">
        <div class="col-lg-12">
            <small style="color:gray">今日阅读:23 &nbsp;&nbsp;今日评论:12</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <form action="#" class="form-inline" style="display: inline;">
                <div class="form-group">
                    <label for="start_datetime">起始时间</label>
                    <input type="text" class="form-control" name="start_datetime" value="{{ date("Y-m-d H:i",time()-86400) }}" readonly id="start_datetime">
                </div>
                <div class="form-group">
                    <label for="end_datetime">结束时间</label>
                    <input type="text" class="form-control" name="end_datetime" value="{{ date("Y-m-d H:i",time()) }}" readonly id="end_datetime">
                </div>
            </form>
            <button class="btn btn-success btn-sm" onclick="syncNews('{{ route('admin.material.sync') }}')" id="btn-sync" style="position: absolute; right: 10px;">同步列表</button>
        </div>
    </div>

    {{-- 搜索框 --}}
    <div class="row">
        <hr>
        <form action="{{ route('admin.material.index') }}" class="form-horizontal" method="get">
            <input type="text" name="search" class="form-control" placeholder="请输入标题、正文、或作者搜索">
        </form>
    </div>
    @if(!empty($search))
        <div class="row" style="margin-bottom: 20px;">
            <p style="font-size: 14px; color: #aaaaaa">
                当前搜索内容：{{ $search }}&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{{ route("admin.material.index") }}">清空搜索</a>
            </p>
        </div>
    @else
        <div class="row" style="min-height: 20px;"></div>
    @endif

    {{-- 搜索结果/首页结果 --}}
    @foreach($materials as $material)
        <div class="row" style="background-color: #ffffff; box-shadow:0 0 5px #ccc;margin-bottom: 10px;">
            <div class="col-lg-2" style="padding-left: 0;">
                <a href="javascript:void(0)"><img src="{{ $material->cover_path }}" alt="" height="100px;" width="100%;"></a>
            </div>
            <div class="col-lg-10" style="padding: 10px 0 0 0;height: 100px;">
                <p>
                    <a href="{{ route('admin.material.show',$material->id) }}">{{ $material->title }}</a>
                    <span  style="position: absolute;right: 10px;">
                        @if($material->display == 1)
                            <a href="javascript:void(0)"
                               onclick="set_display('{{ route("admin.material.set_display",$material->id) }}',2)">
                            <small><i class="fa fa-cog"></i> 设为微信文章</small>
                        </a>
                        @elseif($material->display == 2)
                            <a href="javascript:void(0)"
                               onclick="set_display('{{ route("admin.material.set_display",$material->id) }}',1)">
                            <small><i class="fa fa-cog"></i> 设为系统文章</small>
                        </a>
                        @endif
                        &nbsp;&nbsp;
                        <small><a href="javascript:void(0)" style="color:red"
                            onclick="drop_material('{{ route("admin.material.drop",$material->id) }}')"><i class="fa fa-times"></i> 删除文章</a></small>
                    </span>
                </p>
                <small>{{ $material->digest }}</small><br>
                <small style="position: absolute;bottom:5px; color:#ccc">
                    阅读: {{ $material->reading_quantity }}&nbsp; 评论: <?php echo sizeof($material->comments) ?>
                    &nbsp;&nbsp;
                    @if($material->display == 1)
                        <strong style="color: orange">系统文章</strong>
                    @elseif($material->display == 2)
                        <strong style="color: green">微信文章</strong>
                    @endif
                </small>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <small>
                </small>
                <small style="position: absolute;bottom:5px; right: 15px; color:#ccc">{{ $material->wechat_update_time }}</small>
            </div>
        </div>
    @endforeach

    <div class="row" id="pages">
        {{ $materials->appends(Input::except('page'))->links('vendor.pagination.default')  }}
    </div>

    <script type="text/javascript" src="/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript">
        function syncNews(url) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var start_time = Date.parse($("#start_datetime").val());
            var end_time   = Date.parse($("#end_datetime").val());
            if(start_time > end_time){
                alert("起始时间不能晚于结束时间");
            }else{
                $("#btn-sync").attr('disabled','disabled');
                $("#btn-sync").html('正在同步...');
                $.ajax({
                    method:'post',
                    url:url,
                    data: {
                        _token: CSRF_TOKEN,
                        start_time: start_time,
                        end_time: end_time
                    },
                    success:function () {
                        $("#btn-sync").removeAttr('disabled');
                        $("#btn-sync").html('同步列表');
                        location.reload();
                    }
                });
            }
        }

        ;(function($){
            $.fn.datetimepicker.dates['zh-CN'] = {
                days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
                daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
                daysMin:  ["日", "一", "二", "三", "四", "五", "六", "日"],
                months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                today: "今天",
                suffix: [],
                meridiem: ["上午", "下午"]
            };
        }(jQuery));

        $('#start_datetime').datetimepicker({
            language:  'zh-CN',
            format: 'yyyy-mm-dd hh:ii'
        });
        $('#end_datetime').datetimepicker({
            language:  'zh-CN',
            format: 'yyyy-mm-dd hh:ii'
        });

        function set_display(url,display) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                method:'post',
                url:url,
                data: {
                    _token: CSRF_TOKEN,
                    display: display
                },
                success:function () {
                    location.reload();
                }
            });
        }

        function drop_material(url){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            console.log(url,CSRF_TOKEN);
            $.ajax({
                method:'post',
                url:url,
                data: {
                    _token: CSRF_TOKEN
                },
                success:function () {
                    location.reload();
                }
            });
        }
    </script>
@endsection
