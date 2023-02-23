
@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!}>
    @endif
@endif

@if ($showLabel && $options['label'] !== false && $options['label_show'])
    {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
@endif

@php
    if( empty($options['values'])) $options['values'] = [];
    foreach($options['values'] as $idx => $val){
        if( empty($val['name']) )
            unset($options['values'][$idx]);
        else
            $options['values'][$idx]['price'] = !empty($val['price']) && is_numeric($val['price']) ? number_format($val['price'],2,'.','') : '0.00'; 
    }    
@endphp 

@if ($showField)
<div class="form-control" style=" border:none; padding-left: 0;">
        
    <style type="text/css">
        .list_multifields .field_headings{ margin-bottom: 13px;  font-weight: bold; background: #f2f2f2;  }
        .list_multifields .field_headings span{ display: block; padding: 5px 10px; }
        .list-cloned-items input{ width: 100%; border:1px solid #ccc; border-radius: 4px; padding: 5px; }
        .list-cloned-items .row{ margin-bottom: 13px; }
        .list-cloned-items .row div input,.list-cloned-items .row div i{ margin-left: 10px; }
        .list-cloned-items .row i{ color: #d00; border-radius: 4px; border:1px solid #d00; padding: 8px; display: inline-block; }
    </style>
    <script type="text/javascript">
        $(document).on("click",".btn-clone-unitstype-item",function(){
            var _target = $("#"+$(this).data('target'));
            var _clone = _target.find('> .clone');

            if( _clone.length ){
                var _tag = $(this).data('tag') != undefined ? $(this).data('tag').trim() : 'div',_class = $(this).data('class') != undefined ? ' '+$(this).data('class').trim() : '',_index = $(this).data('index'), _counter =  _target.data('counter') != undefined ?  _target.data('counter') : _target.find('> .item').length;
                _counter++;
                _target.attr('data-counter',_counter).data('counter',_counter);  
                
                var _clone = _clone.clone();
                _clone.find('[disabled]').prop('disabled',false);
                _clone.find('.dsbl').prop('disabled',true);
                _clone.find('.dsbl-'+_index.replace('[','').replace(']','')).prop('disabled',false);
                
                var _html = _clone.html().replace(new RegExp(_index.replace(/[.*+\-?^${}()|[\]\\]/g, '\\$&'), 'g'), '['+_counter+']').replace(new RegExp('_'+_index, 'g'), _counter);
                _target.append('<'+_tag+' class="row item'+_class+'">'+_html+'</'+_tag+'>');
            }
        });

        $(document).on("click",".btn-clone-unitstype-item-delete",function(e){
            e.preventDefault();
            if( confirm("Are you sure to delete this item ?") ){
                var _itm = $(this).closest('.item');
                _itm.fadeOut(); setTimeout(function(){ _itm.remove(); createPBFormJson(); },500);
            }
        });
    </script>
    <div class="list_multifields">
        <div class="field_headings">
            <div class="row">
                <div class="col-lg-5"><span>Name</span></div> 
                <div class="col-lg-1"><span>&nbsp;</span></div>
            </div>
        </div>   
  
        <div class="list-cloned-items" id="listunitstype">
            @foreach($options['value'] as $idx => $val) 
            <div class="item row">
                <div class="col-lg-5">  
                    <input type="text" class="field" name="unitstype[{{$idx}}][unitstype]" value="{{$val['unitstype']}}" />
                </div> 
                <div class="col-lg-2">
                    <a class="btn-clone-unitstype-item-delete"><i class="fa fa-trash btn-remove"></i></a>
                </div>
            </div>
            @endforeach
            <div class="clone row d-none">
                <div class="col-lg-5">  
                    <input type="text" class="field" disabled="disabled" name="unitstype[X][unitstype]" />
                </div> 
                <div class="col-lg-2">
                    <a class="btn-clone-unitstype-item-delete"><i class="fa fa-trash btn-remove"></i></a>
                </div>
            </div>
        </div>
        <button type="button" style="margin-left: 10px;" class="btn btn-success btn-clone-unitstype-item" data-target="listunitstype" data-index="[X]">Add New</button>
    </div>  
    
    
</div>

    @include('core/base::forms.partials.help-block')
@endif

@include('core/base::forms.partials.errors')

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif
