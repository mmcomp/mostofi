var watchID;
// onSuccess Callback
//   This method accepts a `Position` object, which contains
//   the current GPS coordinates
//
function onGPSSuccess(position) {
  // alert('1');
    // var element = document.getElementById('geolocation');
    /*
        alert('Latitude: '          + position.coords.latitude          + '\n' +
              'Longitude: '         + position.coords.longitude         + '\n' +
              'Altitude: '          + position.coords.altitude          + '\n' +
              'Accuracy: '          + position.coords.accuracy          + '\n' +
              'Altitude Accuracy: ' + position.coords.altitudeAccuracy  + '\n' +
              'Heading: '           + position.coords.heading           + '\n' +
              'Speed: '             + position.coords.speed             + '\n' +
              'Timestamp: '         + position.timestamp                + '\n');
    */
/*
element.innerHTML = 'Latitude: '  + position.coords.latitude      + '<br />' +
                        'Longitude: ' + position.coords.longitude     + '<br />' +
                        'Altitude: '  + position.coords.altitude      + '<br />' +
                        'Accuracy: '  + position.coords.accuracy      + '<br />' +
                        'Altitude Accuracy: '  + position.coords.altitudeAccuracy      + '<br />' +
                        'Heading: '  + position.coords.heading      + '<br />' +
                        'Speed: '  + position.coords.speed      + '<br />' +
                        'Timestamp: '  + position.coords.timestamp      + '<br />' +
                        '<hr />'      + element.innerHTML;
*/
  // alert('2');
// element.innerHTML = 'Latitude: '  + position.coords.latitude      + '<br />' +
//                         'Longitude: ' + position.coords.longitude     + '<br />' +
//                         'Altitude: '  + position.coords.altitude      + '<br />' +
//                         'Heading: '  + position.coords.heading      + '<br />' +
//                         'Speed: '  + position.coords.speed      + '<br />' ;
//   // alert('3');
addPoint(position);
}

// onError Callback receives a PositionError object
//
function onGPSError(error) {
  if(error.code===1){
    ons.notification.alert('لطفا سرویس مکانی را فعال کنید',{title:'باچی'});
  }else{
    console.log('code: '    + error.code    + '\n' +
          'message: ' + error.message + '\n');
  }
}

// Options: throw an error if no update is received every 30 seconds.
//
var callbackFn = function(location) {
  alert('[js] BackgroundGeoLocation callback:  ' + location.latitude + ',' + location.longitude);
  bgGeo.finish();
}
var failureFn = function(error) {
  alert('BackgroundGeoLocation error');
}
function getBackgroundLocation(){
  navigator.geolocation.getCurrentPosition(onGPSSuccess, onGPSError, { maximumAge: 3000, timeout: 5000, enableHighAccuracy: true });
  alert(bgGeo);

  $("#start").prop('disabled',true);
  $("#stop").prop('disabled',false);
}
function stopBackground(){

  alert('BG stoped');
  $("#start").prop('disabled',false);
  $("#stop").prop('disabled',true);
}
function getCurrentLocation(){
  // alert('a1');
  navigator.geolocation.getCurrentPosition(function(p){
    // alert('Loc ok');
    onGPSSuccess(p);
  }, function(e){
    // alert('Loc error');
  }, { maximumAge: 3000, timeout: 5000, enableHighAccuracy: true });
  // alert('a2');
  if(watchID){
    // alert('a3');
    
    // console.log('already started',watchID);
  }else{
    // alert('started ..');
    // alert('a4');
    // watchID = navigator.geolocation.watchPosition(onGPSSuccess, onGPSError, { maximumAge: 3000, timeout: 5000, enableHighAccuracy: true });//navigator.geolocation.getCurrentPosition(onGpsSuccess, onGpsError, { timeout: 30000 });  
    
    watchID = setInterval(function(){
      getCurrentLocation();
    },1000);
    
    // console.log('start',watchID); 
    // alert(watchID);
    $("#start").prop('disabled',true);
    $("#stop").prop('disabled',false);
  }
}

function stopLocation(){
  if(watchID){
    console.log('stoping',watchID);
    navigator.geolocation.clearWatch(watchID);
    // clearInterval(watchID);
    console.log('stoped',watchID);
    $("#start").prop('disabled',false);
    $("#stop").prop('disabled',true);
  }else{
    console.log('Nothing to stop');
  }
}
function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c * 1000; // Distance in meters
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}

function findGaps(inp){
  var out = [];
  var d1,d2,d3;
  if(inp.length<3){
    return inp;
  }
  var i = 0;
  var contin = true;
  while(contin){
    contin = (typeof inp[i]!=='undefined' && typeof inp[i+1]!=='undefined' && typeof inp[i+2]!=='undefined');
    if(contin){
      d1 = getDistanceFromLatLonInKm(inp[i+1].lat,inp[i+1].lon,inp[i].lat,inp[i].lon);
      d2 = getDistanceFromLatLonInKm(inp[i+2].lat,inp[i+2].lon,inp[i+1].lat,inp[i+1].lon);
      d3 = getDistanceFromLatLonInKm(inp[i+2].lat,inp[i+2].lon,inp[i].lat,inp[i].lon);
      if(d1>50 && d2>50 && d3<50){
        out.push(inp[i]);
        out.push(inp[i+2]);
      }else{
        out.push(inp[i]);
        out.push(inp[i+1]);
        out.push(inp[i+2]);
      }
      i+=3;
    }else{
      for(var j =i;j<inp.length;j++){
        out.push(inp[j]);
      }
    }
  }
  return out;
}