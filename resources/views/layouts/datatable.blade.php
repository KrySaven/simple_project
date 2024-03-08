<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap Core Js -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>

<!-- Select Plugin Js -->
<script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

<!-- Slimscroll Plugin Js -->
<script src="{{ asset('plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>
<!-- Waves Effect Plugin Js -->
<script src="{{ asset('plugins/node-waves/waves.js') }}"></script>
<script src="{{ asset('plugins/autosize/autosize.js')}}"></script>

    <!-- Moment Plugin Js -->
    <script src="{{ asset('plugins/momentjs/moment.js')}}"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
{{--LIST ++++++++++++++++++++++--}}
<script src="{{ asset('plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>
<!-- Custom Js -->
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('js/pages/ui/dialogs.js') }}"></script>
<script src="{{ asset('js/pages/tables/jquery-datatable.js') }}"></script>

<!-- Demo Js -->
<script src="{{ asset('js/demo.js') }}"></script>
<script>
    $(document).ready(function(){
        $(function(){
            $('#date,#date_from,#date_to,#date_return').bootstrapMaterialDatePicker({ weekStart : 0, time: false});
        });
        $('.openPopup').on('click',function(){
            var dataURL = $(this).attr('data-href');
            $('.modal-body').load(dataURL,function(){
                $('#myModal').modal({show:true});
            });
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.popupshow').click();
    });
</script>