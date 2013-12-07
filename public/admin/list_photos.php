<?php
require_once("../../includes/initialize.php");
if(!$session->is_logged_in()) { redirect_to("login.php"); }
// Find all the photos
$photos = Photograph::find_all();
?>

<?php include_layout_template('admin_header.php'); ?>

<h2>Photographs</h2>

<?=output_message($message);?>

<table class="bordered">
	<tr>
		<th>Image</th>
		<th>Filename</th>
		<th>Caption</th>
		<th>Size</th>
		<th>Type</th>
		<th>&nbsp;</th>
	</tr>
	<?php foreach ($photos as $photo): ?>
	<tr>
		<td><img src="../<?=$photo->image_path();?>" width="100"></td>
		<td><?=$photo->filename;?></td>
		<td><?=$photo->caption;?></td>
		<td><?=$photo->size_as_text();?></td>
		<td><?=$photo->type;?></td>
		<td><a href="delete_photo.php?id=<?=$photo->id;?>">Delete</a></td>
	</tr>
	<?php endforeach; ?>
</table>
<br>
<a href="photo_upload.php">Upload a new photograph</a>

<?php include_layout_template('admin_footer.php'); ?>
