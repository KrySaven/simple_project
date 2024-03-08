var myDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
var today = new Date();
var day = String(today.getDate()).padStart(2, '0');
var month = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var year = today.getFullYear();
var thisDay = 4,
thisDay = myDays[thisDay];;
var hour=today.getHours();
var minu=today.getMinutes();
var seco=today.getSeconds();
function getSeverTime() {
   var a_p = "";
   seco++;
   if (seco == 60) {
      minu += 1;
      seco = 0;
   }
   if (minu == 60) {
      hour += 1;
      minu = 0;
   }
   if (hour == 24) {
      hour = 0;
   }
   if (hour < 12) {
      a_p = "AM";
   } else {
      a_p = "PM";
   }
   var sseco = addZero(seco);
   var sminu = addZero(minu);
   var shour = addZero(hour);
   var tt = document.getElementById('clock').innerHTML = "<span class='clock' style='margin-top:-2px;'></span>" + day + "/" + month + "/" + year + " " + shour + ":" + sminu + ":" + sseco;
   //setTimeout("getSeverTime()",1000);
}

function addZero(num) {
   num = Math.floor(num);
   return ((num <= 9) ? ("0" + num) : num);
}
//new getSeverTime();

$(document).ready(function () {
   if (document.getElementById("clock")) {
      setInterval(getSeverTime, 1000);
   }
});          