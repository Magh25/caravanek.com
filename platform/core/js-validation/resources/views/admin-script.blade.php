<script>
    jQuery(document).ready(function () {
        var select = jQuery('.ctm-feature-select').val();
        if(select == "select"){
             jQuery('.ctm-feature-select-option').parent().show();
        }else{
            jQuery('.ctm-feature-select-option').parent().hide();
        }
        jQuery('.ctm-feature-select').on('change',function(){
            var select = jQuery(this).val();
            if(select == "select"){
                 jQuery('.ctm-feature-select-option').parent().show();
            }else{
                jQuery('.ctm-feature-select-option').parent().hide();
            }
        })
    });
</script>
