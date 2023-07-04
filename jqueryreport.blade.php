@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
    <style>
        {{--        pagination style--}}

.pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination .active {
            font-weight: bold;
            color: #000;
        }

        .pagination a {
            color: #007bff;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: #fff;
        }


        {{--pagination style ends--}}

#prescription .container {
            background-color: #f2f2f2 !important;
        }

        .header p {
            font-size: 14px;
        }
        .aside {
            width: 400px;
            border-right: 1px solid #ddd;
            min-height: 600px;
            padding-bottom: 20px;
        }

        .signatureImage {
            display: inline-block;
            width: 100px;
            object-fit: contain;
            margin-bottom: 5px;
        }
        .signatureBox {
            position: absolute;
            right: 50px;
            bottom: 30px;
        }
        .footer {
            padding-top: 20px;
            padding-bottom: 20px;
            border-top: 1px solid #ddd;
        }

        .footer p {
            font-size: 14px;
        }
        .apiLogo {
            max-width: 40px;
            transform: translateY(-4px);
            margin-left: 5px;
        }
        .logoText {
            font-size: 14px;
        }
        .nextinfo {
            margin-top: 150px;
        }

        .userImg {
            margin-top: 20px;
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 20px;
            border: 10px solid rgba(122,122,122,.15);
        }

        .dataItem p{
            font-weight: 400;
            font-size: 15px;
        }
        .dataItem span{
            font-weight: 600;
            font-size: 15px;
        }

        @media (max-width: 767px){
            #prescription, .logoText, address p, .header p{
                font-size: 12px !important;
            }
            .header h4{
                font-size: 18px !important;
            }
            .patientageLeftSide {
                width: 100% !important;
                min-height: auto !important;
                border: 0 !important;
            }
            .itemMerge{
                flex-direction: column;
            }
            .patientageLeftSide h5{
                font-size: 18px !important;
            }
            .userImg {
                width: 140px !important;
                height: 140px !important;
                border-width: 5px;
            }
            .patientageRightSide .dataItem p,
            .patientageRightSide .dataItem span,
            .patientageLeftSide p{
                margin-bottom: 0;
                font-size: 14px;
            }
            .patientageRightSide .dataItem h5{
                font-size: 16px !important;
                margin-bottom: 5px !important;
            }
            .patientageRightSide{
                padding: 10px 10px !important;
            }
            .patientageRightSide .dataItem{
                margin-top: 15px !important;
            }

        }
    </style>
@endpush

@section('content')
    <div class="dt-content">

        <!-- Grid -->
        <div class="row">
            <div class="col-xl-12 pb-3">
                <ol class="breadcrumb bg-white">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                    <li class="active breadcrumb-item">{{ $sub_title }}</li>
                </ol>
            </div>
            <!-- Grid Item -->
            <div class="col-xl-12">

                <!-- Entry Header -->
                <div class="dt-entry__header">

                    <!-- Entry Heading -->
                    <div class="dt-entry__heading">
                        <h2 class="dt-page__title mb-0 text-primary"><i class="{{ $page_icon }}"></i> {{ $sub_title }}</h2>
                    </div>
                    <!-- /entry heading -->
                    @if (permission('patientage-add'))
                        <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New patientage','Save')">
                            <i class="fas fa-plus-square"></i> Add New
                        </button>
                    @endif


                </div>
                <!-- /entry header -->

                <!-- Card -->
                <div class="dt-card">

                    <!-- Card Body -->
                    <div class="dt-card__body">

                        <form id="form-filter" method="POST" action="{{route('date-wise-dx')}}" >
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Date From</label>
                                    <input type="date" class="form-control" name="starting_date" id="starting_date" placeholder="Date From">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="name">Date To </label>
                                    <input type="date" class="form-control" name="ending_date" id="ending_date" placeholder="Date To">
                                </div>
                                <div class="col-md-2 warning-searching invisible" id="warning-searching">
                                    <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                    <span class="spinner-border text-danger"></span>
                                </div>
                                <div class="form-group col-md-4 pt-24">

                                    <button type="submit"  class="btn btn-primary d-none btn-sm float-right mr-2" id="btn-filter"
                                            data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>


                        <table id="dataTable" class="table table-striped table-bordered table-hover">
                            <thead class="bg-primary">
                            <tr>

                                <th>No</th>
                                <th>Date</th>
                                <th>Provisional DX</th>
                                <th>Total</th>

                            </tr>

                            </thead>
                            @if($results ?? '')
                                <tbody>
                                @foreach($results as $result)
                                    <tr>

                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$result->CreateDate}}</td>
                                        <td>{{$result->ProvisionalDiagnosis}}</td>
                                        <td>{{$result->Total}}</td>

                                    </tr>
                                @endforeach
                                </tbody>
                            @endif
                        </table>



                    </div>
                    <!-- /card body -->

                </div>
                <!-- /card -->

            </div>
            <!-- /grid item -->

        </div>
        <!-- /grid -->

    </div>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script>
        var table;


        $(document).ready(function () {
            $('#dataTable').DataTable({
                pagingType: 'full_numbers',
                dom: 'Bfrtip',
                orderCellsTop: true,
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Export to Excel',

                    },
                ],
                // initComplete: function () {
                //     var api = this.api();
                //     $('.filterhead', api.table().header()).each(function (i) {
                //         var column = api.column(i);
                //         var select = $('<select><option value=""></option></select>')
                //             .appendTo($(this).empty())
                //             .on('change', function () {
                //                 var val = $.fn.dataTable.util.escapeRegex($(this).val());
                //                 column.search(val ? '^' + val + '$' : '', true, false).draw();
                //             });
                //
                //         column.data().unique().sort().each(function (d, j) {
                //             select.append('<option value="' + d + '">' + d + '</option>');
                //         });
                //     });
                // },
            });



        });
        $('#btn-filter').on('click', function (event) {
            $('#warning-searching').removeClass('invisible');
        });

        $(function () {

            $('#starting_date, #ending_date').on('input', function () {
                if ($('#starting_date').val() != '' && $('#ending_date').val() != '') {
                    $('#btn-filter').removeClass('d-none');
                } else {
                    $('#btn-filter').addClass('d-none');
                }
            });
        });

    </script>
@endpush
