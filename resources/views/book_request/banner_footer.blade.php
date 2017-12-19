<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2017/12/18
 * Time: 下午11:17
 */

@if(!empty($banner_items))
    <div class="row">
        <hr>
        <div class="col-lg-12">
            @foreach($banner_items as $material)
                <a href="{{ $material->display==1?route("material.show",$material->id):$material->url }}">
                    {{--<a href="{{ $material->url }}">--}}
                    <div class="col-xs-12 item">
                        <div class="col-xs-3" style="padding: 0;">
                            <img class="item-img" src="{{ $material->cover_path }}" alt="">
                        </div>
                        <div class="col-xs-9 item-content">
                            <p class="item-title">{{ \Illuminate\Support\Str::limit($material->title,40) }}</p>
                            <small class="item-hint" style="position: absolute; bottom: 2px;">阅读 {{ $material->reading_quantity  }}</small>
                            <small class="item-hint" style="position: absolute; bottom: 2px; right: 5px;">{{ $material->wechat_update_time }}</small>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif