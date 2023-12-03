"use strict";
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#viewer').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#customFileEg1").on('change',function () {
    readURL(this);
});

"use strict";
function update_customer_balance_cl(val)
{
    $("#customer_id").val(val);
    console.log(val);
}

    function printDiv(divName) {
        if($('html').attr('dir') === 'rtl') {
            $('html').attr('dir', 'ltr')
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            $('.width-inone').attr('dir', 'rtl')
            window.print();
            document.body.innerHTML = originalContents;
            $('html').attr('dir', 'rtl')
            location.reload()
        }else{
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload()
        }
    }

"use strict";
$(document).on('ready', function () {
    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});

function getRndInteger() {
    return Math.floor(Math.random() * 90000) + 10000;
}

function discount_option(val) {
    if ($(val).val() == 'percent') {
        $("#percent").removeClass('d-none').show();
        $("#amount").hide();
        //console.log("percent");
    }
    if ($(val).val() == 'amount') {
        $("#amount").removeClass('d-none').show();
        $("#percent").hide();
        //console.log("amount");
    }
}

"use strict";
function getRequest(route, id) {
    $.get({
        url: route,
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty().append(data.options);
        },
    });
}

"use strict";
function update_quantity_plst(val)
{
    $("#product_id").val(val);
    console.log(val);
}


"use strict";
function update_quantity_sto(val)
{
    $("#product_id").val(val);
    console.log(val);
}

/*supplier transaction list*/
"use strict";
$("#start_date").on('change',function () {
    let start = $('#start_date').val();
    if (start) {
        $('#end_date').attr('min', $(this).val());
        $('#end_date').attr('required', true);
        //console.log("jkfjkf");
    }

});
$("#end_date").on("change", function () {
    $('#start_date').attr('max', $(this).val());
});

function add_new_purchase(val) {
    $("#supplier_id").val(val);
    //console.log(val);
}

function due_calculate() {
    let purchase_amount = $('#purchased_amount').val();
    let paid_amount = $('#paid_amount').val();
    let due_amount = parseInt(purchase_amount) - parseInt(paid_amount);
    $('#due_amount').val(due_amount);
    //console.log(purchase_amount);
    $('#paid_amount').attr('max', purchase_amount);
}

function payment_due(val) {
    $("#due_pay_supplier_id").val(val);
    console.log(val);
}

"use strict";
function due_remain() {
    let total_due_amount = $('#total_due_amount').val();
    let pay_amount = $('#pay_amount').val();
    let remain_due = parseInt(total_due_amount) - parseInt(pay_amount);
    $('#remaining_due_amount').val(remain_due);
}


 "use strict";
    function accountChangeTr(val)
    {
        let hide_id = val;
        $('.account').show();
        $('#account_to_id').removeClass('d-none');
        $("#account_to_id option[value='"+hide_id+"']").hide();
        console.log(val);
    }


"use strict";
// INITIALIZATION OF CHARTJS
// =======================================================
   Chart.plugins.unregister(ChartDataLabels);
    $('.js-chart').each(function () {
    $.HSCore.components.HSChartJS.init($(this));
    });

$.HSCore.components.HSChartJS.init($('#updatingData_monthly'));
$.HSCore.components.HSChartJS.init($('#updatingData_yearly'));


"use strict";
$("#lastMonthStatistic").hide();
function chart_statistic(val)
{
let chart =val;
console.log(chart);
if(val=='monthly')
{
    $("#lastYearStatistic").hide();
    $("#lastMonthStatistic").show();
}else{
    $("#lastMonthStatistic").hide();
    $("#lastYearStatistic").show();
}
}


  "use strict";
        function update_quantity(val)
        {
            $("#product_id").val(val);
            console.log(val);
        }
