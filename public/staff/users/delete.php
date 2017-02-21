<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
// Retrieving user by id
$users_result = find_user_by_id($_GET['id']);
$user = db_fetch_assoc($users_result);

// Deleting usefr if porst reques
if(is_post_request()) {
  delete_user($user['id']);
  redirect_to('index.php');
}
?>

<?php $page_title = 'Staff: Delete User ' . h($user['first_name']) . " " . h($user['last_name']); ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="show.php?id=<?php echo u($user['id']); ?>">Back to user details</a><br />

  <h1>Delete User: <?php echo h($user['first_name']) . " " . h($user['last_name']); ?></h1>

    <form action="delete.php?id=<?php echo u($user['id']); ?>" method="post">
            
      <?php
        echo "Are you sure you want to <strong>permantly delete</strong> the user:";
        echo "<ul>";
        echo  "<li>" . h($user['first_name']) . " " . h($user['last_name']) . "</li>";
        echo "</ul>";
        echo "<br />";

        db_free_result($users_result);
      ?>
      <input type="submit" name="submit" value="Delete"  />
    </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>