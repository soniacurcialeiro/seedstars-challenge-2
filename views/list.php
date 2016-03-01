<?php $this->layout('layout') ?>

<div class="page-header">
  <h1>List Contacts</h1>
</div>

<?php
if (!$contacts){
  echo "<p>There are no contacts to show.</p>";
}
else{
  ?>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($contact = $contacts->fetchArray()){?>
          <tr>
            <td><?php echo htmlspecialchars($contact["name"]);?></td>
            <td><?php echo htmlspecialchars($contact["email"]);?></td>
            <td class="text-right">
              <a href="/delete/<?php echo $contact["id"];?>" class="btn btn-sm btn-default" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
            </td>
          </tr>
          <?php
        }
        ?>
      </tbody>
    </table>
  </div>
<?php
}
?>

<p><a href="/add" class="btn btn-primary">Add</a></p>
