<h2 class="text-center">All Comics</h2>
<!-- <h4>Page <?php echo $page ?> of <?php echo ~~sizeof($comics)/20; ?></h4> -->
<div class="row text-center">
	<a class="btn btn-danger <?php if ($order === "old") { echo 'disabled'; } ?>">Oldest First</a>
	<a class="btn btn-danger 
	<?php 
	if ($order === "new") { 
		echo 'disabled'; 
		array_reverse($comics); 
	} 
	?>">Newest First</a>
</div>

<?php 
if ($page > (sizeof($comics)/20)) { $page = sizeof($comics)/20; }		// verify max page
$column_count = 0;	// comic thumbnails on current row
$comic_count = 0;
foreach ($comics as $comic): 
	if ($page > 1 && $comic_count < 20*($page-1)) { 		// skip comics from previous page
		$comic_count++; 
		continue; 
	}	
	else if ($comic_count > 20*$page) { break; }			// stop loading comics from next page
	else if ($column_count === 0) { echo '<div class=row>'; }
?>
	
	<div class="col-sm-3 col-xs-12">
		<h2 class="panel panel-default text-center shadow" style="margin-bottom: 5px;">
			<?php 
			echo '#'; 
			if ($comic->number < 100) { echo '0'; }
			if ($comic->number < 10) { echo '0'; }
			echo $comic->number . ' - "' . $comic->title . '"'; 
			?>
		</h2>
		<a href="/show/comic/<?php echo $comic->id ?>"><img class="img img-responsive thumbnail shadow" src="<?php echo $comic->img_src ?>"></img></a>
		<hr class="hidden-sm hidden-md hidden-lg"/>
	</div>

<?php 
if ($column_count === 3) { 
	echo '</div>'; 
	$comic_count += 4;
	$column_count = -1; 
}

$column_count++;
endforeach 
?>

</div>
<div class="row"> <!-- ROW for page selector -->
	<div class="col-sm-6 col-sm-offset-4 col-xs-offset-4">	
		<nav>
		  <ul class="pagination pagination-lg">
		    <li><a href="<?php echo $page-1; ?>"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>
		    <?php 
		    for ($i=-3; $i < 4; $i++) {
		    	if ($page + $i > 0) {
			    	if ($i === 0) {	echo '<li class="active"><a href="'; } 	// mark current page
			    	else { echo '<li><a href="'; }
		    	
		    		echo '/show/gallery/';
		    		echo $page+$i;
		    		echo '">';
		    		echo $page+$i;
		    		echo '</a></li>';
				}	
		    } 
		    ?>
		    <li><a href="<?php echo $page+1; ?>"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>
		  </ul>
		</nav>
	</div>
</div>		