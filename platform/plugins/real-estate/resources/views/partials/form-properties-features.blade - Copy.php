<div>
-----------------------	
<form method="POST" class="form-horizontal well" role="form">
    <div class="repeater-default">
		<div data-repeater-list="features">
			@foreach ($features as $feature)
				<div data-repeater-item="" class="data-repeater-item">
					<div class="form-group">
						<div class="row">
							<div class="col-md-4 col-sm-5">
								<select name="id" class="properties-features-select form-control">
									@if(!empty($feature_categories))
										@foreach ($feature_categories as $value)
        										
											<option data-select="{{$value->select_options}}" data-type="{{$value->type}}" {{ ($feature->name ===   $value->name ) ? "selected" : "" }} value="{{$value->id}}">{{$value->name}}</option>
										@endforeach 
									@endif
								</select>
							</div> 
							<div class='col-md-4 col-sm-5 pf-text-wrap  {{ $feature->type === "text" ? "" : "hide"}}'>
								<input class="form-control properties-features-input-text" type="text" value="{{$feature->value}}" name="features" value="">
							</div>
							<div class="col-md-4 col-sm-5 pf-checkbox-wrap {{ $feature->type === 'checkbox' ? '' : 'hide'}}">
								<div class="widget-body">
									<label class="checkbox-inline">
										<input name="features" type="checkbox" {{ ($feature->value === 'Yes' ) ? "checked" : "" }}  value="Yes">Yes
									</label>&nbsp;&nbsp; 
									<label class="checkbox-inline">
										<input name="features" type="checkbox" {{ ($feature->value === 'No') ? "checked" : "" }} value="No">No
									</label>
								</div>
							</div> 
							<div class="col-md-4 col-sm-5 pf-select-wrap {{ $feature->type === 'select' ? '' : 'hide' }}">
								<select name="features" class="form-control"> 
									<?php 
										$select_options = $feature->select_options;
										$select_options = explode(',',$select_options);
										foreach ($select_options as $key => $value) {
									?>
										<option value="{{$value}}">{{$value}}</option>
									<?php
										} 
									?>
								</select>
							</div>
						<div class="col-sm-4">
							<button data-repeater-delete="" class="btn btn-warning" type="button" ><i class="fa fa-times"></i></button>
						</div>
						</div>
					</div>
				</div>
			@endforeach 
			@if (empty($features))
			<div data-repeater-item="" class="data-repeater-item">
				<div class="form-group">
					<div class="row">
						<div class="col-md-4 col-sm-5">
							<select name="id" class="properties-features-select form-control"></select>
						</div>
					<div class="col-md-4 col-sm-5 pf-text-wrap hide">
						<input class="form-control properties-features-input-text" type="text" name="features" value="">
					</div>
						<div class="col-md-4 col-sm-5 pf-checkbox-wrap hide">
							<div class="widget-body">
								<label class="checkbox-inline">
									<input name="features" type="checkbox" value="Yes">Yes
								</label>&nbsp;
								<label class="checkbox-inline">
									<input name="features" type="checkbox" value="No">No
								</label>&nbsp;
							</div>
						</div> 
					<div class="col-md-4 col-sm-5 pf-select-wrap  hide">
						<select name="features" class="form-control"> 
						</select>
					</div>
					 
					<div class="col-sm-4">
						<button data-repeater-delete="" class="btn btn-warning" type="button" ><i class="fa fa-times"></i></button>
					</div>
					</div>
				</div>
			</div>
			@endif 
		</div>
		<div class="form-group">
			<span data-repeater-create="" class="btn btn-info add_new_fields ">
				<span class="glyphicon glyphicon-plus"></span>Add New
			</span>
		</div> 
    </div> 
</form>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<script type="text/javascript">
	var repeater = $('.repeater-default').repeater({
		initval: 1,
	}); 
</script>
<style type="text/css">
	.hide{
		display: none;
	}
</style>