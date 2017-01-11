// set the date we're counting down to
var target_date = new Date('Jun, 20, 2016').getTime();
 
// variables for time units
var days, hours, minutes, seconds;
 
// get tag element
var countdown = document.getElementById('countdown');
 
// update the tag with id "countdown" every 1 second
setInterval(function () {
 
    // find the amount of "seconds" between now and target
    var current_date = new Date().getTime();
    var seconds_left = (target_date - current_date) / 1000;
 
    // do some time calculations
    days = parseInt(seconds_left / 86400);
    seconds_left = seconds_left % 86400;
     
    hours = parseInt(seconds_left / 3600);
    seconds_left = seconds_left % 3600;
     
    minutes = parseInt(seconds_left / 60);
    seconds = parseInt(seconds_left % 60);
     
    // format countdown string + set tag value
    countdown.innerHTML =
        '<div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-3"><span><span class="days" style="color:#b30000"><b>' + days +  
        ' </b></span>Days</span></div><div class="col-lg-2 col-md-2 col-sm-3"><span><span class="hours" style="color:#b30000"><b>' + hours + 
        ' </b></span>Hours</span></div><div class="col-lg-2 col-md-2 col-sm-3"><span><span class="minutes" style="color:#b30000"><b>' + minutes + 
        ' </b></span>Minutes</span></div><div class="col-lg-2 col-md-2 col-sm-3"><span><span class="seconds" style="color:#b30000"><b>' + seconds + 
        ' </b></span>Seconds</span></div>';  
 
}, 1000);