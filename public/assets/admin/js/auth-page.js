"use strict";
$(document).on('ready', function () {
    // INITIALIZATION OF SHOW PASSWORD
    // =======================================================
    $('.js-toggle-password').each(function () {
        new HSTogglePassword(this).init()
    });

    // INITIALIZATION OF FORM VALIDATION
    // =======================================================
    $('.js-validate').each(function () {
        $.HSCore.components.HSValidation.init($(this));
    });
});

"use strict";
function copy_cred() {
    $('#signinSrEmail').val('admin@admin.com');
    $('#signupSrPassword').val('12345678');
    toastr.success('Copied successfully!', 'Success!', {
        CloseButton: true,
        ProgressBar: true
    });
}
