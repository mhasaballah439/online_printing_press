@extends('admin.layouts.app')
@section('title','Orders')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Invoice Template</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('dashboard.index')}}">{{__('msg.dashboard')}} </a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Invoice</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="card">
                    @if(admin()->check_route_permission('admin.orders.show') == 1)
                    <div id="invoice-template" class="card-body">
                        <!-- Invoice Company Details -->
                        <div id="invoice-company-details" class="row">
                            <div class="col-md-6 col-sm-12 text-center text-md-left">
                                <div class="media">
                                    <img src="{{asset('public'.$setting->logo)}}" style="width: 120px;" alt="company logo" class=""
                                    />
                                    <div class="media-body">
                                        <ul class="ml-2 px-0 list-unstyled">
                                            <li class="text-bold-800">قرطاسية الشيوخ</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 text-center text-md-right">
                                <h2>{{__('msg.INVOICE')}}</h2>
                                <p class="pb-3"># {{$order->code}}</p>
                            </div>
                        </div>
                        <!--/ Invoice Company Details -->
                        <!-- Invoice Customer Details -->
                        <div id="invoice-customer-details" class="row pt-2">
                            <div class="col-sm-12 text-center text-md-left">
                                <p class="text-muted">{{__('msg.Bill_To')}}</p>
                            </div>
                            <div class="col-md-6 col-sm-12 text-center text-md-left">
                                <ul class="px-0 list-unstyled">
                                    <li class="text-bold-800">{{$order->user->name ?? ''}}</li>
                                    <li>{{$order->user->email ?? ''}}</li>
                                    <li>{{$order->user->phone ?? ''}}</li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-sm-12 text-center text-md-right">
                                <p><span class="text-muted">{{__('msg.Invoice_Date')}} :</span> {{date('d/m/Y H:i',strtotime($order->created_at))}}</p>
                                <p><span class="text-muted">{{__('msg.is_payment')}} :</span> {{$order->is_payment == 1 ? 'مدفوع':'غير مدفوع'}}</p>
                                <p><span class="text-muted">{{__('msg.status')}} :</span> {{$order->status_name}}</p>
                            </div>
                        </div>
                        <!--/ Invoice Customer Details -->
                        <!-- Invoice Items Details -->
                        <div id="invoice-items-details" class="pt-2">
                            <div class="row">
                                <div class="table-responsive col-sm-12">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">{{__('msg.file')}}</th>
                                            <th class="text-center">{{__('msg.paper_size')}}</th>
                                            <th class="text-center">{{__('msg.price')}}</th>
                                            <th class="text-center">{{__('msg.qty')}}</th>
                                            <th class="text-center">{{__('msg.color_cover_price')}}</th>
                                            <th class="text-center">{{__('msg.first_page_color')}}</th>
                                            <th class="text-center">{{__('msg.paper_type')}}</th>
                                            <th class="text-center">{{__('msg.aspects_printing')}}</th>
                                            <th class="text-center">{{__('msg.packaging_id')}}</th>
                                            <th class="text-center">{{__('msg.page_layout')}}</th>
                                            <th class="text-center">{{__('msg.printing_color')}}</th>
                                            <th class="text-center">{{__('msg.total')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($order->details) && count($order->details) > 0)
                                            @foreach($order->details as $detail)
                                        <tr>
                                                    <td class="text-center"><a target="_blank" href="{{isset($detail->file) ? asset('public'.$detail->file->file_path) : '#'}}">
                                                            {{isset($detail->file) ? $detail->file->file_name : '-'}}
                                                        </a> </td>
                                                    <td class="text-center">A {{$detail->paper_size}}</td>
                                                    <td class="text-center">{{(float)$detail->price}}</td>
                                                    <td class="text-center">{{$detail->qty}}</td>
                                                    <td class="text-center">{{(float)$detail->color_cover_price}}</td>
                                                    <td class="text-center">{{$detail->first_page_color == 1 ? 'نعم':'لا'}}</td>
                                                    <td class="text-center">{{$detail->paper_type_name}}</td>
                                                    <td class="text-center">{{$detail->aspects_printing_name}}</td>
                                                    <td class="text-center">{{$detail->packaging_name}}</td>
                                                    <td class="text-center">{{$detail->page_layout_name}}</td>
                                                    <td class="text-center">{{$detail->printing_color == 1 ? 'نعم':'لا'}}</td>
                                                    <td class="text-center">{{(float)$detail->total}}</td>

                                        </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7 col-sm-12 text-center text-md-left">

                                </div>
                                <div class="col-md-5 col-sm-12">
                                    <p class="lead">{{__('msg.summery')}}</p>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <td>{{__('msg.sub_total')}}</td>
                                                <td class="text-right">{{(float)$order->order_sub_total}} {{__('msg.sar')}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('msg.tax')}} ({{$order->tax}}%)</td>
                                                <td class="text-right">{{(float)$order->total_tax}} {{__('msg.sar')}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('msg.sum_color_cover_price')}}</td>
                                                <td class="pink text-right">{{(float)$order->sum_color_cover_price}} {{__('msg.sar')}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-bold-800">{{__('msg.total')}}</td>
                                                <td class="text-bold-800 text-right"> {{(float)$order->total}} {{__('msg.sar')}}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Invoice Footer -->
                        <div id="invoice-footer">
                            <div class="row">
                                <div class="col-md-7 col-sm-12">

                                </div>
                                <div class="col-md-5 col-sm-12 text-center">
                                    <button type="button" class="btn btn-info btn-lg my-1" id="send_sms"><i class="la la-paper-plane-o"></i>{{__('msg.send_sms')}}</button>
                                </div>
                            </div>
                        </div>
                        <!--/ Invoice Footer -->
                    </div>
                    @else
                        @include('admin.layouts.alerts.error_perm')
                    @endif
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
    <button type="button" class="btn btn-lg btn-block d-none btn-outline-success mb-2" id="type-success">Success</button>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css/pages/invoice.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/extensions/sweetalert.css')}}">
@endsection
@section('scripts')
    <script src="{{asset('public/assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/datatable/dataTables.buttons.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/tables/buttons.print.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/js/scripts/tables/datatables/datatable-advanced.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/extensions/sweetalert.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/js/scripts/extensions/sweet-alerts.js')}}" type="text/javascript"></script>
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

        $(document).on('click','#send_sms',function (){
                $.ajax({
                    type : 'post',
                    url: "{{route('admin.orders.send_sms')}}",
                    data:{
                        '_token' : "{{csrf_token()}}",
                        'id' : {{$order ? $order->id : 0}}
                    },
                    success: function (data) {

                        if (data.status == true) {
                            $('#type-success').click();
                        }
                    }
                })
        });
    </script>
@endsection
