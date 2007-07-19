$(document).ready(function() {

    // replacement for OnAllDayClick in MRBS    
    $("#all-day-checkbox").click(function(){
        form = $("#main-form");
        allday = $("#all-day-checkbox");
        
        if (allday.attr("checked")) // If checking the box...
        {
           if (!enablePeriods) {
               $("#main-hour").val("00");
               $("#main-minute").vale("00")
           }
           if ($("#main-dur-units").val() != "days") { // Don't change it if the user already did.
             $("#main-duration").val("1");
             $("#main-dur-units").val("days");
           }
       }
    });
    
}); // #^ $(document).ready()

// do a little form verifying
function validate_and_submit ()
{
  // null strings and spaces only strings not allowed
  if(/(^$)|(^\s+$)/.test($("#main-name").val())) {
      $("#main-name").css('background-color', 'red');
      alert(you_have_not_entered + "\n" + brief_description);
      return false;
  }
  
  if (!enablePeriods) {
      var h = parseInt($("#main-hour").val());
      var m = parseInt($("#main-minute").val());
      
      if (h > 23 || m > 59) {
          $("#main-hour").css("background-color", 'red');
          $("#main-minute").css("background-color", 'red');
          alert(you_have_not_entered + "\n" + valid_time_of_day);
	      return false;
      }
  }
  
  var i1 = 0;
  // check form element exist before trying to access it
  if($("#main-id")) {
    i1 = parseInt($("#main-id").val());  
  }

  i2 = parseInt($("#main-rep-id").val());
  var n = 0;
  if ($("#main-rep-num-weeks")) {
  	n = parseInt($("#main-rep-num-weeks").val());
  }

  // old comment kept as a example for insecurity 
  // START MRBS COMMENT
  // check that a room(s) has been selected
  // this is needed as edit_entry_handler does not check that a room(s)
  // has been chosen
  // END MRBS COMMENT
  //
  // the above bug is fixed in Schoorbs
  if( $('#main-rooms').selectedIndex == -1 ) {
    alert(you_have_not_entered + "\n" + valid_room);
    return false;
  }
  
  // Form submit can take some times, especially if mails are enabled and
  // there are more than one recipient. To avoid users doing weird things
  // like clicking more than one time on submit button, we hide it as soon
  // it is clicked.
  $("#main-save-button").disabled = "true";

  // would be nice to also check date to not allow Feb 31, etc...
  // we need to use the raw submit method instead of jQuery's since it just triggers a event
  $("#main-form")[0].submit();
  
    return true;
}