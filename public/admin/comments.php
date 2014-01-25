<?php
require_once("../../includes/initialize.php");
if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php 


if(empty($_GET['id'])) {
		$session->message("No photo ID was provided");
		redirect_to("index.php");
	}

	$photo = Photograph::find_by_id($_GET['id']);
	if(!$photo) {
		$session->message("The photo could not be located.");
		redirect_to("index.php");
	}

	$comments = $photo->comments();

 ?>

<?php include_layout_template('admin_header.php'); ?>
	<a href="list_photos.php">&laquo; Back</a>
	<br>

	<h2>Comments on <?=$photo->filename;?></h2>
	<?=output_message($message);?>
	<div id="comments">
	<?php foreach ($comments as $comment) : ?>
		<div class="comment" style="margin-bottom: 2em;">
			<div class="author">
				<?=htmlentities($comment->author);?>
			</div>
			<div class="body">
				<?=strip_tags($comment->body, '<strong><em><p>');?>
			</div>
			<div class="meta-info" style="font-size:0.8em">
				<?=datetime_to_text($comment->created);?>
			</div>
			<div class="actions" style="font-size: 0.8em;">
				<a href="delete_comment.php?id=<?=$comment->id;?>">Delete Comment</a>
			</div>
		</div>
	<?php endforeach; ?>
	<?php if(empty($comments)) { echo "No Comments."; } ?>
</div>

<?php include_layout_template('admin_footer.php'); ?>
