<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loader.js"></script>



<div class="row">

	<div class="col-12">

		<div class="form-group">

		  <div class="input-group mb-3">

		    <div class="input-group-prepend"><div class="input-group-text" style="display: block;"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Date range");?></div></div>

		    <input type="text" class="form-control reservation" id="country_wise_report_date">

		    <div class="input-group-append">

		      <button class="btn btn-info search_button" type="button"><i class="fa fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>

		    </div>

		  </div>

		</div>

	</div>

	

	<div class="col-12">

	  <div class="card">

	    <div class="card-header">

	      <h4><i class="fas fa-flag"></i> <?php echo $this->lang->line('Country Wise New Visitor Report'); ?></h4>

	    </div>

	    <div class="card-body">

	    	<div id="regions_div" style="height: 450px;"></div>

	    </div>

	  </div>

	</div>



	<div class="col-12">

	  <div class="card">

	    <div class="card-header">

	      <h4><i class="fa fa-map-marker"></i> <?php echo $this->lang->line('Country Wise Report From'); ?> <span id="country_wise_visitor_from_date"></span> <?php echo $this->lang->line('To'); ?> <span id="country_wise_visitor_to_date"></span></h4>

	    </div>

	    <div class="card-body">

	    	<div id="country_wise_table_data" class="table-responsive scrool" style="height: 500px; overflow: hidden;"></div>

	    </div>

	  </div>

	</div>



</div>

<script type="text/javascript">
	$(document).ready(function() { 
		$(".scrool").mCustomScrollbar({
		  autoHideScrollbar:true,
		  theme:"rounded-dark"
		});
	});
</script>