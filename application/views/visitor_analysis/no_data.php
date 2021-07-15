<style>
	.box-card .card-statistic-1{border:.5px solid #dee2e6;border-radius: 4px;}
	.box-card .card-icon{ border: .5px solid #dee2e6; }
	.bg-body {background: #FAFDFB !important;}
	.social_shared_icon{ width: 40px;height: 10px; }
	.color_codes_div .media { border-bottom: 0; }
	.bg-direction { background-color: #a45fff !important; }
	.font-12 { font-size:12px !important; }
</style>


<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-chart-line"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="<?php echo base_url("menu_loader/analysis_tools"); ?>"><?php echo $this->lang->line("Analysis Tools"); ?></a></div>
			<div class="breadcrumb-item active"><a href="<?php echo base_url('visitor_analysis/index'); ?>"><?php echo $this->lang->line("Visitor Analytics"); ?></a></div>
			<div class="breadcrumb-item"><?php echo $this->lang->line('Report'); ?></div>
		</div>
	</div>

	<div class="section-body">
		<div class="card">
			<div class="card-header">
				<h4><i class="fas fa-ban"></i> <?php echo $this->lang->line('Not found'); ?></h4>
			</div>
			<div class="card-body">
				<div class="empty-state" data-height="600" style="height: 600px;">
					<img class="img-fluid" src="<?php echo base_url('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
					<h2 class="mt-0"><?php echo $this->lang->line('Looks like you got lost'); ?></h2>
					<p class="lead">
						<?php echo $this->lang->line('We could not find any data associated with this account.'); ?>
					</p>
					<a href="<?php echo base_url('visitor_analysis/index'); ?>" class="btn btn-warning mt-4"><?php echo $this->lang->line('Back'); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>