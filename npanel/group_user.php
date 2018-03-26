<?php
function loadUsers(){
  $user = new users;
  $users = $user->load();
  $out = '';
  foreach($users as $user){
    $out .= '<option value="'.$user['ID'].'">'.$user['FNAME'].' '.$user['LNAME'].'</option>';
  }
  return $out;
}
function loadGroups(){
  $user = new group;
  $users = $user->load();
  $out = '<option value="-1">نمونه بردار</option>';
  $out .= '<option value="-2">مدیریت</option>';
  foreach($users as $user){
    $out .= '<option value="'.$user['id'].'">'.$user['name'].'</option>';
  }
  return $out;
}
if(isset($_POST['mod'])){
  $mod = $_POST['mod'];
  if($mod=='add'){
    $user_id = $_POST['user_id'];
    $user_group_id = $_POST['user_group_id'];
    $group = new group_user;
    $group->add(array("user_id"=>$user_id,"user_group_id"=>$user_group_id));
  }else{
    $id = $_POST['id'];
    $group = new group_user($id);
    if($mod=='update'){
      $user_id = $_POST['user_id'];
      $user_group_id = $_POST['user_group_id'];
      $group->update(array("user_id"=>$user_id,"user_group_id"=>$user_group_id));
    }else if($mod=='delete'){
      $group->delete();
    }    
  }
}else{
  $group = new group_user;
}
$groups = $group->loadWithJoin();
// var_dump($groups);
$out = '';
$i = 1;
foreach($groups as $r){
  if($r['gname']==NULL){
    $r['gname'] = 'نمونه بردار';
    if($r['user_group_id']==-2){
      $r['gname'] = 'مدیریت';
    }
  }
  $out .= '<tr>';
  $out .= '<td>'.$i.'</td>';
  $out .= '<td>'.$r['fname'].' '.$r['lname'].'</td>';
  $out .= '<td>'.$r['gname'].'</td>';
  $out .= '     <td>
                  <a href="javascript:updateItem('.$r['id'].','.$r['user_id'].','.$r['user_group_id'].')">
                    <span class="tag tag-warning">اصلاح</span>
                  </a>
                  <a href="javascript:deleteItem('.$r['id'].')">
                    <span class="tag tag-danger">حذف</span>
                  </a>
                </td>';
  $out .= '</tr>';
  $i++;
}
?>
<main class="main">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">خانه</li>
    <li class="breadcrumb-item"><a href="#">مدیریت کاربر</a>
    </li>
    <li class="breadcrumb-menu">
        <!--<div class="btn-group" role="group" aria-label="Button group with nested dropdown">-->
        <!--    <a class="btn btn-secondary" href="#"><i class="icon-speech"></i></a>-->
        <!--    <a class="btn btn-secondary" href="./"><i class="icon-graph"></i> &nbsp;داشبرد</a>-->
        <!--    <a class="btn btn-secondary" href="#"><i class="icon-settings"></i> &nbsp;تنظیمات</a>-->
        <!--</div>-->
    </li>
  </ol>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12 col-lg-12 col-md-12">
        <a href="javascript:$('#addModal').modal();" class="btn btn-primary" style="margin:10px;">
          جدید
        </a>

        <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ردیف</th>
                <th>کاربر</th>
                <th>گروه</th>
                <th>امکانات</th>
              </tr>
            </thead>
            <tbody>
              <?php echo $out; ?>
            </tbody>
        </table>      
      </div>
    </div>
  </div>
</main>
<div class="modal fade show" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">اصلاح</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="بستن">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <p>اصلاح</p>
        <label for="euser">
          کاربر
        </label>
        <select id="euser" name="euser">
          <?php echo loadUsers(); ?>
        </select>
        <label for="egroup">
          گروه
        </label>
        <select id="egroup" name="egroup">
          <?php echo loadGroups(); ?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
        <button type="button" class="btn btn-primary" onclick="saveItem();">ذخیره</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade show" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">اضافه</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="بستن">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <p>اضافه</p>
        <label for="euser">
          کاربر
        </label>
        <select id="auser" name="auser">
          <?php echo loadUsers(); ?>
        </select>
        <label for="egroup">
          گروه
        </label>
        <select id="agroup" name="agroup">
          <?php echo loadGroups(); ?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
        <button type="button" class="btn btn-primary" onclick="newItem();">ذخیره</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<form id="data-form" method="post">
  <input type="hidden" name="mod" id="mod" value="update" />
  <input type="hidden" name="id" id="id" />
</form>
<script>
  function updateItem(id,user_id,user_group_id){
    $("#id").val(id);
    $("#euser option[value='"+user_id+"']").prop('selected',true);
    $("#egroup option[value='"+user_group_id+"']").prop('selected',true);
    $("#editModal").modal();
  }
  function saveItem(){
    var euser = $("#euser").val();
    var egroup = $("#egroup").val();
    $("#mod").val('update');
    $("#data-form").append('<input name="user_id" value="'+euser+'" />');
    $("#data-form").append('<input name="user_group_id" value="'+egroup+'" />');
    $("#data-form").submit();
  }
  function newItem(){
    var auser = $("#auser").val();
    var agroup = $("#agroup").val();
    $("#mod").val('add');
    $("#id").val('');
    $("#data-form").append('<input name="user_id" value="'+auser+'" />');
    $("#data-form").append('<input name="user_group_id" value="'+agroup+'" />');
    $("#data-form").submit();
  }
  function deleteItem(id){
    if(confirm('حذف انجام شود؟')){
      $("#mod").val('delete');
      $("#id").val(id);
      $("#data-form").submit();
    }
  }
</script>