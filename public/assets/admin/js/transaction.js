 "use strict";
        $("#start_date").on('change',function(){
            let start = $('#start_date').val();
            if(start){
                $('#end_date').attr('min',$(this).val());
                $('#end_date').attr('required',true);
                //console.log("jkfjkf");
            }
        });
        $("#end_date").on("change", function () {
            $('#start_date').attr('max',$(this).val());
        });

        function export_data()
        {
            let accountId = $('#account_id').val();
            let tranType = $('#tran_type').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            $('#expt_form').submit();
        }
