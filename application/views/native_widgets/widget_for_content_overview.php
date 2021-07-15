<!-- Bootstrap 3.3.4 -->
<link href="<?php echo base_url();?>assets/modules/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- FontAwesome 4.3.0 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/v4-shims.min.css">
<!-- Theme style -->
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css" />

<style>body{background-color:unset;}</style>

<div class="card border">
	<div class="card-header">
		<h4><i class="fa fa-star"></i> <?php echo $this->lang->line('Top Web Pages From'); ?> &nbsp;&nbsp;<span class="bg-danger badge-pill float-right text-white small"><?php echo $from_date." to ".$to_date; ?></span></h4>
	</div>
	<?php if($data_found == 'yes') : ?>
	<div class="card-body">
		<div class="row">
			<div class="col-12">
				<?php  
					if(empty($content_overview_data)) {
						echo "<div class='text-center font-weight-bold h5'>".$this->lang->line('No data to show')."</div>";
					} else {
						$i = 0;
						foreach ($content_overview_data as $value) {
							$percentage = number_format($value['total_view']*100/$total_view, 2);
							$i++;
				?>
					<div>
						<label class="text-primary"><?php echo $value['visit_url']; ?></label>
						<label class="float-right bg-dark badge-pill text-white"><?php echo $percentage; ?>%</label>
					</div>
					<div class="progress mb-3" style="height: 20px;">
					  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage; ?>%"></div>
					</div>

				<?php 
					if($i==5) break;
					}
				} ?>



			</div>
		</div>
	</div>
	<?php else : ?>
	<div class="card-body text-center">
		<img class="img-fluid" src="<?php echo base_url("assets/img/drawkit/drawkit-nature-man-colour.svg"); ?>" style="height: 200px" alt="image">
		<h4><?php echo $this->lang->line('We couldn`t find any data'); ?></h4>
	</div>
	<?php endif; ?>
</div>
