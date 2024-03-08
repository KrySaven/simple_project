@push('myscripts')
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {
            $("#searchValue").autocomplete({
                autoFocus: true,
                source: function(request, response) {
                    $.ajax({
                        url: "{!! route('autoCompleteProduct') !!}",
                        type: 'GET',
                        dataType: "json",
                        global: false,
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term,
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    $('#searchValue').val(ui.item.code);
                    addUniqueProduct();
                    return false;
                },
            });
        });

        // SET INDEX FOR ROW WHEN APPEND
        let add_index = -1;
        // Add events for old rows after validation
        $(document).ready(() => {
            $('#product_row tr').each(function() {
                // Get latest index
                add_index = $(this).attr('data-index');
                addRowEvents(add_index);
            })
            add_index++;
            reorder();
            updateGrandTotal();
        });

        function addUniqueProduct() {
            var searchValue = $("#searchValue").val();
            if (searchValue != '') {
                $.ajax({
                    url: "{!! route('searchItemProduct') !!}",
                    method: 'get',
                    dataType: 'html',
                    global: false,
                    data: {
                        searchValue: searchValue,
                        index: add_index
                    },
                    success: function(data) {
                        $('#product_row').append(data);
                        // console.log(add_index++);
                        addRowEvents(add_index++);
                        $(document).find('select').selectpicker('refresh');
                        reorder();
                    }
                });
                $("#searchValue").val('');
            }
        }

        const grand_total_input = $(`input[name=grand_total]`)
        const grand_total_label = $(`span.grand_total`)
        const amount_input = $(`input[name=amount]`)
        const amount_label = $(`span.amount`)
        const discount_input = $(`input[name=discount]`)
        const discount_label = $(`span.discount`)
        const total_qty_input = $(`input[name=total_qty]`)
        const total_qty_label = $(`span.total_qty`)
        let grand_total = 0;
        grand_total = $('input[name^=total_prices]').val();

        function updateGrandTotal() {
            let grand_total = 0;
            let total_qty = 0;
            $('input[name^=total_prices]').each(function() {
                grand_total += parseInt($(this).val(), 10) || 0
            })

            $('input[name^=qtys]').each(function(){
                total_qty += parseInt($(this).val(), 10) || 0
            })

            const formatted_total = Intl.NumberFormat('en-US').format(grand_total)
            amount_label.text(formatted_total)
            amount_input.val(grand_total)
            grand_total_label.text(formatted_total)
            grand_total_input.val(grand_total)
            total_qty_input.val(total_qty)
            total_qty_label.text(total_qty)
        }

        // REORDR THE INDEX OF ROW
        function reorder() {
            const trs = $('#product_row tr');
            trs.each(function(index, tr) {
                $(tr).find('th').text(index + 1);
            })
        }

        function addRowEvents(row_no) {
            const row = `tr#row_${row_no}`
            //const size_inputs = $(`${row} input[name^=sizes]`)
            const unit_price_input = $(`${row} input[name^=unit_prices]`)
            const qty_input = $(`${row} input[name^=qty]`)
            // const qty_label = $(`${row} span.total_qty`)
            const total_price_input = $(`${row} input[name^=total_prices]`)
            const total_price_label = $(`${row} span.total_prices`)
            const delete_btn = $(`${row} td:last-child i`)

            function calculateRowTotal() {
                let total_price = unit_price_input.val() * qty_input.val()
                total_price_input.val(total_price).trigger('change')
                total_price_label.text(total_price)
                updateGrandTotal()
            }

            qty_input.keyup(function(e) {
                let total_qty = parseInt($(this).val(), 10) || 0;
                calculateRowTotal();
                updateGrandTotal();
            })

            delete_btn.click(function() {
                if (confirm("Are you sure you want to delete this row?")) {
                    $(row).remove()
                    updateGrandTotal()
                    reorder()
                }

            })
        }


    </script>

    <script>
        function refreshSelect() {
            return $(".selectpicker").selectpicker()
        }
    </script>

    <script>
        $('.colorpicker').colorpicker();
        $('.datepicker').bootstrapMaterialDatePicker({
            clearButton: true,
            weekStart: 1,
            time: false,
            date: true,
        });
    </script>
    <script>
        function showValidationMessage(response, formSelector = null) {
            formSelector = (formSelector || $('form'));
            var errors = response.messages;
            formSelector.find("span[id$='_error']").addClass('hidden');
            formSelector.find('input').removeClass('error')
            $.each(errors, function(key, val) {
                console.log(key);
                var str_key = key.replace('.', '_');
                var error_id = "#" + str_key + "_error";
                if (formSelector.length > 0) {
                    formSelector.find('input[name="'+str_key+'"] , select[name="'+str_key+'"], textarea[name="'+str_key+'"]').addClass('error')
                    formSelector.find(error_id).show();
                    formSelector.find(error_id).removeClass('hidden');
                    formSelector.find(error_id).addClass('text-danger');
                    formSelector.find(error_id).text(val[0]);
                }
                formSelector.find('input,select,textarea').on('change',function(){
                    if ($(this).hasClass('error')) {
                        $(this).removeClass('error')
                        $(this).parents('.form-group').find('.error-msg').text('')
                    }
                })
            });
        }
        $('#btnSubmit').click(function(){
            var form_data = $('#purchase-return-form');
            var data = new FormData($('#purchase-return-form')[0]);
            var btnSubmit = form_data.find("button[type='submit']");
            $.ajax({
                type: form_data.attr('method'),
                url: form_data.attr('action'),
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                success: function (response) {
                    if (response.code === 401) {
                        console.log(response);
                        btnSubmit.attr('disabled', false);
                        showValidationMessage(response)
                    }
                    if (response.code === 200) {
                        console.log(response);
                        btnSubmit.attr('disabled', false);
                        showValidationMessage(response)
                    }

                    if (response.code === 200, response.data && response.data.reload_url) {
                         Swal.fire({
                            icon: 'success',
                            title: 'Insert Success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(() => {
                            location.href = response.data.reload_url
                        }, 1 * 1000);
                    } else {
                        btnSubmit.attr('disabled', false);
                    }
                },
                error: function (data) {
                    var errors = $.parseJSON(data.responseText);
                    console.log(errors);

                }
            });
        });
    </script>

    <script>
        var limit = 5;
            $(document).ready(function(){
                $('#pdfUpload').change(function(){
                    var files = $(this)[0].files;
                    // console.log(files.length);
                    $("#number_files").text(files.length);
                    if(files.length > limit){
                        alert("You can select max "+limit+" images.");
                        $('#pdfFiles').val('');
                        $("#number_files").text(0);
                        return false;
                    }
                });
            });
    </script>

@endpush
