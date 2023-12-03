document.addEventListener("keydown", function(event) {
    "use strict";
    if (event.altKey && event.code === "KeyO")
    {
        submit_order();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyZ")
    {
        $('#payment_close').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyS")
    {
        $('#order_complete').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyC")
    {
        emptyCart();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyA")
    {
        $('#add_new_customer').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyN")
    {
        $('#submit_new_customer').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyK")
    {
        $('#short-cut').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyP")
    {
        $('#print_invoice').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyQ")
    {
        $('#search').focus();
        $("#search-box").css("display", "none");
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyE")
    {
        $("#search-box").css("display", "none");
        $('#extra_discount').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyD")
    {
        $("#search-box").css("display", "none");
        $('#coupon_discount').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyB")
    {
        $('#invoice_close').click();
        event.preventDefault();
    }

});



"use strict";
function submit_order(){
    $("#search-box").css("display", "none");
    let cus_id = $('#customer').val();
    $('#customer_id').val(cus_id);
    let  cart_id = $('#cart_id').val();
    $('#order_cart_id').val(cart_id);
    //console.log(cus_id);
    if(cus_id == 'null')
    {
        toastr.warning('Please, Select Customer First.!', {
            CloseButton: true,
            ProgressBar: true
        });
        //console.log("null");
    }else{
        let payementId = $('#payment_opp').val();
        //console.log(payementId);
        //$('#cash_amount').removeAttr('required');
        if(payementId == 1)
        {
            let tt = $('#total_price').text();
            //console.log(tt);
            $('#cash_amount').attr({'min': tt,'required':true});
        }
        $("#paymentModal").modal();
    }
}


"use strict";
function price_calculation() {
    //console.log('reee');
    let collectedCash = $('#cash_amount').val();
    let order_total = $('#total_price').text();
    let total = parseFloat(collectedCash - order_total).toFixed(2);
    $('#returned').val(total);
}

"use strict";
function customer_Balance_Append(val)
{
    let customerId = val;
    $('#customer_balance').remove();
    if(customerId !=0){
        console.log(customerId);
        $('#payment_opp').append('<option id="customer_balance" value="0">Customer Balance</option>')
    }
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


function addon_quantity_input_toggle(e) {
    var cb = $(e.target);
    if (cb.is(":checked")) {
        cb.siblings('.addon-quantity-input').css({'visibility': 'visible'});
    } else {
        cb.siblings('.addon-quantity-input').css({'visibility': 'hidden'});
    }
}

function checkAddToCartValidity() {
    var names = {};
    $('#add-to-cart-form input:radio').each(function () { // find unique names
        names[$(this).attr('name')] = true;
    });
    var count = 0;
    $.each(names, function () { // then count them
        count++;
    });
    if ($('input:radio:checked').length == count) {
        return true;
    }
    return false;
}

function cartQuantityInitialize() {
    $('.btn-number').on('click',function (e) {
        e.preventDefault();

        var fieldName = $(this).attr('data-field');
        var type = $(this).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (type == 'minus') {

                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if (type == 'plus') {

                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });

    $('.input-number').focusin(function () {
        $(this).data('oldValue', $(this).val());
    });

    $('.input-number').on('change',function () {

        let minValue = parseInt($(this).attr('min'));
        let maxValue = parseInt($(this).attr('max'));
        let valueCurrent = parseInt($(this).val());

        var name = $(this).attr('name');
        if (valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Cart',
                text: 'Sorry, the minimum value was reached'
            });
            $(this).val($(this).data('oldValue'));
        }
        if (valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Cart',
                text: 'Sorry, stock limit exceeded.'
            });
            $(this).val($(this).data('oldValue'));
        }
    });
    $(".input-number").on('keydown',function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
}

$(function () {
    $(document).on('click', 'input[type=number]', function () {
        this.select();
    });
});


jQuery(document).on('mouseup',function (e) {
    var container = $(".search-card");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.addClass('d-none');
    }
});

function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

