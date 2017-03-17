


<div class="container" style="background:white ;margin-bottom: 20px; max-width: 500px; width: 100%;   box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);">

<br> 

<?php
if($this->session->userdata('error') != NULL){ ?>
 <div class="alert alert-danger alert-dismissable fade in">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong><?=$this->session->userdata('error');?>
  </div>
<?php $this->session->unset_userdata('error'); }?>

 <ul class="nav nav-tabs">
    <li class="active"><a href="#signup">Sign Up</a></li>
    <li><a href="#login">Log In</a></li>
  </ul>

  <div class="tab-content">
    <div id="signup" class="tab-pane fade in active" style="margin-bottom: 20px">
      <h2 style="text-align: center;padding: 35px">Sign Up for Free</h2>
      <div class="container" style="max-width: 470px;">
  <form id="signupForm" method="post" action="<?=base_url(). 'fuse/signin';?>">
  <div class="form-group row">
      <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" remote="check" name="inputUsername" id="inputUsername" minlength="8" required placeholder="Username">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-10">
        <input type="email" class="form-control" remote="check" name="inputEmail" id="inputEmail" required placeholder="Email">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name="inputPassword" minlength="8" id="inputPassword" required placeholder="Password">
      </div>
    </div>
   
        <button type="submit" class="btn btn-primary btn-block" style="padding: 10px;font-size: 15px;border-radius: 0;background: #0095da;border: 0;">Sign in <i class="fa fa-sign-in" aria-hidden="true"></i></button>

  </form>
</div>
    </div>
    <div id="login" class="tab-pane fade" style="margin-bottom: 20px">
         <h2 style="text-align: center;padding: 35px">Login Now!</h2>
      <div class="container" style="max-width: 470px;"><br>
  <form action="<?=base_url(). 'fuse/login';?>" method="post" id="loginForm">
  <div class="form-group row">
      <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name = "inputUsername2" id="inputUsername2" required placeholder="Username">
      </div>
    </div>

    <div class="form-group row">
      <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name = "inputPassword2" id="inputPassword2" required placeholder="Password">
      </div>
    </div>
   
        <button type="submit" class="btn btn-primary btn-block" style="padding: 10px;font-size: 15px;border-radius: 0;background: #0095da;border: 0;">Log in <i class="fa fa-sign-in" aria-hidden="true"></i>
</button>
     
  </form>
</div>
    </div>
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
