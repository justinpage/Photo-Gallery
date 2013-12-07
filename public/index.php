<?php require_once("../includes/initialize.php"); ?>
<?php 
	// find all photos
	$photos = Photograph::find_all();
 ?>
<?php include_layout_template('header.php'); ?>

	<?php foreach ($photos as $photo) : ?>
		<dv style="float: left; margin-left: 20px">
			<a href="photo.php?id=<?=$photo->id;?>">
				<img src="<?=$photo->image_path();?>" width="200">
			</a>
			<p><?=$photo->caption;?></p>
		</dv>
	<?php endforeach; ?>

<?php include_layout_template('footer.php'); ?>

