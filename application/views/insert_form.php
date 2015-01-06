<?php 
echo validation_errors(); 
?>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">Comic Info</div>
			<div class="panel-body">
				<?php 
				echo form_open(); 
				
				echo '<div class="row">';
				echo '<div class="col-sm-offset-1">';

				echo form_label("Installment #", "number");
				echo '<br/>';
				echo form_input(array(
					'type' => 'text',
					'name' => 'number',
					'placeholder' => '000',
					'required' => 'true',
				));
				echo '</div>';
				echo '</div>';

				echo '<div class="row">';
				echo '<div class="col-sm-offset-1">';
				echo form_label("Publication Date", "date");
				echo '<br/>';
				echo form_input(array(
					'type' => 'date',
					'name' => 'date',
				));
				echo '</div>';
				echo '</div>';

				echo '<div class="row">';
				echo '<div class="col-sm-offset-1">';
				echo form_label("Choose topic", "radio-topic");
				echo '<br/>';
				echo form_radio("radio-topic", "existing", TRUE);
				echo form_dropdown("topic", $topic_form_options);
				echo '</div>';
				echo '</div>';
				
				echo '<div class="row">';
				echo '<div class="col-sm-offset-1">';
				echo form_radio("radio-topic", "new", FALSE);
				echo form_label("Add New Topic", "new-topic");
				echo '<br/>';
				echo form_input(array(
					'type' => 'text',
					'name' => 'new-topic',
					'placeholder' => 'topic/game'
				));
				echo '</div>';
				echo '</div>';

				echo '<div class="row">';
				echo '<div class="col-sm-offset-1">';
				echo form_label("Title", "title");
				echo '<br/>';
				echo form_input(array(
					'type' => 'text',
					'name' => 'title',
					'placeholder' => 'Title',
				));
				echo '</div>';
				echo '</div>';

				echo '<div class="row">';
				echo '<div class="col-sm-offset-1">';
				echo form_label("Image URL", "img_src");
				echo '<br/>';
				echo form_input(array(
					'type' => 'text',
					'name' => 'img_src',
					'placeholder' => 'http://subdomain.domain.com/parameters',
				));
				echo '</div>';
				echo '</div>';

				echo '<div class="row">';
				echo '<div class="col-sm-offset-1">';
				echo '<br/>';

				echo form_submit("submit", "Add");
				echo '</div>';
				echo '</div>';
				echo form_close();
				?>
			</div>
		</div>
	</div>
</div>		