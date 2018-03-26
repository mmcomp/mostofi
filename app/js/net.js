function sendReg(fname,lname,gender,email,cell,address,work1,username,password,age,job,car,benzin,work2,school1,school2,shop1,shop2,use_car,sarparast,vasile,tedad_khanevade,fn){
  email = ($.trim(email)==='')?'-':$.trim(email);
  cell = ($.trim(cell)==='')?'-':$.trim(cell);
  address = ($.trim(address)==='')?'-':$.trim(address);
  work1 = ($.trim(work1)==='')?'-':$.trim(work1);
  work2 = ($.trim(work2)==='')?'-':$.trim(work2);
  school1 = ($.trim(school1)==='')?'-':$.trim(school1);
  school2 = ($.trim(school2)==='')?'-':$.trim(school2);
  shop1 = ($.trim(shop1)==='')?'-':$.trim(shop1);
  shop2 = ($.trim(shop2)==='')?'-':$.trim(shop2);
  fname = ($.trim(fname)==='')?'-':$.trim(fname);
  var ur = server_endpoint+"user/" + fname + "/" + lname + "/" + gender + "/" + email + "/" + cell + "/" + address +"/" + work1 +"/" + username + "/" + password + "/";
  ur += age + "/" + job + "/" + car + "/" + benzin + "/" + work2 + "/" + school1 + "/" + school2 + "/" + shop1 + "/" + shop2 + "/" + use_car + "/" + sarparast + "/" + vasile + "/" + tedad_khanevade;
  // alert(ur);
  $.ajax({
      url: ur ,
      type: 'PUT',
      success: function (res) {
        // alert(res);
        alert('ثبت با موفقیت انجام شد');
          console.log('sent');
          if(fn){
            fn(true);
          }
      },
      error: function () {
          ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
          if(fn){
            fn(false);
          }
      }
  });
}
function getEmtiaz(fn){
  // alert(server_endpoint+"login/"  + username + "/" + password);
  $.ajax({
      url: server_endpoint+"emtiaz/"  + user_id ,
      type: 'GET',
      success: function (res) {
          // alert(res);
          res = JSON.parse(res);
          // alert(res.items[0].id+' '+res.items[0].fname+' '+res.items[0].lname);
          if(fn){
            fn(res);
          }
      },
      error: function () {
          ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
          if(fn){
            fn(false);
          }
      }
  });
}
function addEmtiaz(emtiaz,fn){
  // alert(server_endpoint+"login/"  + username + "/" + password);
  $.ajax({
      url: server_endpoint+"emtiaz/"  + user_id + "/" + emtiaz,
      type: 'PUT',
      success: function (res) {
          // alert(res);
          res = JSON.parse(res);
          // alert(res.items[0].id+' '+res.items[0].fname+' '+res.items[0].lname);
          if(fn){
            fn(res);
          }
      },
      error: function () {
          ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
          if(fn){
            fn(false);
          }
      }
  });
}
function sendAuth(username,password,fn){
  // alert(server_endpoint+"login/"  + username + "/" + password);
  $.ajax({
      url: server_endpoint+"login/"  + username + "/" + password ,
      type: 'GET',
      success: function (res) {
          // alert(res);
          res = JSON.parse(res);
          // alert(res.items[0].id+' '+res.items[0].fname+' '+res.items[0].lname);
          if(fn){
            fn(res);
          }
      },
      error: function () {
          ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
          if(fn){
            fn(false);
          }
      }
  });
}
function sendPoint(){
  // alert('sending to server');
  // alert(navigator.connection.type!=Connection.UNKNOWN && navigator.connection.type!=Connection.NONE);
  // if(navigator.connection.type!=Connection.UNKNOWN && navigator.connection.type!=Connection.NONE){
    getLocation(function(res){
      // alert('getting cache');
      if(res.rows.length>0){
        // alert('cache size : '+res.rows.length);
        var speed = res.rows.item(0).speed;
        if(speed===null){
          speed = 0;
        }
        var heading = res.rows.item(0).headi;
        if(heading === null){
          heading = 0;
        }
        var aurl = server_endpoint+"track/" + user_id + "/" + res.rows.item(0).lon + "/" + res.rows.item(0).lat + "/" + speed +"/" + res.rows.item(0).alti + "/" + heading;
        // alert('id= '+res.rows.item(0).ID);
        try{
          $.ajax({
              url: aurl,
              type: 'PUT',
              success: function (res1) {
                  // alert('sent '+res.rows.item(0).ID);
                  delLocation(res.rows.item(0).ID);
              },
              error: function () {
                // alert('send error');
                // alert('sendnavigator.connection.type!=Connection.UNKNOWN && navigator.connection.type!=Connection.NONE error');
                // console.log('add location');
                // addLocation(position);
                // ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
              }
          });
        }catch(e){
          // alert(e.message);
        }
      }
    });
  // }
  setTimeout(function(){
    sendPoint();
  },500);
}
function addPoint(position){
  // alert('add point');
  addLocation(position);
  /*
  getLocation(function(res){
    if(res.rows.length==0){
      var speed = position.coords.speed;
      if(speed===null){
        speed = 0;
      }
      var heading = position.coords.heading;
      if(heading === null){
        heading = 0;
      }
      $.ajax({
          url: server_endpoint+"track/" + user_id + "/" + position.coords.longitude + "/" + position.coords.latitude + "/" + speed +"/" + position.coords.altitude + "/" + heading,
          type: 'PUT',
          success: function (res) {
              console.log('sent');
          },
          error: function () {
            console.log('add location');
            addLocation(position);
            // ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
          }
      });
    }else{
      addLocation(position);
      console.log('read from stack '+res.rows.item(0).ID);
      // for(var ind in res.rows.item(0)){
      //   alert(ind+'->'+res.rows.item(0)[ind]);
      // }
      var aurl = server_endpoint+"track/" + user_id + "/" + res.rows.item(0).lon + "/" + res.rows.item(0).lat + "/" + res.rows.item(0).speed +"/" + res.rows.item(0).alti + "/" + res.rows.item(0).headi;
      console.log(aurl);
      $.ajax({
          url: aurl,
          type: 'PUT',
          success: function (res) {
              // console.log('sent');
              console.log('del '+res.rows.item(0).ID);
              delLocation(res.rows.item(0).ID);
          },
          error: function () {
            // addLocation(position);
            alert('error sending');
            // ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
          }
      });
    }
  });
  */
}
var current_page = 0;
var items = [];
var dates = [];
var stops = [];
function getDatesAll(user_id,fn){
  // alert('start');
  dates = [];
  var page_url = (current_page>0)?("?page="+current_page):"";
  // var durl = server_endpoint+"track/"+user_id+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
  var durl = server_endpoint+"trackdates/"+user_id+'/all';//+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
  // alert(durl);
  $(".loading").show();
  $.ajax({
      url : durl,
      type: 'GET',
      success: function (res) {
        // alert(res);
        res = JSON.parse(res);
        for(var i = 0;i < res.items.length;i++){
          dates.push(res.items[i]);
        }
        $(".loading").hide();
        if(typeof fn === 'function')
          fn(dates);
      },
      error: function () {
        $(".loading").hide();
        ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
      }
  });
  
}
function getDates(user_id,fn){
  // alert('start');
  dates = [];
  var page_url = (current_page>0)?("?page="+current_page):"";
  // var durl = server_endpoint+"track/"+user_id+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
  var durl = server_endpoint+"trackdates/"+user_id;//+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
  // alert(durl);
  $(".loading").show();
  $.ajax({
      url : durl,
      type: 'GET',
      success: function (res) {
        // alert(res);
        res = JSON.parse(res);
        // for(var i in res){
        //   alert(i);
        // }
          // alert(res.items[0].dt+'|'+res.items[0].pdt);
          for(var i = 0;i < res.items.length;i++){
            dates.push(res.items[i]);
          }
          $(".loading").hide();
          if(typeof fn === 'function')
            fn(dates);
      },
      error: function () {
        $(".loading").hide();
        ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
      }
  });
  
}
function getStops(user_id,dt,fn){
  // alert('start');
  
  var page_url = (current_page>0)?("?page="+current_page):"";
  // var durl = server_endpoint+"track/"+user_id+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
  var durl = server_endpoint+"stops/"+user_id+"/"+dt;//+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
  // alert(durl);
  stops = [];
  $.ajax({
      url : durl,
      type: 'GET',
      success: function (res) {
        // alert(res);
        res = JSON.parse(res);
          for(var i = 0;i < res.items.length;i++){
            stops.push(res.items[i]);
          }
          if(res.next){
            current_page++;
            getStops(user_id, fn);
          }else{
            current_page = 0;
            if(typeof fn === 'function')
              fn(stops);
          }
      },
      error: function () {
        $(".loading").hide();
        ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
      }
  });
  
}
function getPath(user_id,dt,fn){
  // alert('start');
  
  var page_url = (current_page>0)?("?page="+current_page):"";
  // var durl = server_endpoint+"track/"+user_id+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
  var durl = server_endpoint+"newtrack/"+user_id+"/"+dt;//+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
  // alert(durl);
  
  $.ajax({
      url : durl,
      type: 'GET',
      success: function (res) {
        // alert(res);
        res = JSON.parse(res);
          for(var i = 0;i < res.items.length;i++){
            items.push(res.items[i]);
          }
          if(res.next){
            current_page++;
            getPath(user_id, fn);
          }else{
            current_page = 0;
            var data = items;
            items = [];
            if(typeof fn === 'function')
              fn(data);
          }
      },
      error: function () {
        $(".loading").hide();
        ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
      }
  });
  
}
var stops_indx = 0;
function sendMode(/*user_id,start_id,end_id,mode,answer,rahati,mobile_app,*/fn){
  // alert('start');
  if(stops_indx<travel_modes.length){
    var i = stops_indx;
    var start_id = travel_modes[i].start_id;
    var end_id = travel_modes[i].end_id;
    var mode = travel_modes[i].umode;
    var answer = travel_modes[i].answer;
    var rahati = travel_modes[i].rahati;
    var mobile_app = travel_modes[i].mobile_app;
    var page_url = (current_page>0)?("?page="+current_page):"";
    // var durl = server_endpoint+"track/"+user_id+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
    var durl = server_endpoint+"mode/"+user_id+"/"+start_id+'/'+end_id+'/'+mode+'/'+answer+'/'+rahati+'/'+mobile_app;//+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
    // alert(durl);
    
    $.ajax({
        url : durl,
        type: 'PUT',
        success: function (res) {
          // alert(res);
          stops_indx++;
          sendMode(fn);
        },
        error: function () {
          $(".loading").hide();
          ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
        }
    });
  }else{
    if(typeof fn == 'function'){
      fn();
    }
  }
  
}

function sendStop(/*id,tgoal,*/fn){
  // alert('start');
  if(stops_indx<stops.length){
    var id = stops[stops_indx].id;
    var tgoal = stops[stops_indx].tgoal;
    var page_url = (current_page>0)?("?page="+current_page):"";
    // var durl = server_endpoint+"track/"+user_id+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
    var durl = server_endpoint+"stops/"+id+"/"+tgoal;//+"/50.145879/36.547855/4.14534354354/0/0"+page_url;
    // alert(durl);
    $.ajax({
        url : durl,
        type: 'PUT',
        success: function (res) {
          // alert(res);
          stops_indx++;
          sendStop(fn);
          $(".loading").hide();
        },
        error: function () {
          $(".loading").hide();
          ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
        }
    });
  }else{
    stops_indx = 0;
    if(typeof fn=='function'){
      fn();
    }
  }
}
function sendBGTest(location){
  $.ajax({
      url: server_endpoint+"reqdump/1/"+JSON.stringify(location),
      type: 'GET',
      success: function (res) {
          console.log('sent');
      },
      error: function () {
          ons.notification.alert('خطای دسترسی<br/>اتصال اینترنت و شبکه را بررسی کنید' ,{title:'باچی'});
      }
  });
}