<!-- Bootstrap 3.3.4 -->
<link href="<?php echo base_url();?>assets/modules/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- FontAwesome 4.3.0 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/v4-shims.min.css">
<!-- Theme style -->
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css" />

<style>body{background-color:unset;}</style>
<style>body{background-color:unset;}</style>
<div class="card border">
	<div class="card-header">
		<h4><?php echo $this->lang->line('Report For Last 30 Days'); ?></h4>
	</div>
	<?php if($data_found == 'yes') : ?>
	<div class="card-body">
		<div class="row">
			<div class="col-12 col-md-6">
				<div class="card card-statistic-1">
					<div class="card-icon bg-primary">
						<i class="fas fa-user" style="line-height: 80px;"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4><?php echo $this->lang->line('Total Unique Visitor'); ?></h4>
						</div>
						<div class="card-body"><?php echo $total_unique_visitro; ?></div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-6">
				<div class="card card-statistic-1">
					<div class="card-icon bg-warning">
						<i class="fas fa-file-alt" style="line-height: 80px;"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4><?php echo $this->lang->line('Total Page View'); ?></h4>
						</div>
						<div class="card-body"><?php echo $total_page_view; ?></div>
					</div>
				</div>
			</div>
		</div><br>

		<div class="row mb-4">
			<div class="col-12 col-md-4">
				<div>
					<label class="text-primary"><?php echo $this->lang->line('Average Visit'); ?></label>
					<label class="float-right bg-primary badge-pill text-white"><?php echo $average_visit; ?></label>
				</div>
				<div class="progress mb-2" style="height: 20px;">
				  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $average_visit; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $average_visit; ?>%"></div>
				</div>
			</div>
			<div class="col-12 col-md-4">
				<div>
					<label class="text-warning"><?php echo $this->lang->line('Average Stay Time'); ?></label>
					<label class="float-right bg-warning badge-pill text-white"><?php echo $average_stay_time; ?></label>
				</div>
				<div class="progress mb-2" style="height: 20px;">
				  <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-valuenow="<?php echo $average_stay_time; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php if($average_stay_time == "0:0:0") echo 0; else echo "50%"; ?>"></div>
				</div>
			</div>
			<div class="col-12 col-md-4">
				<div>
					<label class="text-danger"><?php echo $this->lang->line('Bounce Rate'); ?></label>
					<label class="float-right bg-danger badge-pill text-white"><?php echo $bounce_rate; ?>%</label>
				</div>
				<div class="progress mb-2" style="height: 20px;">
				  <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $bounce_rate;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $bounce_rate;?>%"></div>
				</div>
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