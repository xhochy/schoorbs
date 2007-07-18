// replacement for OnAllDayClick in MRBS
$(document).ready(function() {
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
    
});