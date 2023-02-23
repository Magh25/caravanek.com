<script>
    jQuery(document).ready(function () {
        'use strict';
        $("{{ $validator['selector'] }}").each(function () {
            $(this).validate({
                errorElement: 'span',
                errorClass: 'invalid-feedback',

                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length ||
                        element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-control').removeClass('is-valid').addClass('is-invalid');
                },

                @if (isset($validator['ignore']) && is_string($validator['ignore']))
                    ignore: "{{ $validator['ignore'] }}",
                @endif

                unhighlight: function (element) {
                    $(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid');
                },

                success: function (element) {
                    $(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid');
                },

                focusInvalid: false,
                @if (config('core.js-validation.js-validation.focus_on_error'))
                invalidHandler: function (form, validator) {

                    if (!validator.numberOfInvalids())
                        return;

                    $('html, body').animate({
                        scrollTop: $(validator.errorList[0].element).offset().top
                    }, {{  config('core.js-validation.js-validation.duration_animate') }});
                    $(validator.errorList[0].element).focus();

                },
                @endif

                rules: {!! json_encode($validator['rules']) !!}
            });
        });
    });
</script> 
<script> 
    jQuery(document).ready(function () {
        /* Property Features code start here */
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
        /* Property Features code end here */

        /**/

        function hideAll() {
            jQuery('.pf-text-wrap').hide();
            jQuery('.pf-checkbox-wrap').hide();
            jQuery('.pf-select-wrap').hide();
        }
        var checkOptions = jQuery('.properties-features-select').html();
        if(checkOptions == ''){
            hideAll();
        }    
        /*Properties add and edit code start here*/
        jQuery('.form-group #type_id.form-control').on('change',function(){
            type_ids = ','+jQuery(this).data('typids')+',';
            _val = jQuery(this).val().trim(); if( _val == '') _val = 0;

            console.log(type_ids);
            console.log(type_ids.indexOf(','+_val+','));
            console.log(jQuery(this).val().trim());

            if( type_ids.indexOf(','+_val+',') != -1)
                $(".fixed_type_field").addClass('hidden')
            else
                $(".fixed_type_field").removeClass('hidden')

            if( type_ids.indexOf(','+_val+',') != -1)
                $(".none_fixed_type_field").removeClass('hidden')
            else
                $(".none_fixed_type_field").addClass('hidden')
            
           // if( $(this).hasClass('editing-property') )
         //   $(".widget-body").find('button[type="submit"].btn-success').trigger('click');
            getSectionByCategory(_val);  
        })

        jQuery(document).on('change','.properties-features-select',function(event) { 
            var type = jQuery(this).find(':selected').data('type');
            var select = jQuery(this).find(':selected').data('select');
            jQuery(this).parent().siblings('.pf-text-wrap').hide();
            jQuery(this).parent().siblings('.pf-checkbox-wrap').hide();
            jQuery(this).parent().siblings('.pf-select-wrap').hide();
            jQuery(this).parent().siblings('.pf-'+type+'-wrap').show();
            if(type == 'select'){
                var selectOption = select.split(",");
                var  options = '';
                selectOption.forEach(function(item, index){
                    options += '<option  value="'+item+'">'+item+'</option>'; 
                });
                jQuery(this).parent().siblings('.pf-'+type+'-wrap').find('select').html(options);
            }
        })

        function getSectionByCategory(type_id){
            $(".list-property_features .content").html('').addClass("hidden");
            $(".list-property_features .msg").html('Loading features ...').removeClass("hidden");
            $.ajax({
                headers: {
                    'Content-Type':'application/json'
                },
                type:'GET',
                url:'/api/v1/get-features-by-category/'+ type_id ,
                success:function(response) {

                    if( response.data ){
                        $(".list-property_features .content").html(response.data).removeClass("hidden");
                        $(".list-property_features .msg").addClass("hidden");
                    } else {
                        $(".list-property_features .msg").html('No features available !');
                    }
                }, error: function(){
                    $(".list-property_features .msg").html('<b color="#d00">Unable to load features, refresh page and try again!</b>');
                }
            }); 
        } 
        /*Properties add and edit code end here*/
        jQuery('.add_new_fields').on('click',function(event) {
            var options = jQuery('.properties-features-select').html();
            // setTimeout(function(event) {
                var data = jQuery(".data-repeater-item:last-child").find('.properties-features-select').html(options);
                var type =  jQuery(".data-repeater-item:last-child").find('.properties-features-select').find(':selected').data('type');
                    jQuery(".data-repeater-item:last-child").find('.pf-'+type+'-wrap').show();
            // }, 1000);
        })
        // add_new_fields
    }); 
</script>
