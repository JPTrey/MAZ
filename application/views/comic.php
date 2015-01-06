<div class="row">
	<div class="col-sm-10 col-sm-offset-1">	
		<div class="panel panel-warning shadow" style="margin-bottom: 0;">
			<a class="left carousel-control" href="<?php echo $comic->number-1; ?>"><span class="glyphicon glyphicon-chevron-left"></span></a>
			<h2 class="text-center">#<?php echo $comic->number . ' - "' . $comic->title . '"' ?></h2>
			<a class="right carousel-control" href="<?php echo $comic->number+1; ?>"><span class="glyphicon glyphicon-chevron-right"></span></a>
		</div>
		<img class="img img-responsive shadow" src="<?php echo $comic->img_src ?>" style="width: 100%;"></img>
	</div>
</div>

<div class="row">
	<div class="col-sm-4 col-sm-offset-4">
		<a 
			class="btn btn-primary" href="/show/gallery/<?php echo max(1, round($comic->number/20)); ?>" style="width: 100%; margin-top: 20px;">
			Back to Gallery
		</a>
	</div>
</div>