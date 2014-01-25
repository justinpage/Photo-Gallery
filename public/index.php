<?php require_once("../includes/initialize.php"); ?>
<?php 

	//	1. the current page number ($current_page)
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

	//	2. records per page ($per_page)
	$per_page = 3;

	//	3. total record count($total_count)
	$total_count = Photograph::count_all();
	
	// find all photos
	// $photos = Photograph::find_all();

	$pagination = new pagination($page, $per_page, $total_count);

	// insted of finding all records, just find  the records
	// for this page
	$sql = "SELECT * FROM photographs ";
	$sql .= "LIMIT {$per_page} ";
	$sql .= "OFFSET {$pagination->offset()}";
	$photos = Photograph::find_by_sql($sql);

	// need to add ?page=$page to all links we want to 
	// maintain the current page (or store $page in $session)


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

	<div class="pagination" style="clear: both">
		<?php if($pagination->total_pages() > 1) : ?>

			
			<?php if($pagination->has_previous_page()) : ?>
				<a href="index.php?page=<?=$pagination->previous_page();?>">&laquo; Previous</a>
			<?php endif; ?> 

			<?php for($i = 1; $i <= $pagination->total_pages(); $i++) : ?>
				<?php if($i == $page): ?>
					<span class="selected"><?=$i?></span>
				<?php else : ?>
					<a href="index.php?page=<?=$i?>"><?=$i?></a>
				<?php endif; ?>

			<?php endfor; ?>

			<?php if($pagination->has_next_page()) : ?>
				<a href="index.php?page=<?=$pagination->next_page();?>">Next &raquo;</a>
			<?php endif; ?> 

		<?php endif; ?>
	</div>

<?php include_layout_template('footer.php'); ?>

