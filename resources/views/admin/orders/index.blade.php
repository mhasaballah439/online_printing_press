@extends('admin.layouts.app')
@section('title','Orders')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section id="file-export">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                @if(admin()->check_route_permission('admin.orders.index') == 1)
                                <div class="card-header" style="padding-bottom: 0px">
                                    <h4 class="card-title">{{__('msg.orders')}}</h4>
                                    <a class="heading-elements-toggle"><i
                                            class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                @include('admin.layouts.alerts.success')
                                @include('admin.layouts.alerts.errors')
                                <div class="card-content collapse show" style="margin-top: -12px">
                                    <div class="card-body card-dashboard">
                                            <table class="table table-striped table-bordered file-export">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">{{__('msg.user')}}</th>
                                                    <th class="text-center">{{__('msg.status')}}</th>
                                                    <th class="text-center">{{__('msg.branch')}}</th>
                                                    <th class="text-center">{{__('msg.is_payment')}}</th>
                                                    <th class="text-center">{{__('msg.created_at')}}</th>
                                                    <th class="text-center">{{__('msg.action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($orders as $order)
                                                    <tr class="row_{{$order->id}}">
                                                        <td class="text-center">
                                                            #{{$order->code}}
                                                        </td>
                                                        <td class="text-center">{{$order->user->name ?? ''}}</td>
                                                        <td>

                                                                <select class="form-control change_status">
                                                                        <option value="1" order_id="{{$order->id}}" {{$order->status_id == 1 ? 'selected' : ''}}>{{__('msg.pending')}}</option>
                                                                        <option value="2" order_id="{{$order->id}}" {{$order->status_id == 2 ? 'selected' : ''}}>{{__('msg.approve')}}</option>
                                                                        <option value="3" order_id="{{$order->id}}" {{$order->status_id == 3 ? 'selected' : ''}}>{{__('msg.cancel')}}</option>

                                                                </select>

                                                        </td>
                                                        <td class="text-center">{{$order->branch->name}}</td>
                                                        <td class="text-center">{{$order->is_payment == 1 ? ' مدفوع':'غير مدفوع'}}</td>
                                                        <td class="text-center">{{date('d/m/Y',strtotime($order->created_at))}}</td>

                                                        <td class="d-flex align-items-center justify-content-sm-center">
                                                                @if(admin()->check_route_permission('admin.orders.show') == 1)
                                                            <a href="{{route('admin.orders.show',$order-> id)}}"
                                                               class="block-page btn btn-primary"><i
                                                                    class="la la-eye"></i></a>
                                                                @endif
                                                                    @if(admin()->check_route_permission('admin.orders.delete') == 1)
                                                                        <a href="#" item_id="{{$order->id}}" class="delete_item ml-2 btn btn-danger">
                                                                            <i class="la la-trash"></i></a>
                                                                    @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                                @else
                    @include('admin.layouts.alerts.error_perm')
                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="deleteItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">{{__('msg.delete_item')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{asset('public/assets/images/remove.png')}}">
                    <h5>{{__('msg.confirm_delete_item')}}</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{__('msg.back')}}</button>
                    <button type="button" class="btn btn-outline-danger confirm_delete">{{__('msg.confirm')}}</button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="d-none" id="type-success">Success</button>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/extensions/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css-rtl/plugins/extensions/toastr.css')}}">
@endsection
@section('scripts')
    <script src="{{asset('public/assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/datatable/dataTables.buttons.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/buttons.print.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/js/scripts/tables/datatables/datatable-advanced.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/extensions/toastr.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/js/scripts/extensions/toastr.js')}}" type="text/javascript"></script>
    <script>
        $(document).on('click','.delete_item',function (){
            $('#deleteItem').modal('show');
            var item_id = $(this).attr('item_id')
            $(document).on('click','.confirm_delete',function (){
                $.ajax({
                    type : 'post',
                    url: "{{route('admin.orders.delete')}}",
                    data:{
                        '_token' : "{{csrf_token()}}",
                        'id' : item_id
                    },
                    success: function (data) {

                        if (data.status == true) {
                            $('#success_msg').show();
                            $('#deleteItem').modal('hide');
                            $('.row_' + data.id).remove();
                        }
                    }, error: function (reject) {

                    }
                })
            })
        });

        $(document).on('change','.change_status',function (){
            var status_id = $(this).find('option:selected').val();
            var order_id = $(this).find('option:selected').attr('order_id')

            $.ajax({
                type : 'post',
                url: "{{route('admin.orders.update_status')}}",
                data:{
                    '_token' : "{{csrf_token()}}",
                    'status_id' : status_id,
                    'order_id' : order_id,
                },
                success: function (data) {

                    if (data.status == true) {
                        $('#type-success').click();
                    }
                }, error: function (reject) {

                }
            })
        });
    </script>
@endsection
