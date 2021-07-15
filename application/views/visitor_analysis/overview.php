<div class="row">
	<div class="col-12">
		<div class="form-group">
		  <div class="input-group mb-3">
		    <div class="input-group-prepend"><div class="input-group-text" style="display: block;"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Date range");?></div></div>
		    <input type="text" class="form-control reservation" id="overview_date">
		    <div class="input-group-append">
		      <button class="btn btn-info search_button" type="button" id="add_domain"><i class="fa fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
		    </div>
		  </div>
		</div>
	</div>
	
	<div class="col-12">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="far fa-chart-bar"></i> <?php echo $this->lang->line('Day Wise New Visitor Report From'); ?> <span id="overview_from_date"></span> <?php echo $this->lang->line('to'); ?> <span id="overview_to_date"></span></h4>
	    </div>
	    <div class="card-body">
	      <canvas id="line-chart" height="134"></canvas>
	    </div>
	  </div>
	</div>
	
	<div class="col-12">
		<div class="row">
			<!-- Page Views -->
			<div class="col-12 col-md-6">
				<div class="card card-statistic-1">
					<div class="card-icon">
						<i class="fas fa-users text-primary"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4><?php echo $this->lang->line('Total Unique Visitor'); ?></h4>
						</div>
						<div class="card-body"><h6 class="mt-2" id="total_unique_visitor"></h6></div>
					</div>
				</div>
			</div>
			<!-- Bounce Rate -->
			<div class="col-12 col-md-6">
				<div class="card card-statistic-1">
					<div class="card-icon">
						<i class="fas fa-binoculars text-info"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4><?php echo $this->lang->line('Total Page View'); ?></h4>
						</div>
						<div class="card-body"><h6 class="mt-2" id="total_page_view"></h6></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12">
		<div class="row">
			<!-- Category Rank -->
			<div class="col-12 col-md-4">
				<div class="card card-statistic-1">
					<div class="card-icon bg-primary">
						<i class="far fa-clock"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4><?php echo $this->lang->line('Average Stay Time'); ?></h4>
						</div>
						<div class="card-body"><h6 class="mt-2" id="average_stay_time"></h6></div>
					</div>
				</div>
			</div>

			<!-- Global Rank -->
			<div class="col-12 col-md-4">
				<div class="card card-statistic-1">
					<div class="card-icon bg-warning">
						<i class="far fa-eye"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4><?php echo $this->lang->line('Average Visit'); ?></h4>
						</div>
						<div class="card-body"><h6 class="mt-2" id="average_visit"></h6></div>
					</div>
				</div>
			</div>

			<!-- Top Country Rank -->
			<div class="col-12 col-md-4">
				<div class="card card-statistic-1">
					<div class="card-icon bg-danger">
						<i class="fas fa-sign-out-alt"></i>
					</div>
					<div class="card-wrap">
						<div class="card-header">
							<h4><?php echo $this->lang->line('Bounce Rate'); ?></h4>
						</div>
						<div class="card-body"><h6 class="mt-2" id="bounce_rate"></h6></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>