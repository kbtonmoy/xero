<div class="row">
	<div class="col-12">
		<div class="form-group">
		  <div class="input-group mb-3">
		    <div class="input-group-prepend"><div class="input-group-text" style="display: block;"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Date range");?></div></div>
		    <input type="text" class="form-control reservation" id="device_report_date">
		    <div class="input-group-append">
		      <button class="btn btn-info search_button" type="button"><i class="fa fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
		    </div>
		  </div>
		</div>
	</div>

	<div class="col-12">
	  <div class="card">
	    <div class="card-header">
	      <h4><i class="fa fa-tv"></i> <?php echo $this->lang->line('Report From'); ?> <span id="device_table_from_date"></span> <?php echo $this->lang->line('to'); ?> <span id="device_table_to_date"></span></h4>
	    </div>
	    <div class="card-body">
	    	<div id="device_report_name" class="table-responsive"></div>
	    </div>
	  </div>
	</div>
</div>


