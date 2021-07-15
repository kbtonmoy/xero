<div class="row">
	<div class="col-12">
		<div class="form-group">
		  <div class="input-group mb-3">
		    <div class="input-group-prepend"><div class="input-group-text" style="display: block;"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Date range");?></div></div>
		    <input type="text" class="form-control reservation" id="visitor_type_date">
		    <div class="input-group-append">
		      <button class="btn btn-info search_button" type="button"><i class="fa fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
		    </div>
		  </div>
		</div>
	</div>
	
	<div class="col-12">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="far fa-chart-bar"></i> <?php echo $this->lang->line('Day Wise New vs Returning User Report From'); ?> <span id="visitor_type_from_date"></span> to <span id="visitor_type_to_date"></span></h4>
	    </div>
	    <div class="card-body">
	      <canvas id="visitor_type_bar_chart" height="150"></canvas>
	    </div>
	  </div>
	</div>

	<div class="col-12 col-md-6">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="fas fa-users"></i> <?php echo $this->lang->line('Total New vs Returning User'); ?></h4>
	    </div>
	    <div class="card-body">
	      <canvas id="visitor_type_pieChart" height="200"></canvas>
	    </div>
	  </div>
	</div>

	<div class="col-12 col-md-6">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="fas fa-star"></i> <?php echo $this->lang->line('Top Web Pages From'); ?> <span id="content_overview_from_date"></span> To <span id="content_overview_to_date"></span></h4>
	    </div>
	    <div class="card-body">
	    	<div id="content_overview_data"></div>
	    </div>
	  </div>
	</div>

</div>