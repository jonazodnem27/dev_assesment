


<div class="container" style="background:white ;margin-bottom: 20px; max-width: 500px; width: 100%;   box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);">

<br> 

<?php
if($this->session->userdata('error') != NULL){ ?>
 <div class="alert alert-danger alert-dismissable fade in">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong><?=$this->session->userdata('error');?>
  </div>
<?php $this->session->unset_userdata('error'); }?>

<div class="container" style="max-width: 470px;margin-bottom: 20px">
  <h1><?= ucfirst($this->session->userdata('user'));?> Task</h1>
  <form method="post" action="<?=base_url(). 'fuse/addtask';?>">
    <div class="input-group">
      <input type="text" name="addTask" required class="form-control" placeholder="Enter Task here">
      <div class="input-group-btn">
        <button class="btn btn-default" style="border: 0;border-left: 1px solid #cccccc;" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>
      </div>
    </div>
  </form>

<hr>


<?php 
  foreach ($tasks as $key => $value) { ?>
   <div class="input-group">
      <input type="text" readonly class="form-control" value="<?=$tasks[$key]->task;?>">
      <div class="input-group-btn">
        <a href="<?=base_url(). 'fuse/removeTask/'. $tasks[$key]->taskID;?>" class="btn btn-default" type="submit"><i class="fa fa-remove" aria-hidden="true"></i></a>
      </div>
    </div>
  <?php } ?>
 <br>
<a href= "<?=base_url(). 'fuse/logout';?>" class="btn btn-danger btn-block">Logout</a>

</div>

</div>

<script>

$(document).ready(function(){
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
});

$("#signupForm").validate();
$("#loginForm").validate();


</script>
