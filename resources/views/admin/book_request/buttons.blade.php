<div class="row" style="margin-bottom: 10px;">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <a href="{{route('admin.bookreq.export.bookreq')}}"><button class="btn btn-sm btn-primary">导出全部样书申请单</button></a>
        <a href="{{route('admin.bookreq.export.book')}}"><button class="btn btn-sm btn-warning">导出库房发书单</button></a>
        <a href="{{route('admin.bookreq.export.packaging')}}"><button class="btn btn-sm btn-success">导出快递打印单</button></a>
        <a href="{{route('admin.bookreq.export.invoice') }}"><button class="btn btn-sm btn-danger">导出发行单</button></a>
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#import-express" >导入发行单</button>

        <div class="modal fade bs-example-modal-sm" id="import-express" tabindex="-1" role="dialog" aria-labelledby="deleteOfficeLable" style="margin-top: 200px">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="deleteOfficeLable">导入发行单</h4>
                    </div>
                    <script>
                        function submit(){
                            var form = new FormData(document.getElementById("import_express_form"));
                            $("#express_submit").val("正在提交中...").attr("disabled","disabled");
                            $.ajax({
                                type: 'POST',
                                url: '/admin/bookreq/importexpress',
                                data: form,
                                processData:false,
                                contentType:false,
                                success: function(){
                                    $("#express_submit").val("提交").attr("disabled","");
                                    window.location.reload();
                                },
                                error: function(xhr, type){
                                    alert('出现错误，请手动刷新页面！');
                                }
                            });
                        }
                        $(function () {
                            $("#import_express_form").submit(function (e) {
                                submit();
                                return false;
                            });
                        })
                    </script>
                    <div class="modal-body">
                        {!! Form::open(["route"=>"admin.bookreq.import_express","id"=>"import_express_form", "method"=>"post", "files"=>true]) !!}
                        {{ Form::file("express_file", ["class"=>"form-control form-spacing-top"])}}
                        {{ Form::submit("导入", ["class"=>"btn btn-primary form-spacing-top","id"=>"express_submit"])}}
                        <button type="button" class="btn btn-default form-spacing-top" data-dismiss="modal">取消</button>
                        {!! Form::close() !!}
                        <hr>
                        <div>
                            <span style="color:red;">注意事项:</span><br>
                            1. 表头的标准格式:快递单号、状态、ISBN、定价、数量、书名、姓名、电话、地址 <br>
                            2. 每个excel文件下只能有一张工作表 <br>
                            3. 请保证isbn与用户真实名字的准确性 <br>
                            4. 同一份文件可提交多次
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bs-example-modal-sm" id="close_bookreq" tabindex="-1" role="dialog" aria-labelledby="close_bookreq_title" style="margin-top: 200px">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="close_bookreq_title">关闭样书申请功能</h4>
                    </div>
                    <script>
                        $(function () {
                            $("#close_bookreq_form").submit(function (e) {
                                return false;
                            });
                        })
                    </script>
                </div>
            </div>
        </div>

        <!-- TODO 导出发货单 admin.bookreq.export.invoice -->
    </div>
</div>
