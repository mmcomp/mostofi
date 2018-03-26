// This is a JavaScript file
var dbSize = 5 * 1024 * 1024; // 5MB
var db;
function createDB(){
  db = window.openDatabase("test", "1.0", "Test DB", dbSize);
  db.transaction(function(tx){
    populateDB(tx);
  }, errorCB, successCB);
}
function populateDB(tx) {
  // alert('pop');
  // tx.executeSql('DROP TABLE IF EXISTS conf');
  tx.executeSql('CREATE TABLE IF NOT EXISTS conf(ID INTEGER PRIMARY KEY ASC, key TEXT, value TEXT)');
  tx.executeSql('CREATE TABLE IF NOT EXISTS gps_loc(ID INTEGER PRIMARY KEY ASC, lon TEXT, lat TEXT,speed TEXT,alti TEXT,headi TEXT)');
     // tx.executeSql('INSERT INTO DEMO (id, data) VALUES (1, "First row")');
  // tx.executeSql('INSERT INTO conf (key, value) VALUES ("user_id", "10")');
  // tx.executeSql("delete from conf");
  tx.executeSql("select * from gps_loc ",[],
    function(tx,results){
      // alert('location length = '+results.rows.length);
    });
  tx.executeSql("select * from conf where key = 'user_id'",[],
    function(tx,results){
      // alert(results.rows.length);
      // console.log(results.rows.length);
      if(results.rows.length===1){
        user_id = results.rows.item(0).value;
      }else{
        user_id = -1;
      }
      getUserId(user_id);
    },
    function(){
      user_id = -2;
      getUserId(user_id);
    }
  );
}
function addLocation(position){
  // alert('adding');
  db.transaction(function(tx){
    tx.executeSql("insert into gps_loc (lon,lat,speed,alti,headi) values ('"+position.coords.longitude+"','"+position.coords.latitude+"','"+position.coords.speed+"','"+position.coords.altitude+"','"+position.coords.heading+"')");
  }, errorCB, successCB);
}
function getLocation(fn){
  db.transaction(function(tx){
    tx.executeSql("select * from gps_loc order by id limit 1",[],function(tx,results){
      if(typeof fn == 'function'){
        fn(results);
      }
    });
  }, errorCB, successCB);
}
function delLocation(id){
  db.transaction(function(tx){
    tx.executeSql("delete from gps_loc where ID = "+id);
    // alert("delete from gps_loc where ID = "+id);
  }, errorCB, successCB)
}
function logout(){
  db.transaction(function(tx){
    tx.executeSql("delete from conf where key = 'user_id'");
  }, errorCB, successCB);
}
function setConf(key,value){
  db.transaction(function(tx){
    tx.executeSql("select * from conf where key = '"+key+"'",[],function(tx,results){
      if(results.rows.length===1){
        tx.executeSql("update conf set value = '"+value+"' where id = "+results.rows.item(0).id);
      }else{
        tx.executeSql("insert into conf (key,value) values ('"+key+"','"+value+"')");
      }
    },errorCB);
  },errorCB, successCB);
}
function errorCB(err) {
    console.log("Error processing SQL: "+err.code);
}

function successCB() {
    // console.log("success!");
}
var register_state = false;
function register(){
  if(register_state){
    var password = $("#password").val();
    var repassword = $("#repassword").val();
    var username = $("#username").val();
    if(username==''){
      ons.notification.alert('نام کاربری نباید خالی باشد',{title:'باچی'});
    }else if(password==''){
      ons.notification.alert('رمز عبور خالی نباید باشد',{title:'باچی'});
    }else if(password!=repassword){
      ons.notification.alert('رمز عبور و تکرارش یکی نمی باشند',{title:'باچی'});
    }else{  
      
      // $(".reg").hide()
      var gender = $("#gender-1").prop('checked')?'m':'f';
      var fname = $("#fname").val();
      var lname = $("#lname").val();
      // alert('a');
      var age = $("#age").val();
      var job = $("#job").val();
      var car = $("#car").val();
      var benzin = $("#benzin").val();
      var cell = $("#cell").val();
      var email = $("#email").val();
      var address = $("#address").val();
      var work1 = $("#work1").val();
      var work2 = $("#work2").val();
      var school1 = $("#school1").val();
      var school2 = $("#school2").val();
      var shop1 = $("#shop1").val();
      var shop2 = $("#shop2").val();
      var use_car = $("#use_car").val();
      var sarparast = $("#sarparast").val();
      var vasile = $("#vasile").val();
      var tedad_khanevade = $("#tedad_khanevade").val();
      // alert('registering '+gender);
      if($.trim(lname)===''){
        ons.notification.alert('ورود نام خانوادگی الزامی است' ,{title:'باچی'});
      }else if($.trim(username)===''){
        ons.notification.alert('ورود نام کاربری الزامی است' ,{title:'باچی'});
      }else if($.trim(password)===''){
        ons.notification.alert('ورود رمز عبور الزامی است' ,{title:'باچی'});
      }else{
        $(".loading").show();
        sendReg(fname,lname,gender,email,cell,address,work1,username,password,age,job,car,benzin,work2,school1,school2,shop1,shop2,use_car,sarparast,vasile,tedad_khanevade,function(stat){
          $(".loading").hide();
          if(stat){
            $(".reg").hide();
            register_state = false;
          }
        });
      }
    }
  }else{
    register_state = true;
    $(".reg").show();
    $(".hid").hide();
  }
  
}
function login(){
  if(register_state){
    register_state = false;
    $(".reg").hide();
  }else{
    var password = $("#password").val();
    var username = $("#username").val();
    if(username=='' || password==''){
      ons.notification.alert('نام کاربری یا رمز عبور نمی بایست خالی باشند',{title:'باچی'});
    }else{
      $(".loading").show();
      sendAuth(username,password,function(res){
        $(".loading").hide();
        if(res!==false){
          user_id = res.items[0].id;
          setConf('user_id', user_id);
          ons.notification.alert(res.items[0].fname+' '+res.items[0].lname+' خوش آمدی ',{title:'باچی'});
          myTabbar.setActiveTab(0);
        }
      });
    }
  }
}
function getUserId(user_id){
  // alert(user_id);
  if(user_id<=0){
    myTabbar.setActiveTab(2);
  }else{
    myTabbar.setActiveTab(0);
  }
}