<?php
include_once('class/group.php');
if(isset($_POST['mod'])){
  $mod = $_POST['mod'];
  if($mod=='add'){
    $name = $_POST['name'];
    $group = new group;
    $group->add(array("name"=>$name));
  }else{
    $id = $_POST['id'];
    $group = new group($id);
    if($mod=='update'){
      $name = $_POST['name'];
      $group->update(array("name"=>$name));
    }else if($mod=='delete'){
      $group->delete();
    }    
  }
}else{
  $group = new group;
}
$groups = $group->load();
$out = '';
$i = 1;
foreach($groups as $r){
  $out .= '<tr>';
  $out .= '<td>'.$i.'</td>';
  $out .= '<td>'.$r['name'].'</td>';
  $out .= '     <td>
                  <a href="javascript:updateItem('.$r['id'].',\''.$r['name'].'\')">
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
    <li class="breadcrumb-item"><a href="#">مدیریت گروه</a>
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
                    <th>نام</th>
                    <th>امکانات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>مدیریت</td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>نمونه بردار</td>
                    <td>
                    </td>
                </tr>
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
        <p>اصلاح گروه</p>
        <label for="name">
          نام گروه
        </label>
        <input name="name" id="ename" placeholder="نام گروه" />
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
        <h4 class="modal-title">جدید</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="بستن">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <p>اضافه کردن گروه</p>
        <label for="name">
          نام گروه
        </label>
        <input name="name" id="aname" placeholder="نام گروه" />
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
  function updateItem(id,name){
    $("#id").val(id);
    $("#ename").val(name);
    $("#editModal").modal();
  }
  function saveItem(){
    var name = $("#ename").val().trim();
    $("#mod").val('update');
    $("#data-form").append('<input name="name" value="'+name+'" />');
    $("#data-form").submit();
  }
  function newItem(){
    var name = $("#aname").val().trim();
    $("#mod").val('add');
    $("#id").val('');
    $("#data-form").append('<input name="name" value="'+name+'" />');
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