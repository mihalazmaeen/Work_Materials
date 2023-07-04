@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <style type="text/css">
        .logoBox p {
            font-size: 14px;
            color: #285ec3;
        }

        .barCode {
            width: 250px !important;
            height: 55px !important;
        }
    </style>

@endpush


@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <!--begin::Notice-->
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <a href="{{ route('barcodeprint') }}" class="btn btn-warning btn-sm font-weight-bolder">
                            <i class="fas fa-arrow-left"></i> Back</a>
                        <!--end::Button-->
                    </div>
                </div>
            </div>
            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom" style="padding-bottom: 100px !important;">
                <div class="card-body">

                    <form id="generate_barcode_form" method="POST">
                        @csrf
                        <div class="pb-3 mb-3 select-barcode-type">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="barcode_type" id="barcode_type_old"
                                       value="old" onclick="getGeneratedBarcode(this.value)">
                                <label class="form-check-label" for="inlineRadio1">Printed Barcode</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="barcode_type" id="barcode_type_new"
                                       value="new" onclick="getGeneratedBarcode(this.value)">
                                <label class="form-check-label" for="inlineRadio1">Print New Barcode</label>
                            </div>
                            <div class="mt-2 spinner" id="spinner">

                            </div>

                            <div class="error-max d-none" id="error-max">
                                <span class="text-danger" id="error-message"> Range must be greater than 0 and not cross max range</span>
                            </div>
                            <div class="warning-downloading d-none" id="warning-downloading">
                                <span class="text-danger" id="warning-message">Downloading PDF...Please Wait</span>
                                <span class="spinner-border text-danger"></span>
                            </div>
                        </div>

                        <div class="d-none" id="select-barcode-filter-range" >
                            <x-form.selectbox labelName="Barcode Prefix" name="mdata_barcode_prefix" col="col-md-4" class="mdata_barcode_prefix selectpicker"/>
                            <x-form.textbox labelName="Show Range" name="show_range" type="number" required="required" col="col-md-2" placeholder="Enter Show Range" />

                            <div class="form-group col-md-2" style="padding-top: 22px;">
                                <button type="button" class="btn btn-primary btn-sm" style="width: 90px" id="save-range-btn"> Show</button>
                            </div>

                        </div>

                        <div class="d-none" id="select-barcode-filter-range-old" >
                            <x-form.selectbox labelName="Barcode Prefix" name="mdata_barcode_prefix" col="col-md-4"  class="mdata_barcode_prefix selectpicker"/>
                            <x-form.textbox labelName="Starting Range" name="starting_range" type="number"  max="" required="required" col="col-md-2" placeholder="Enter Starting Range" />
                            <x-form.textbox labelName="Ending Range" name="ending_range" type="number" required="required" col="col-md-2" placeholder="Enter Ending Range" />
                            <div class="form-group col-md-2" style="padding-top: 22px;">
                                <button type="button" class="btn btn-primary btn-sm" style="width: 90px" id="save-old-range-btn"> Show</button>
                            </div>

                        </div>

                        <div class="d-none" id="select-barcode-range" >
                            <x-form.selectbox labelName="Barcode Start" name="mdata_barcode_prefix_number_start"
                                              col="col-md-4" class="mdata_barcode_prefix_number_start selectpicker"/>
                            <x-form.selectbox labelName="Barcode End" name="mdata_barcode_prefix_number_end"
                                              col="col-md-4 " class="mdata_barcode_prefix_number_end selectpicker"/>

                            <div class="form-group col-md-2" style="padding-top: 22px;">
                                <button type="button" class="btn btn-primary btn-sm" id="save-btn"> Print</button>
                            </div>


                        </div>
                    </form>


                    <style>
                        #select-barcode-filter-range {
                            display: flex;
                            flex-wrap: wrap;
                            margin-right: -1.6rem;
                            margin-left: -1.6rem;
                        }

                        #select-barcode-filter-range-old {
                            display: flex;
                            flex-wrap: wrap;
                            margin-right: -1.6rem;
                            margin-left: -1.6rem;
                        }

                        #select-barcode-range {
                            display: flex;
                            flex-wrap: wrap;
                            margin-right: -1.6rem;
                            margin-left: -1.6rem;
                        }

                        .select-barcode-type{
                            display: flex;
                            flex-wrap: wrap;
                            margin-right: -1.6rem;
                            margin-left: -1.6rem;
                        }


                        @media print {
                            /*html {*/
                            /*    overflow: hidden;*/
                            /*}*/
                            ::-webkit-scrollbar {
                                display: none;
                            }
                            .element::-webkit-scrollbar { width: 0 !important }

                            /* page-break-after works, as well */

                            .page-break {
                                display: block;
                                page-break-inside: avoid !important;
                                page-break-after: always !important;
                            }

                            #select-barcode-range {
                                display: none;

                            }
                            .select-barcode-type{
                                display: none;

                            }
                            #main-sidebar{
                                display: none;
                            }
                            /*.dt-sidebar--fixed .dt-sidebar, .dt-content-wrapper, .dt-header, .dt-header__container{*/
                            /*    margin-left: 0 !important;*/
                            /*}*/
                            .dt-sidebar--fixed  {
                                margin-left: 0 !important;
                            }
                            .dt-sidebar{
                                margin-left: 0 !important;
                            }
                            .dt-content-wrapper{
                                margin-left: 0!important;
                            }
                            .card-header{
                                display: none;
                            }
                            .dt-header{
                                display: none;
                            }
                            /*.dt-footer{*/
                            /*    display: none;*/
                            /*}*/
                            .logoBox p {
                                font-size: 14px;
                                color: #285ec3;
                            }

                            .barCode {
                                width: 250px !important;
                                height: 55px !important;
                            }

                            #barcode-section * {
                                visibility: visible !important;
                            }
                            .row {
                                display: flex;
                                flex-wrap: wrap;
                                margin-right: -1.6rem;
                                margin-left: -1.6rem;
                            }

                            .col-lg-6{
                                flex: 0 0 50%;
                                max-width: 50%;
                            }


                        }
                    </style>
                    <div class="row g-4" id="barcode-section">


                    </div>



                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>

@endsection

@push('script')
    <script src="js/spartan-multi-image-picker-min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script>
        var table;
        $(document).ready(function () {




            $('.summernote').summernote({
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            table = $('#dataTable').DataTable({
                "processing": true, //Feature control the processing indicator
                "serverSide": true, //Feature control DataTable server side processing mode
                "order": [], //Initial no order
                "responsive": true, //Make table responsive in mobile device
                "bInfo": true, //TO show the total number of data
                "bFilter": false, //For datatable default search box show/hide
                "lengthMenu": [
                    [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                    [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
                ],
                "pageLength": 10, //number of data show per page
                "language": {
                    processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable: '<strong class="text-danger">No Data Found</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">No Data Found</strong>'
                },
                "ajax": {
                    "url": "{{route('bgenerate.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.name = $("#form-filter #name").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                    @if (permission('bgenerate-bulk-delete'))
                    "targets": [0, 3],
                    @else
                    "targets": [3],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                    {
                        @if (permission('bgenerate-bulk-delete'))
                        "targets": [1, 2, 4],
                        @else
                        "targets": [0, 1, 3],
                        @endif
                        "className": "text-center"
                    },
                    {
                        @if (permission('bgenerate-bulk-delete'))
                        "targets": [2, 3],
                        @else
                        "targets": [2, 3],
                        @endif
                        "className": "text-right"
                    }
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

                "buttons": [
                        @if (permission('bgenerate-report'))
                    {
                        'extend': 'colvis', 'className': 'btn btn-secondary btn-sm text-white', 'text': 'Column'
                    },
                    {
                        "extend": 'print',
                        'text': 'Print',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "bgenerate List",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: function (index, data, node) {
                                return table.column(index).visible();
                            }
                        },
                        customize: function (win) {
                            $(win.document.body).addClass('bg-white');
                        },
                    },
                    {
                        "extend": 'csv',
                        'text': 'CSV',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "bgenerate List",
                        "filename": "bgenerate-list",
                        "exportOptions": {
                            columns: function (index, data, node) {
                                return table.column(index).visible();
                            }
                        }
                    },
                    {
                        "extend": 'excel',
                        'text': 'Excel',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "bgenerate List",
                        "filename": "bgenerate-list",
                        "exportOptions": {
                            columns: function (index, data, node) {
                                return table.column(index).visible();
                            }
                        }
                    },
                    {
                        "extend": 'pdf',
                        'text': 'PDF',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "bgenerate List",
                        "filename": "bgenerate-list",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        },
                    },
                        @endif
                        @if (permission('bgenerate-bulk-delete'))
                    {
                        'className': 'btn btn-danger btn-sm delete_btn d-none text-white',
                        'text': 'Delete',
                        action: function (e, dt, node, config) {
                            multi_delete();
                        }
                    }
                    @endif
                ],
            });

            $('#btn-filter').click(function () {
                table.ajax.reload();
            });

            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });


            $(document).on('click', '#print-barcode', function () {
                var mode = 'popup'; //popup
                var close = mode == 'popup';
                var options = {
                    mode: mode,
                    popClose: close
                };
                $('#printableArea').printArea(options);
            })


            $(document).on('click', '.delete_data', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('bgenerate.delete') }}";
                delete_data(id, url, table, row, name);
            });

            function multi_delete() {
                let ids = [];
                let rows;
                $('.select_data:checked').each(function () {
                    ids.push($(this).val());
                    rows = table.rows($('.select_data:checked').parents('tr'));
                });
                if (ids.length == 0) {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'Please checked at least one row of table!',
                        icon: 'warning',
                    });
                } else {
                    let url = "{{route('bgenerate.bulk.delete')}}";
                    bulk_delete(ids, url, table, rows);
                }
            }

            $(document).on('click', '.change_status', function () {
                let id = $(this).data('id');
                let status = $(this).data('status');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('bgenerate.change.status') }}";
                change_status(id, status, name, table, url);

            });

            $('#image').spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: '200px',
                groupClassName: 'col-md-12 com-sm-12 com-xs-12',
                maxFileSize: '',
                dropFileLabel: 'Drop Here',
                allowExt: 'png|jpg|jpeg',
                onExtensionErr: function (index, file) {
                    Swal.fire({icon: 'error', title: 'Oops...', text: 'Only png,jpg,jpeg file format allowed!'});
                }
            });

            $('input[name="image"]').prop('required', true);

            $('#lifestyle_image').spartanMultiImagePicker({
                fieldName: 'lifestyle_image',
                maxCount: 1,
                rowHeight: '200px',
                groupClassName: 'col-md-12 com-sm-12 com-xs-12',
                maxFileSize: '',
                dropFileLabel: 'Drop Here',
                allowExt: 'png|jpg|jpeg',
                onExtensionErr: function (index, file) {
                    Swal.fire({icon: 'error', title: 'Oops...', text: 'Only png,jpg,jpeg file format allowed!'});
                }
            });

            $('input[name="lifestyle_image"]').prop('required', true);


            $('input[name="bgenerate_video_path"]').prop('nullable', true);
            $('input[name="bgenerate_brochure"]').prop('nullable', true);

            $('.remove-files').on('click', function () {
                $(this).parents('.col-md-12').remove();
            });


        });




        // $('#select-barcode-filter-range-old #').on('click', function (event) {
        // });

        $('#save-btn').on('click', function (event) {


            event.preventDefault();
            var barcode_type = $('input[name="barcode_type"]:checked').val();

            var filterValueStart = $('#mdata_barcode_prefix_number_start').val();
            var filterValueEnd = $('#mdata_barcode_prefix_number_end').val();


            $.ajax({
                url: '{{ route('barcodeprint.store.or.update') }}',
                method: 'GET',
                data: {filterValueStart: filterValueStart, filterValueEnd: filterValueEnd, barcode_type: barcode_type},
                beforeSend: function(){
                    $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light','');
                },
                complete: function(){
                    $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light','');
                },
                success: function (data) {
                    $('#select-barcode-range').removeClass('d-none');

                    generatePDF(data);

                },

            });
        });


        $('#save-old-range-btn').on('click', function (event) {


            event.preventDefault();
            var barcode_type1 = $('input[name="barcode_type"]:checked').val();



            if(barcode_type1 == "old"){
                var starting_range = $('input[name="starting_range"]').val();
                var ending_range = $('input[name="ending_range"]').val();
                var mdata_barcode_prefix = $('#select-barcode-filter-range-old select[name="mdata_barcode_prefix"]').val();

                $.ajax({
                    url: '{{ route('barcodeprint.store.or.update.range') }}',
                    method: 'GET',
                    data: {mdata_barcode_prefix: mdata_barcode_prefix, starting_range: starting_range,ending_range: ending_range, barcode_type1: barcode_type1},
                    beforeSend: function(){
                        $('#save-old-range-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light','');
                    },
                    complete: function(){
                        $('#save-old-range-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light','');
                    },


                    success: function (data) {
                        $('#select-barcode-range').removeClass('d-none');

                        $('#generate_barcode_form #mdata_barcode_prefix_number_start').empty();
                        $('#generate_barcode_form #mdata_barcode_prefix_number_end').empty();
                        $.each(data, function (key, value) {
                            if (!$.trim(data)) {
                                $('#generate_barcode_form .mdata_barcode_prefix_number_end').addClass('d-none');
                            } else {
                                $('#generate_barcode_form .mdata_barcode_prefix_number_end').removeClass('d-none');
                                $('#generate_barcode_form .mdata_barcode_prefix_number_start').removeClass('d-none');
                                $('#generate_barcode_form #mdata_barcode_prefix_number_start').append('<option value="' + value.mdata_barcode_prefix_number + '">' + value.mdata_barcode_prefix_number + '</option>');
                                $('#generate_barcode_form #mdata_barcode_prefix_number_end').append('<option value="' + value.mdata_barcode_prefix_number + '">' + value.mdata_barcode_prefix_number + '</option>');
                                $('#generate_barcode_form #mdata_barcode_prefix_number_start.selectpicker').selectpicker('refresh');
                                $('#generate_barcode_form #mdata_barcode_prefix_number_end.selectpicker').selectpicker('refresh');
                            }
                        });
                        $('#generate_barcode_form .selectpicker').selectpicker('refresh');


                    },

                });
            }


        });

        $('#save-range-btn').on('click', function (event) {


            event.preventDefault();
            var barcode_type1 = $('input[name="barcode_type"]:checked').val();


            if(barcode_type1 == "new"){

                var show_range = $('input[name="show_range"]').val();

                var mdata_barcode_prefix = $('#select-barcode-filter-range select[name="mdata_barcode_prefix"]').val();

                $.ajax({
                    url: '{{ route('barcodeprint.store.or.update.range') }}',
                    method: 'GET',
                    data: {mdata_barcode_prefix: mdata_barcode_prefix, show_range: show_range,barcode_type1: barcode_type1},
                    beforeSend: function(){
                        $('#save-range-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light','');
                    },
                    complete: function(){
                        $('#save-range-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light','');
                    },


                    success: function (data) {
                        $('#select-barcode-range').removeClass('d-none');

                        $('#generate_barcode_form #mdata_barcode_prefix_number_start').empty();
                        $('#generate_barcode_form #mdata_barcode_prefix_number_end').empty();

                        $.each(data, function (key, value) {
                            if (!$.trim(data)) {
                                $('#generate_barcode_form .mdata_barcode_prefix_number_end').addClass('d-none');
                            } else {
                                $('#generate_barcode_form .mdata_barcode_prefix_number_end').removeClass('d-none');
                                $('#generate_barcode_form .mdata_barcode_prefix_number_start').removeClass('d-none');
                                $('#generate_barcode_form #mdata_barcode_prefix_number_start').append('<option value="' + value.mdata_barcode_prefix_number + '">' + value.mdata_barcode_prefix_number + '</option>');
                                $('#generate_barcode_form #mdata_barcode_prefix_number_end').append('<option value="' + value.mdata_barcode_prefix_number + '">' + value.mdata_barcode_prefix_number + '</option>');
                                $('#generate_barcode_form #mdata_barcode_prefix_number_start.selectpicker').selectpicker('refresh');
                                $('#generate_barcode_form #mdata_barcode_prefix_number_end.selectpicker').selectpicker('refresh');
                            }
                        });
                        $('#generate_barcode_form .selectpicker').selectpicker('refresh');


                    },

                });


            }


        });






        // Bind the printPDF function to the click event of your button


        //function to generate new barcode or view old one's


        //get Generated Barcode from radio button
        function getGeneratedBarcode(barcode_type) {
            if(barcode_type == "old"){

                $.ajax({
                    url: "{{url('/get-barcodes')}}/" + barcode_type,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function(){
                        $('#spinner').addClass('spinner-border text-dark');
                    },
                    complete: function(){
                        $('#spinner').removeClass('spinner-border text-dark');
                    },
                    success: function (data) {

                        $('#select-barcode-filter-range').addClass('d-none');
                        $('#select-barcode-filter-range-old').removeClass('d-none');

                        $('#generate_barcode_form #mdata_barcode_prefix').empty();


                        $.each(data, function (key, value) {

                            if (!$.trim(data)) {
                                $('#generate_barcode_form #mdata_barcode_prefix').addClass('d-none');
                            } else {
                                $('#generate_barcode_form #mdata_barcode_prefix').append('<option value="' + value.mdata_barcode_prefix + '">' + value.mdata_barcode_prefix +'('+ value.mdata_barcode_count + ')</option>');

                                $('#generate_barcode_form #mdata_barcode_prefix.selectpicker').selectpicker('refresh');
                            }
                        });


                        $('#generate_barcode_form .selectpicker').selectpicker('refresh');


                    },


                });
            }
            else{

                $.ajax({
                    url: "{{url('/get-barcodes')}}/" + barcode_type,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function(){
                        $('#spinner').addClass('spinner-border text-dark');
                    },
                    complete: function(){
                        $('#spinner').removeClass('spinner-border text-dark');
                    },
                    success: function (data) {

                        $('#select-barcode-filter-range-old').addClass('d-none');
                        $('#select-barcode-filter-range').removeClass('d-none');

                        $('#generate_barcode_form #mdata_barcode_prefix').empty();
                        $.each(data, function (key, value) {

                            if (!$.trim(data)) {
                                $('#generate_barcode_form #mdata_barcode_prefix').addClass('d-none');
                            } else {
                                $('#generate_barcode_form #mdata_barcode_prefix').append('<option value="' + value.mdata_barcode_prefix + '">' + value.mdata_barcode_prefix +'('+ value.mdata_barcode_count + ')</option>');
                                $('#generate_barcode_form #mdata_barcode_prefix.selectpicker').selectpicker('refresh');
                            }
                        });
                        $('#generate_barcode_form .selectpicker').selectpicker('refresh');


                    },


                });
            }



        }


        $('#select-barcode-filter-range-old .mdata_barcode_prefix').on('change', function (event) {

            var selectedOption = $(this).find('option:selected');
            var text = selectedOption.text();
            var count = parseInt(text.match(/\((\d+)\)/)[1]);
            checkMaxValue(count);


        });

        $('#select-barcode-filter-range .mdata_barcode_prefix').on('change', function (event) {

            var selectedOption = $(this).find('option:selected');
            var text = selectedOption.text();
            var count = parseInt(text.match(/\((\d+)\)/)[1]);
            checkMaxValue(count);


        });
        function checkMaxValue(count) {

            $('#select-barcode-filter-range-old [name="starting_range"]').on('keyup', function() {
                var startingRange = $(this).val();
                if (startingRange>count || startingRange<=0){
                    $('#error-max').removeClass('d-none');
                    $('#save-old-range-btn').addClass('d-none');

                }else{
                    $('#error-max').addClass('d-none');
                    $('#save-old-range-btn').removeClass('d-none');
                }

            });

            // Attach keyup event handler to the ending range input field
            $('#select-barcode-filter-range-old [name="ending_range"]').on('keyup', function() {
                var endingRange = $(this).val();
                if (endingRange>count || endingRange<=0){
                    $('#error-max').removeClass('d-none');
                    $('#save-old-range-btn').addClass('d-none');

                }else{
                    $('#error-max').addClass('d-none');
                    $('#save-old-range-btn').removeClass('d-none');
                }

            });
            $('#select-barcode-filter-range [name="show_range"]').on('keyup', function() {
                var showRange = $(this).val();

                if (showRange>count || showRange<=0){
                    $('#error-max').removeClass('d-none');
                    $('#save-range-btn').addClass('d-none');

                }else{
                    $('#error-max').addClass('d-none');
                    $('#save-range-btn').removeClass('d-none');
                }

            });


        }






        function getBarcodePrefix(mdata_barcode_prefix) {

            $.ajax({
                url: "{{url('/get-prefix')}}/" + barcode_type,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('#select-barcode-range').removeClass('d-none');

                    $('#generate_barcode_form #mdata_barcode_prefix_number_start').empty();
                    $('#generate_barcode_form #mdata_barcode_prefix_number_end').empty();
                    $.each(data, function (key, value) {
                        if (!$.trim(data)) {
                            $('#generate_barcode_form .mdata_barcode_prefix_number_end').addClass('d-none');
                        } else {
                            $('#generate_barcode_form .mdata_barcode_prefix_number_end').removeClass('d-none');
                            $('#generate_barcode_form .mdata_barcode_prefix_number_start').removeClass('d-none');
                            $('#generate_barcode_form #mdata_barcode_prefix_number_start').append('<option value="' + value.mdata_barcode_prefix_number + '">' + value.mdata_barcode_prefix_number + '</option>');
                            $('#generate_barcode_form #mdata_barcode_prefix_number_end').append('<option value="' + value.mdata_barcode_prefix_number + '">' + value.mdata_barcode_prefix_number + '</option>');
                            $('#generate_barcode_form #mdata_barcode_prefix_number_start.selectpicker').selectpicker('refresh');
                            $('#generate_barcode_form #mdata_barcode_prefix_number_end.selectpicker').selectpicker('refresh');
                        }
                    });
                    $('#generate_barcode_form .selectpicker').selectpicker('refresh');


                },


            });

        }

        function generatePDF(data) {
            var barcodeDefinitions = [];
            var loadedBarcodes = 0;
            var totalBarcodes = 0;

            $.each(data, function (key, value) {
                var barcodes = value.barcodes;
                if (barcodes && barcodes.length > 0) {
                    totalBarcodes += barcodes.length;
                    $.each(barcodes, function (index, barcode) {

                        var mdataBarcodePrefixNumber = barcode.mdata_barcode_prefix_number;
                        var mdataBarcodeaddress = barcode.address;
                        if (mdataBarcodePrefixNumber) {
                            let barcodeHTML =
                                '<div class="col-lg-6" style="margin-bottom:0px; margin-top:0px; padding-top:0px;padding-bottom:30px;">' +
                                '<div class="haefaCardBox border p-2 text-center">' +
                                '<div class="logoBox d-flex align-items-center">' +
                                '<img src="data:image/png;base64,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" style="width: 30px; height: auto">' +
                                '<p class="ms-1 mb-0 mx-auto text-center" style="font-size: 12px">HEALTH AND EDUCATION FOR ALL (HAEFA)</p>' +
                                '</div>' +
                                '<p class="ms-1 mb-0 mx-auto text-center" style="font-size: 9px">'+ mdataBarcodeaddress +'</p>' +
                                '<h5 class="fs-5 mb-0 py-1 fw-normal">Health Card</h5>' +
                                '<img class="barCode" src="" style="width:250px !important; height:55px !important;" alt="barCode">' +
                                '<h5 class="fw-semibold">ID NO: ' + mdataBarcodePrefixNumber + '</h5>' +

                                '</div></div>';


                            // Append barcodeHTML to a div
                            var cardBox = $(barcodeHTML);
                            $('#barcode-section').append(cardBox);

                            // Generate barcode using jsbarcode
                            JsBarcode(cardBox.find('.barCode')[0], mdataBarcodePrefixNumber, {
                                displayValue: false // Hide the barcode value text
                            });






                        }
                    });
                }
            });
            let start = $('#mdata_barcode_prefix_number_start').val();
            let end = $('#mdata_barcode_prefix_number_end').val();
            $('#warning-downloading').removeClass('d-none');

            html2pdf().set({
                margin: [0.5, 0.5, 0.5, 0.5],
                filename:  start + '-' + end + '.pdf',
                image: { type: 'png', quality: 1 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            }).from($('#barcode-section')[0]).save().then(function() {
                $('#warning-downloading').addClass('d-none');
                window.location.reload();
            })

            $('#select-barcode-range #mdata_barcode_prefix_number_start').empty();
            $('#select-barcode-range #mdata_barcode_prefix_number_end').empty();
            $('#select-barcode-range #mdata_barcode_prefix_number_start.selectpicker').selectpicker('refresh');
            $('#select-barcode-range #mdata_barcode_prefix_number_end.selectpicker').selectpicker('refresh');
            $('#select-barcode-filter-range #mdata_barcode_prefix').empty();
            $('#select-barcode-filter-range #show_range').val('');
            $('#select-barcode-filter-range #mdata_barcode_prefix.selectpicker').selectpicker('refresh');






        }



        function showStoreFormModal(modal_title, btn_text) {
            $('#store_or_update_form')[0].reset();
            $('#store_or_update_form #update_id').val('');
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();

            $('#store_or_update_form #image img.spartan_image_placeholder').css('display', 'block');
            $('#store_or_update_form #image .spartan_remove_row').css('display', 'none');
            $('#store_or_update_form #image .img_').css('display', 'none');
            $('#store_or_update_form #image .img_').attr('src', '');
            $('#store_or_update_form #lifestyle_image img.spartan_image_placeholder').css('display', 'block');
            $('#store_or_update_form #lifestyle_image .spartan_remove_row').css('display', 'none');
            $('#store_or_update_form #lifestyle_image .img_').css('display', 'none');
            $('#store_or_update_form #lifestyle_image .img_').attr('src', '');
            $('.selectpicker').selectpicker('refresh');
            $('#store_or_update_modal').modal({
                keyboard: false,
                backdrop: 'static',
            });
            $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square"></i> ' + modal_title);
            $('#store_or_update_modal #save-btn').text(btn_text);
        }

    </script>
@endpush
