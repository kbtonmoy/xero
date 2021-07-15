<div class="row">
	<div class="col-12">
		<div class="form-group">
		  <div class="input-group mb-3">
		    <div class="input-group-prepend"><div class="input-group-text" style="display: block;"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Date range");?></div></div>
		    <input type="text" class="form-control reservation" id="traffic_source_date">
		    <div class="input-group-append">
		      <button class="btn btn-info search_button" type="button"><i class="fa fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
		    </div>
		  </div>
		</div>
	</div>

	<div class="col-12 col-md-6">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="far fa-registered"></i> <?php echo $this->lang->line("Top Referrer"); ?> (%)</h4>
	    </div>
	    <div class="card-body">
	      <canvas id="top_referrer_chart" height="200"></canvas>
	    </div>
	  </div>
	</div>

	<div class="col-12 col-md-6">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="fas fa-truck"></i> <?php echo $this->lang->line("Total Traffic"); ?></h4>
	    </div>
	    <div class="card-body">
	      <canvas id="traffic_bar_chart" height="200"></canvas>
	    </div>
	  </div>
	</div>
	
	<div class="col-12">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="far fa-chart-bar"></i> <?php echo $this->lang->line("Day Wise Traffic Source Report From"); ?> <span id="traffic_source_from_date"></span> to <span id="traffic_source_to_date"></span></h4>
	    </div>
	    <div class="card-body">
	      <canvas id="traffic_line-chart" height="250"></canvas>
	    </div>
	  </div>
	</div>

	<div class="col-12 col-md-6">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="fas fa-truck"></i> <?php echo $this->lang->line('Traffic From Search Engines'); ?></h4>
	    </div>
	    <div class="card-body">
	      <canvas id="search_enginge_traffic" height="200"></canvas>
	    </div>
	  </div>
	</div>

	<div class="col-12 col-md-6">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="fas fa-truck"></i> <?php echo $this->lang->line('Traffic From Social Networks'); ?></h4>
	    </div>
	    <div class="card-body">
	      <canvas id="social_network_traffic" height="200"></canvas>
	    </div>
	  </div>
	</div>
</div>