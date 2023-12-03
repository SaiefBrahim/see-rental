"use strict";
$(document).on('ready', function () {
    // INITIALIZATION OF FLATPICKR
    // =======================================================
    $('.js-flatpickr').each(function () {
        $.HSCore.components.HSFlatpickr.init($(this));
    });
});

function coupon_type_change(order_type) {
    if(order_type=='first_order'){
        $('#limit-for-user').addClass('d-none');
    }else{
        $('#limit-for-user').removeClass('d-none');
    }
}

function discount_amount(discount_type)
{ console.log(discount_type);
    if(discount_type=='percent'){
        $('#max_discount').removeClass('d-none')
    }else{
        $('#max_discount').addClass('d-none').removeClass('d-block')
    }
}


"use strict";
function checkDate(){
    let starDate = $("#start_date").val();
    let expiredDate = $("#expire_date").val();
    console.log($("#start_date").val());
    console.log($("#expire_date").val());
    if(expiredDate < starDate)
    {
        toastr.warning('Expiry Date cannot be previous from start Date!', {
            CloseButton: true,
            ProgressBar: true
        });
        $("#expire_date").val('');
    }
}
