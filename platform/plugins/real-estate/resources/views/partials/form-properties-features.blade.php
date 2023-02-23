<style type="text/css">
	.note.note-success{ display: none; } 
	.list-property_features .hidden{ display: none; }
	.feature_groups{ margin-bottom: 10px; }
	.feature_groups h5{ background: #f0f0f0;
padding: 5px 10px;
font-size: 14px;
line-height: 18px;
margin:0px 0 15px 0;}
	.feature_groups label{ font-weight: normal; }
	.feature_groups label.tp{ margin-top: 0px; }
	.feature_groups .text-right{ text-align: right; }
</style>
<div class="list-property_features">	
	<div class="msg" @php echo count($features) ? "hidden" : ""; @endphp">@php echo !count($features) ? trans('plugins/real-estate::property.form.no_features_available') : ""; @endphp</div>
	<div class="content main-form @php echo !count($features) ? "hidden" : ""; @endphp">
		@foreach ($features as $group)
		<div class="feature_groups form-body">
			<h5>{{ $group->name }}</h5>
			<div class="fields row">
			@foreach ($group->fields as $feature)
				<div class="col-md-4 col-sm-6">
					<div class="form-group mb-3">
						<label for="feature_{{$feature->id}}" class="control-label @if( $feature->type != 'checkbox') tp @endif">{{$feature->name}}</label>	
						@if( $feature->type == 'select')
							<select class="form-control" id="feature_{{$feature->id}}" name="features[{{$feature->id}}]">
								<option value="">[ None ]</option>
								@foreach ($feature->select_options as $oid => $oval)
									<option value="{{$oid}}" {{($feature->value == $oid) ? "selected":""}}>{{$oval}}</option>
								@endforeach
							</select>
						@elseif( $feature->type == 'checkbox')

							<div class="onoffswitch">

								<input type="hidden" name="features[{{$feature->id}}]" value="N" />
								<input type="checkbox" id="feature_{{$feature->id}}" name="features[{{$feature->id}}]" class="onoffswitch-checkbox" {{($feature->value == "Y") ? "checked":""}} value="Y" />
                                <label class="onoffswitch-label" for="feature_{{$feature->id}}">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>

							&nbsp; &nbsp; 
						@else
							<input class="form-control" id="feature_{{$feature->id}}" type="text" name="features[{{$feature->id}}]" value="{{$feature->value}}" />
						@endif
					</div>
				</div>
			@endforeach	
			</div>
		</div>
		@endforeach 
	</div>
</div>