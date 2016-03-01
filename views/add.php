<?php $this->layout('layout') ?>

<div class="page-header">
  <h1>Add a contact</h1>
</div>

<form method="post">
  <p>All fields are required.</p>
  <?php printError($errors, "global");?>

  <fieldset class="form-group">
    <label for="name">Name</label>
    <input required type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo getValue($values, 'name');?>">
    <?php printError($errors, "name");?>
  </fieldset>
  <fieldset class="form-group">
    <label for="email">Email address</label>
    <input required type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo getValue($values, 'email');?>">
    <?php printError($errors, "email");?>
  </fieldset>
  <input type="hidden" name="token" id="token" value="<?php echo $token;?>">
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php
function printError( $errors, $field ){
  if ( !isset($errors[$field]) ) return;

  echo '<span class="error">' . $errors[$field] . '</span>';
}

function getValue($values, $field){
  if (!isset($values[$field])) return "";

  return htmlspecialchars($values[$field]);
}
