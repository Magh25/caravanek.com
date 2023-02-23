<section class="gray-simple">
    <div class="container">
        <div class="row">
            <div class="col-12 render_status alert">

            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $.get("{{ route('payment_status') }}", function(data) {
            //data = $.parseJSON(data);
            switch (data.status) {
                case 1:
                    $('.render_status').addClass('alert-danger');
                    $('.render_status').html('{{__("The payment failed")}}');
                    break;
                case 2:
                    $('.render_status').addClass('alert-warning');
                    $('.render_status').html('{{__("Checking the payment process")}}');
                    break;
                case 3:
                    $('.render_status').addClass('alert-success');
                    $('.render_status').html('{{__("Paid successfully")}}');
                    break;
                default:
                    

            }
            setTimeout(function(){location.replace(data.return_url)},4000);
        }); 
    });
</script>
