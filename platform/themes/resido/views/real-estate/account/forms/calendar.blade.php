@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
 
 

@section('content')
  <div class="property_block"> </div>  
    <div class="dashboard-wraper settings crop-avatar">
        <!-- Basic Information -->
        <div class="form-submit">
            <!-- Setting Title -->
            <div class="row">
                <div class="col-12">
                    <h4 class="with-actions">Edit {{ trans('calendar') }} for <a href="{{$property->url}}">{{$property->name}} </a></h4>

                </div>
                
                <div class="response"></div>
            </div> 
        </div> 
    </div>

    <div class="dashboard-wraper settings crop-avatar  mt-2">
        <!-- Basic Information -->
        <div class="form-submit">
            <!-- Setting Title -->
         
                           
                <form class="row" action="{{ route('public.account.properties.calendar_create',request()->id ) }}" id="setting-form" method="POST">
                    @csrf
                    <div class="   col-3 ">
                      <label for="start" class="">from date</label>
                      <div class="col-sm-10">
                        <input type="date" name="start" class="form-control" id="start" >
                      </div>
                    </div>
                    <div class="   col-3">
                      <label for="end" class="">to date</label>
                      <div class="col-sm-10">
                        <input type="date" name="end" class="form-control" id="end" placeholder="">
                      </div>
                    </div>

                    <div class="   col-3">
                      <label for="price" class="">price</label>
                      <div class="col-sm-10">
                        <input type="text" name="price"  class="form-control" id="price" placeholder="">
                      </div>
                    </div>

                    <div class="   col-3">
                      <label for="inputPassword" class="">available</label>
                      <div class="col-sm-10">
                      <div class="form-check">
                          <input class="form-check-input" type="radio" name="status" id="available" value="1" checked>
                          <label class="form-check-label" for="available">
                            available
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="status" id="unavailable" value="0">
                          <label class="form-check-label" for="unavailable">
                            unavailable
                          </label>
                        </div> 
                      </div>
                    </div>

                    <div class="   col-3"> 
                      <div class="col-sm-10"> 
                          <button type="submit" class="btn btn-primary">save</button>
                      </div>
                    </div>
                    
                </form>
             
        </div> 
    </div>

    <div class="dashboard-wraper settings mt-3 crop-avatar">
         

        <div class="form-submit">  
            <div class="row">
                <div class="col-lg-12 order-lg-0">
                   
                    <div id='calendar'></div> 
 
                    <!-- route('public.account.properties.calendar_create')  -->
                </div>
                <!-- <div class="col-lg-2 order-lg-12">
                     ss
                </div> -->
            </div>
        </div> 

    </div>






<!-- /. modal_danger_delete -->

<div class="modal fade" id="modal_danger_delete">
        <div class="modal-dialog">
          <div class="modal-content bg-danger">
            <div class="modal-header">
              <h4 class="modal-title">{{ __('delet model') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="modal_danger_delete_body">

              {{ __('Are you sure you want to delete this ?') }}

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">{{ __('Close') }}</button>
              <button type="button" id="btn_delete_confirm" class="btn btn-outline-light">{{ __('Save changes') }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


<!-- ./ modal_danger_delete -->

@endsection

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <!-- <script type="text/javascript" src="{{ asset('/vendor/core/plugins/real-estate/js/app.js')}}"></script>
    <script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js')}}"></script> -->
    
    <!-- calendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>

    <!-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.4/index.global.min.js'></script> -->
   
    <script type="text/javascript">
        "use strict";
      //   document.addEventListener('DOMContentLoaded', function() {
      //   var calendarEl = document.getElementById('calendar');
      //   var calendar = new FullCalendar.Calendar(calendarEl, {
      //     initialView: 'dayGridMonth'
      //     // initialView: 'resourceTimelineWeek'
      //     // initialView: 'dayGridMonth'
          
      //   });
      //   calendar.render();
      // });



//  --------------- delete -------------------  
	// $(document).on('click','#delete__1',function() {  
	// 	var id = $(this).data("id");   
	// 	var url_1 = $(this).data("url"); 
	// 	$('#modal_danger_delete').modal('show'); 

        
	//     $('#btn_delete_confirm').click(function(e) {   
	//         $('#modal_danger_delete').modal('hide');     
	// 		e.preventDefault();
 
	// 		$.ajax({
	// 			data: {data: id}, 
	// 			type: "get",
	// 			url: url_1+"/delete/"+id+"",
	// 			success: function(dataResult){   
	// 					var dataResult = JSON.parse(dataResult);
	// 					if(dataResult.status==1){ 
	// 						 toastr.success(dataResult.msg);	 			
	// 	                     $('#example2').load(''+url_1+'  #example2');					
	// 					}
	// 					else if(dataResult.status==0){  

	// 					 	toastr.error(dataResult.msg);	  
	// 	                     $('#example2').load(''+url_1+'  #example2');					
	// 					}
	// 			},
	// 	        error: function () {
	// 	            console.log('it failed!');
	// 	        }
	// 		});

	// 		//end 
	// 	});  


	// });






      // document.addEventListener('DOMContentLoaded', function() {
      //   var calendarEl = document.getElementById('calendar');

      //   var calendar = new FullCalendar.Calendar(calendarEl, {
      //     locale: '{{ Request::segment(1)}}',
      //     initialView: 'dayGridMonth',
      //     initialDate: '2023-02-07',
      //     // headerToolbar: {
      //     //   left: 'prev,next today',
      //     //   center: 'title',
      //     //   right: 'dayGridMonth,timeGridWeek,timeGridDay'
      //     // },
      //     // dateClick: function(info ) { 
      //     //   alert('Date: ' + info.dateStr);
      //     //   console.log(info); 
      //     //   //  alert('Resource ID: ' + info.resource.id);
      //     // }, 
      //     // navLinks: true, // can click day/week names to navigate views
          
      //     selectable: true,
      //     selectMirror: true,
      //     select: function(arg) {
                   
      //       if (confirm("Do you want the day is available?")) {
      //           calendar.addEvent({
      //             title: 'available',
      //             start: arg.start,
      //             end: arg.end,
      //             allDay: arg.allDay,
      //             color: 'green',
      //             // display: 'background'
      //            })
      //       } else {
      //         calendar.addEvent({
      //           title: 'Unavailable',
      //           start: arg.start,
      //           end: arg.end,
      //           allDay: arg.allDay,
      //           color: 'red',
      //           // display: 'background'
      //         })
      //       }
      //       calendar.unselect()
            
            
      //     },
      //     // select: function(arg) {
      //     //   var title =confirm("Do you want the day is available?");
      //     //   if (title) {
      //     //     calendar.addEvent({
      //     //       title: title,
      //     //       start: arg.start,
      //     //       end: arg.end,
      //     //       allDay: arg.allDay
      //     //     })
      //     //   }
      //     //   calendar.unselect()
      //     //   },
      //     eventClick: function(arg) {
      //       if (confirm('Are you sure you want to delete this event?')) {
      //         arg.event.remove()
      //       }
      //     },
      //     editable: true,
      //     dayMaxEvents: true, // allow "more" link when too many events

           
      //     // eventBackgroundColor: '#ABFFA6',
      //     //   eventColor: 'transparent',
      //     //   eventBorderColor: 'transparent',
      //     //   eventTextColor: '#000',
      //   });

      //   calendar.render();
      // });
      // 
      // <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
 
      // <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" />
        
      <script src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
        
      <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
      
    </script>




<script>
        $(document).ready(function () {
          var SITEURL = "{{url('/')}}";
          $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          var events = {!! json_encode($events) !!};
           
          var calendar = $('#calendar').fullCalendar({
              editable: true,
              events:  events,
              displayEventTime: false,
             
              // selectable: true,
              // selectMirror: true,

              eventRender: function (event, element, view) {
                 
                if (event.allDay === 'true') {
                  event.allDay = true;
                } else {
                  event.allDay = false;
                }
              },
              // selectable: true,
              selectHelper: true,


              select: function (start, end, allDay) {

                var price = prompt('Event price:');
                if (price) {
                  var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                  var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                  // 
                  $.ajax({
                    url: "{{ route('public.account.properties.calendar_create',request()->id ) }}",

                    data: 'price=' + price + '&start=' + start + '&end=' + end,
                    type: "POST",
                    success: function (data) {
                      // console.log(data) 
                      // console.log(data['data'].where[0].id) 
                      if(data['data']['data'] == 'erorr'){
                        $('#calendar').fullCalendar('removeEvents', data['data'].where[0].id);
                          calendar.fullCalendar('renderEvent',
                            {
                              id: data['data'].where[0].id,
                              title: price,
                              start: start,
                              end: end,
                              allDay: allDay
                            },
                            true
                            );
                      }else{
                        calendar.fullCalendar('renderEvent',
                            { 
                              title: price,
                              start: start,
                              end: end,
                              allDay: allDay
                            },
                            true
                            );
                      }
                     
                        displayMessage("Added Successfully");
                    }
                  });

                  
                }
                calendar.fullCalendar('unselect');
              },


              eventDrop: function (event) {

                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                // var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                 
                $.ajax({ 
                  url: "{{ route('public.account.properties.calendar_update',request()->id ) }}",
                  // data: '&title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                  data: '&title=' + event.title + '&start=' + start   + '&id=' + event.id,
                  type: "POST",
                  success: function (response) {
                    console.log(response)
                    displayMessage("Updated Successfully");
                  }
                });
              },

              
              
              eventClick: function (event) {
                var deleteMsg = confirm("Do you really want to delete?");
                if (deleteMsg) {
                  $.ajax({
                  type: "POST",
                  url: "{{ route('public.account.properties.calendar_delete',request()->id ) }}",
                  data: "&id_calecdar=" + event.id,
                  success: function (response) {
                    // console.log(response);
                    // console.log(response.data);
                    if(parseInt(response.data) > 0) {
                      $('#calendar').fullCalendar('removeEvents', event.id);
                      displayMessage("Deleted Successfully");
                    }
                  }
                });
                }
              }

          });
      });

      function displayMessage(message) {
        $(".response").html("<div class='success'>"+message+"</div>");
        setInterval(function() { $(".success").fadeOut(); }, 1000);
      }
</script>





@endpush 