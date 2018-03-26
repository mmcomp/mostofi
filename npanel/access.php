<?php
include_once('class/access_key.php');
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
  $out = '';
  foreach($users as $user){
    $out .= '<option value="'.$user['id'].'">'.$user['name'].'</option>';
  }
  return $out;
}
if(isset($_POST['mod'])){
  $mod = $_POST['mod'];
  if($mod=='add'){
    $access_key = $_POST['access_key'];
    $user_group_id = $_POST['user_group_id'];
    $group = new group_access;
    $group->add(array("access_key"=>$access_key,"user_group_id"=>$user_group_id));
  }else{
    $id = $_POST['id'];
    $group = new group_access($id);
    if($mod=='update'){
      $access_key = $_POST['access_key'];
      $user_group_id = $_POST['user_group_id'];
      $group->update(array("access_key"=>$access_key,"user_group_id"=>$user_group_id));
    }else if($mod=='delete'){
      $group->delete();
    }    
  }
}else{
  $group = new group_access;
}
$groups = $group->loadWithJoin();
// var_dump($groups);
$access = new access_key;
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
  $out .= '<td>'.$r['gname'].'</td>';
  $out .= '<td>'.$access->decode($r['access_key']).'</td>';
  $out .= '     <td>
                  <a href="javascript:updateItem('.$r['id'].','.$r['user_group_id'].',\''.$r['access_key'].'\')">
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
    <li class="breadcrumb-item"><a href="#">مدیریت دسترسی</a>
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
                <th>گروه</th>
                <th>دسترسی</th>
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
          گروه
        </label>
        <select id="egroup" name="egroup">
          <?php echo loadGroups(); ?>
        </select>
        <label for="egroup">
          دسترسی
        </label>
        <select id="eaccess" name="eaccess">
          <?php echo $access->encode(); ?>
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
          گروه
        </label>
        <select id="agroup" name="agroup">
          <?php echo loadGroups(); ?>
        </select>
        <label for="egroup">
          دسترسی
        </label>
        <select id="aaccess" name="aaccess">
          <?php echo $access->encode(); ?>
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
  function updateItem(id,user_group_id,access_key){
    $("#id").val(id);
    $("#eaccess option[value='"+access_key+"']").prop('selected',true);
    $("#egroup option[value='"+user_group_id+"']").prop('selected',true);
    $("#editModal").modal();
  }
  function saveItem(){
    var eaccess = $("#eaccess").val();
    var egroup = $("#egroup").val();
    $("#mod").val('update');
    $("#data-form").append('<input name="access_key" value="'+eaccess+'" />');
    $("#data-form").append('<input name="user_group_id" value="'+egroup+'" />');
    $("#data-form").submit();
  }
  function newItem(){
    var aaccess = $("#aaccess").val();
    var agroup = $("#agroup").val();
    $("#mod").val('add');
    $("#id").val('');
    $("#data-form").append('<input name="access_key" value="'+aaccess+'" />');
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