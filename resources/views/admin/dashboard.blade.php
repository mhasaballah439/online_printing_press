@extends('admin.layouts.app')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- eCommerce statistic -->
                @if(admin()->check_route_permission('dashboard.index') == 1)
                    <form method="get">
                    <div class="form-group col-md-3 ">
                        <label class="label-control" for="sort_id">{{__('msg.branch')}}</label>
                            <select class="select2 form-control" id="branch_id" name="branch_id" onchange="this.form.submit()">
                                <option value="">اختر الفرع</option>
                                @if(count($branches) > 0)
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}" {{$branch->id ==  request()->get('branch_id') ? 'selected' : ''}}>{{$branch->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                    </div>
                    </form>
                <div class="row">

                    <div class="col-xl-4 col-lg-6 col-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h3 class="info">{{$num_branches}}</h3>
                                            <h6>{{__('msg.branches')}}</h6>
                                        </div>
                                        <div>
                                            <i class="icon-basket-loaded info font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                        <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 80%"
                                             aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h3 class="warning">{{$num_users}}</h3>
                                            <h6>{{__('msg.users')}}</h6>
                                        </div>
                                        <div>
                                            <i class="icon-pie-chart warning font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                        <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 65%"
                                             aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h3 class="danger">{{$num_orders}}</h3>
                                            <h6>{{__('msg.orders')}}</h6>
                                        </div>
                                        <div>
                                            <i class="icon-heart danger font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                        <div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: 85%"
                                             aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row match-height">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-content ">
                                <div id="cost-revenue" class="height-250 position-relative"></div>
                            </div>
                            <div class="card-footer">
                                <div class="row mt-1">
                                    <div class="col-3 text-center">
                                        <h6 class="text-muted">{{__('msg.orders')}}</h6>
                                        <h2 class="block font-weight-normal">{{$num_orders}}</h2>
                                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                            <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 70%"
                                                 aria-valuenow="{{$num_orders}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <h6 class="text-muted">{{__('msg.Total_Sales')}}</h6>
                                        <h2 class="block font-weight-normal">{{$total_sales}}</h2>
                                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                            <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 60%"
                                                 aria-valuenow="{{$total_sales}}" aria-valuemin="0" aria-valuemax="{{$total_sales}}"></div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <h6 class="text-muted">{{__('msg.today_total_sales')}}</h6>
                                        <h2 class="block font-weight-normal">{{$today_total_sales}}</h2>
                                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                            <div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: 40%"
                                                 aria-valuenow="{{$today_total_sales}}" aria-valuemin="0" aria-valuemax="{{$today_total_sales}}"></div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <h6 class="text-muted">{{__('msg.total_tax')}}</h6>
                                        <h2 class="block font-weight-normal">{{$total_tax}}</h2>
                                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                            <div class="progress-bar bg-gradient-x-success" role="progressbar" style="width: 90%"
                                                 aria-valuenow="90" aria-valuemin="0" aria-valuemax="{{$total_tax}}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    @include('admin.layouts.alerts.error_perm')
                @endif

            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/assets/vendors/css/charts/chartist.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/assets/vendors/css/charts/chartist-plugin-tooltip.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/weather-icons/climacons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/fonts/meteocons/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/charts/morris.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/assets/css/pages/timeline.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css/pages/dashboard-ecommerce.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/forms/selects/select2.min.css')}}">
    <style>
        .select2-container--default {
            width: 100% !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('public/assets/vendors/js/charts/chartist.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/charts/chartist-plugin-tooltip.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/charts/raphael-min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/charts/morris.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/timeline/horizontal-timeline.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <script src="{{asset('public/assets/vendors/js/charts/morris.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/js/scripts/pages/dashboard-ecommerce.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/assets/vendors/js/forms/select/select2.full.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('public/assets/js/scripts/forms/select/form-select2.js')}}" type="text/javascript"></script>
@endsection
