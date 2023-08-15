@extends('layouts.app')

@section('content')
<style type="text/css">
    #get-historical-data-chart
    {
        width:100%; 
        height:300px;
    }

    #preloader {
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1999;
        height: 100%;
        width: 100%;
        background: rgba(0,0,0,.5);
        display: flex;
    }
</style>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#">
                    WebAPI
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item">
                         <a class="nav-link" href="#">Welcome Back</a>
                     </li>
                     <li class="nav-item">
                      <!-- <a class="nav-link" href="#">Register</a> -->
                  </li>
              </ul>
          </div>
      </div>
  </nav>
<div id="preloader" style="display: none;"></div>

  <main class="py-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <!-- Begining -->
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">Get List Data</div>
                    
                    <div class="card-body">
                        <form method="POST" action="{{ url('sendmail-to-customer') }}" id="taskForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <select class="form-control select-filter" name="company_symbol" placeholder="Company Symbol" id="company_symbol" required>
                                         @foreach($data as $value)
                                            <option value="{{ $value->Symbol }}" {{ $value->Symbol == "AAL" ? "selected" : null }}>{{ $value->Symbol }}</option>
                                        @endforeach
                                    </select>
                                    <span class="company_symbol" style="color:red;"></span>
                                </div>
                                <div class="col-md-2">
                                    <input id="start_date" type="date" class="form-control" name="start_date" placeholder="Start Date"  >
                                    <span class="start_date" style="color:red;"></span>
                                </div>
                                <div class="col-md-2">
                                    <input id="end_date" type="date" class="form-control" name="end_date" placeholder="End Date" required >
                                    <span class="end_date" style="color:red;"></span>
                                </div>
                                <div class="col-md-3">
                                    <input id="email" type="email" class="form-control" name="email" placeholder="Email Address" required >
                                    <span class="email" style="color:red;"></span>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        Send Mail
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <table id="task_table" class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <!-- <th>#</th> -->
                                                <th>Date</th>
                                                <th>Open</th>
                                                <th>High</th>
                                                <th>Low</th>
                                                <th>Close</th>
                                                <th>Volume</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>

                    <hr>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div id="get-historical-data-chart" style="height: 500px;"></div>    
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Ended -->
        </div>
    </div>
</main>
</div>

</body>

@endsection

@section('js-script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@latest/dist/echarts.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

        var orders_table = $('#task_table').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: ({
                url : "{{ url('get-historical-data') }}",
                method: "POST",
                data: function (d) {
                    d._token =  $('meta[name="csrf-token"]').attr('content');

                    if($('select[name=company_symbol]').val() != ''){
                        d.company_symbol = $('select[name=company_symbol]').val();
                    }

                },
                 error: function (request, status, error) {
                    // console.log("Get Error Obaid: ", request.responseText);
                    console.log("Get Error Obaid: ", error);
                 }
            }),
            "columns" : [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data : 'date', name : 'date' },
                { data : 'open', name : 'open' },
                { data : 'high', name : 'high' },
                { data : 'low', name : 'low' },
                { data : 'close', name : 'close' },
                { data : 'volume', name : 'volume' },
            ],
            responsive: true,
            "bStateSave": true,
            "bAutoWidth":false, 
            "ordering": false,
            "searching": false,
            "language": {
               "decimal":        "",
               "emptyTable":     "No Data Found",
               "info":           "Showing" + " _START_ to _END_ of _TOTAL_ Entries",
               "infoEmpty":      "Showing 0 To 0 Of 0 Entries",
               "infoFiltered":   "(filtered from _MAX_ total entries)",
               "infoPostFix":    "",
               "thousands":      ",",
               "lengthMenu":     "Show _MENU_ Entries",
               "loadingRecords": "Loading",
               "processing":     "Processing...",
               "search":         "Search",
               "zeroRecords":    "No matching records found",
               "paginate": {
                  "first":      "First",
                  "last":       "Last",
                  "next":       "Next",
                  "previous":   "Previous"
               }
            }
        });

        $('.select-filter').on('change', function(e) {
            // alert('sadsa');
            getHistoricalBarChart();
            orders_table.draw();
        });

    });
</script>

<script type="text/javascript">
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getHistoricalBarChart(){
    var company_symbol = $('select[name=company_symbol]').val();

    //Monthly Record Check | Bar Chart
    var link = "{{ url('task/barchart') }}";
    $.ajax({
        url: link, 
        data: {
            company_symbol: company_symbol,
        },
        beforeSend: function(){
            $("#preloader").css("display","block");  
        },
        success: function (data) {
            $("#preloader").css("display","none"); 
            var cashflow = echarts.init(document.getElementById('get-historical-data-chart'));

            var option = {
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: [data.legend_name_1, data.legend_name_2, data.legend_name_3, data.legend_name_4, data.legend_name_5]
                },
                toolbox: {
                    show: true,
                    feature: {
                        mark: {show: true},
                        magicType: {
                            show: true, title: {
                                line: 'Line',
                                bar: 'Bar',
                            }, type: ['line', 'bar']
                        },
                        restore: {show: true, title: 'Reset'},
                    }
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: data.fetch_data['date_array'],
                        avoidLabelOverlap: true,
                        axisLabel: {
                            show: false,
                            interval: 0,
                            rotate: 45,
                        },
                        axisTick: {
                            show: true,
                            interval: 0
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: data.legend_name_1,
                        type: 'line',
                        color: [
                            '#0dc8de'
                        ],
                        data: data.fetch_data['open_array']
                    },
                    {
                        name: data.legend_name_2,
                        type: 'line',
                        color: [
                            '#fd3c97'
                        ],
                        data: data.fetch_data['high_array']
                    },
                    {
                        name: data.legend_name_3,
                        type: 'line',
                        color: [
                            '#409eff'
                        ],
                        data: data.fetch_data['low_array']
                    },
                    {
                        name: data.legend_name_4,
                        type: 'line',
                        color: [
                            '#f56c6c'
                        ],
                        data: data.fetch_data['close_array']
                    },
                    {
                        name: data.legend_name_5,
                        type: 'line',
                        color: [
                            '#67c23a'
                        ],
                        data: data.fetch_data['volume_array']
                    },
                ]
            };

            // use configuration item and data specified to show chart
            cashflow.setOption(option);

        }
    });
}
getHistoricalBarChart();
</script>


<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

      $('#taskForm').validate({
        rules: {
          start_date: 'required',
          end_date: 'required',
          email: {
            required: true,
            email: true
          },
          company_symbol: 'required',
        },
        messages: {
          name: 'Please enter your name',
          email: 'Please enter a valid email address',
        },
        errorPlacement: function(error, element) {

            var n = element.attr("name");
            
            if ( n == "start_date") {
                $('span.' + n).html("Start Date is required!");
            }else if ( n == "end_date") {
                $('span.' + n).html("End Date is required!");
            }else if ( n == "email") {
                $('span.' + n).html("Email is required!");
            }else if ( n == "company_symbol") {
                $('span.' + n).html("Company Symbol is required!");
            }

        },
        submitHandler: function(form) {
             $('span.start_date').html('');
             $('span.end_date').html('');
             $('span.email').html('');
             $('span.company_symbol').html('');
         
        
            var get_start_date = $("input#start_date").val();
            var get_end_date = $("input#end_date").val();
            var currentDate = new Date();
            var endDate = new Date(get_end_date);
            var startDate = new Date(get_start_date);
            if(endDate > startDate && endDate < startDate){
                $('span.end_date').html("End date should be greater than or equal to the start date and current date!");
                console.log("End date should be greater than or equal to the start date and current date!");
                return false;
            }else if(endDate < startDate){
                $('span.start_date').html("Start date should be less than or equal to the end date and current date!");
                console.log("Start date should be less than or equal to the end date and current date");
                return false;
            }else if(endDate == startDate){
                return true;
            }else{
                return true;
            }
         
        }
      });

    });

</script>

@endsection