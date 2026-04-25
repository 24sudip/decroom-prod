<script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.select2').select2();

    $('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
    });

    $("#userform").on("click", ".edit_row", function() {
        if ($(this).data("pid")) {
            let row = $(this).data();
            $('#product_id').val(row.pid).trigger('change');
            $("#quantity").val(row.quantity);
            $("#rate").val(row.price);
            $("#tax_rate").val(row.taxrate);
            $("#total").val(row.total);
            $("#unitname").html(row.unitname);
            $("#manufacturer_date").val(row.mfg);
            $("#expire_date").val(row.exp);
        }
    });

    $("#userform").on("click", ".delete_row", function() {
        if ($(this).data("pid")) {
            if (confirm('Are you sure to delete this record?')) {
                let product_id = $(this).data("pid");
                let route = '{{ route('response.delpurrow') }}';

                $.post(route, {
                        product_id: product_id
                    })
                    .done(function(data) {
                        $("#pur_rows").html(data);
                    })
                    .fail(function() {
                        alert("Delete failed.");
                    });
            }
        }
    });

    function checkValid(number) {
        return +number <= 0;
    }

    function addRow(product_id, quantity, price, tax_rate, total, unit_name, pname, batch_no, manufacturer_date,
        expire_date) {
        $("#add_row").hide();
        $("#btn_looding").show();

        let route = '{{ route('response.addpurrow') }}';

        $.post(route, {
                product_id,
                quantity,
                price,
                tax_rate,
                total,
                unit_name,
                pname,
                batch_no,
                manufacturer_date,
                expire_date
            })
            .done(function(data) {
                $("#pur_rows").html(data);
                setTimeout(() => {
                    $("#add_row").show();
                    $("#btn_looding").hide();
                }, 1000);

                $("#quantity, #rate, #tax_rate, #total").val('');
                $("#batch_no, #unitname, #cur_stock, #manufacturer_date, #expire_date").html('').val('');
                $('#product_id').val('').trigger('change');
            })
            .fail(function() {
                $("#add_row").show();
                $("#btn_looding").hide();
                alert("Error adding row.");
            });
    }

    $("#add_row").click(function(e) {
        e.preventDefault();

        let product_id = $("#product_id").val();
        if (!product_id) return alert("Select Product!");

        let quantity = parseFloat($("#quantity").val()) || 0;
        if (checkValid(quantity)) return alert("Please enter a valid quantity");

        let price = parseFloat($("#rate").val()) || 0;
        if (checkValid(price)) return alert("Please enter a valid rate");

        let tax_rate = parseFloat($("#tax_rate").val()) || 0;
        let total = parseFloat($("#total").val()) || 0;
        if (checkValid(total)) return alert("Total amount must not be empty!");

        let unit_name = $("#product_id").find(':selected').data('unit');
        let pname = $("#product_id").find(':selected').data('pname');
        let batch_no = $("#product_id").find(':selected').data('batchno');
        let manufacturer_date = $("#manufacturer_date").val();
        let expire_date = $("#expire_date").val();

        addRow(product_id, quantity, price, tax_rate, total, unit_name, pname, batch_no, manufacturer_date,
            expire_date);
    });

    $("#quantity, #rate, #tax_rate").keyup(function() {
        let quantity = parseFloat($("#quantity").val()) || 0;
        let price = parseFloat($("#rate").val()) || 0;
        let tax_rate = parseFloat($("#tax_rate").val()) || 0;

        if (!$("#product_id").val()) {
            $("#quantity, #rate, #tax_rate").val(0);
            alert("Select Product!");
            return;
        }

        let total = (quantity * price);
        let tax_amt = (total * tax_rate / 100);
        let final_total = (total + tax_amt).toFixed(2);

        $("#total").val(final_total);
    });

    $("#product_id").change(function() {
        let selected = $(this).find(':selected');

        let stock = selected.data('stock') || 0;
        let unitid = selected.data('unitid') || '';
        let unit_name = selected.data('unit') || '';
        let pur_price = selected.data('pur') || 0;
        let batch_no = selected.data('batchno') || '';

        $("#cur_stock").html(stock);
        $("#batch_no").html(batch_no);
        $("#unitname").html(unit_name);
        $("#unit_id").val(unitid);
        $("#rate").val(pur_price);

        $("#quantity, #tax_rate, #total, #manufacturer_date, #expire_date").val('');
    });
</script>
