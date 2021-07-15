<style type="text/css">
  .multi_layout{margin:0;background: #fff}
  .multi_layout .card{margin-bottom:0;border-radius: 0;}
  .multi_layout p, .multi_layout ul:not(.list-unstyled), .multi_layout ol{line-height: 15px;}
  .multi_layout .list-group li{padding: 25px 10px 12px 25px;}
  .multi_layout{border:.5px solid #dee2e6;}
  .multi_layout .collef,.multi_layout .colmid{padding-left: 0px; padding-right: 0px;border-right: .6px solid #dee2e6;border-bottom: .6px solid #dee2e6;}
  .multi_layout .colmid .card-icon{border:.5px solid #dee2e6;}
  .multi_layout .colmid .card-icon i{font-size:30px !important;}
  .multi_layout .main_card{min-height: 400px;box-shadow:none;}
  .multi_layout .collef .makeScroll{max-height:430px;overflow:auto;}
  .multi_layout .list-group .list-group-item{border-radius: 0;border:.5px solid #dee2e6;border-left:none;border-right:none;z-index: 0;}
  .multi_layout .list-group .list-group-item:first-child{border-top:none;}
  .multi_layout .list-group .list-group-item:last-child{border-bottom:none;}
  .multi_layout .list-group .list-group-item.active{border:.5px solid #6777EF;}
  .multi_layout .mCSB_inside > .mCSB_container{margin-right: 0;}
  .multi_layout .card-statistic-1{border:.5px solid #dee2e6;border-radius: 4px;}
  .multi_layout h6.page_name{font-size: 14px;}
  .multi_layout .card .card-header input{max-width: 100% !important;}
  .multi_layout .card-primary{margin-top: 35px;margin-bottom: 15px;}
  .multi_layout .product-details .product-name{font-size: 12px;}
  .multi_layout .margin-top-50 {margin-top: 70px;}
  .multi_layout .waiting {height: 100%;width:100%;display: table;}
  .multi_layout .waiting i{font-size:60px;display: table-cell; vertical-align: middle;padding:10px 0;}
  .waiting {padding-top: 100px;}
  .bck_clr{background: #ffffff!important;}
</style>


<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-bars"></i> <?php echo $page_title;?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url('menu_loader/keyword_position_tracking') ?>"><?php echo $this->lang->line("Keyword Tracking");?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title;?></div>
    </div>
  </div>
</section>

<div class="row multi_layout">

	<div class="col-12 col-md-3 collef">
		<div class="card main_card">
			<form  method="POST" enctype="multipart/form-data"  id="kewyord_position_report_form">
				<div class="card-header">
					<h4><i class="fas fa-info-circle"></i> <?php echo $this->lang->line('Keyword Information'); ?></h4>
				</div>

				<div class="card-body">
					<div class="form-group">
						<label><?php echo $this->lang->line('Keyword'); ?></label>
						<?php 
						$keywords['']=$this->lang->line("select keyword");
						echo form_dropdown('keyword',$keywords,set_value('keyword'),' class="form-control select2" id="keyword" style="width:100%"');  
						?>
					</div>

					<div class="form-group">
						<label><?php echo $this->lang->line('From'); ?></label>
						<input type="text" class="form-control datepicker_x" id="from_date" placeholder="<?php echo $this->lang->line('from date'); ?>" style="width:100%;">
					</div>

					<div class="form-group">
						<label><?php echo $this->lang->line('To'); ?></label>
						<input type="text" class="form-control datepicker_x" id="to_date" placeholder="<?php echo $this->lang->line('to date'); ?>" style="width:100%;">
					</div>
				</div>

				<div class="card-footer bg-whitesmoke mt-4">
					<button type="button" id="start_searching" class="btn btn-primary "><i class="fas fa-search"></i> <?php echo $this->lang->line("Search"); ?></button>
					<button class="btn btn-secondary btn-md float-right" onclick="goBack('menu_loader/keyword_position_tracking')" type="button"><i class="fa fa-remove"></i> <?php echo $this->lang->line('Cancel'); ?></button>
				</div>
			</form>
		</div>          
	</div>

	<div class="col-12 col-md-9 colmid">
		<div id="unique_per"></div>
		<div class="card shadow-none">
			<div class="card-header">
				<h4> <i class="fas fa-bars"></i> <?php echo $this->lang->line('Keyword Position Report'); ?></h4>
			</div>

			<div class="card-body">
				<div id="custom_spinner"></div>
				<div id="middle_column_content" style="background: #ffffff!important;">
					<div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">
						<div class="empty-state">
							<img class="img-fluid" src="<?php echo base_url("assets/img/drawkit/revenue-graph-colour.svg"); ?>" style="height: 300px" alt="image">
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function() {

		var today = new Date();
		var next_date = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
		$('.datepicker_x').datetimepicker({
		    theme:'light',
		    format:'Y-m-d',
		    formatDate:'Y-m-d',
		    timepicker:false
		});

		var base_url = '<?php echo base_url(); ?>';

		$(document).on('click', '#start_searching', function(event) {
			event.preventDefault();
			
			var keyword = $("#keyword").val();
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();

			if (keyword == '' || from_date == '' || to_date == '') {
		  		swal("<?php echo $this->lang->line('warning'); ?>", "<?php echo $this->lang->line('Please fill all required fields.'); ?>", 'warning');
			  	return false;
			}

			$(this).addClass('btn-progress');
			var that = $(this);

			var go_back = base_url+"keyword_position_tracking/";

			$("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><p class="text-center"><?php echo $this->lang->line('Please wait for while...'); ?></p>');
			$('#middle_column_content').html("");

			$.ajax({
				url: base_url+'keyword_position_tracking/keyword_position_report_data',
				type: 'POST',
				data: {keyword: keyword,from_date: from_date,to_date: to_date},
				success:function(response) {

					$(that).removeClass('btn-progress');
					$("#custom_spinner").html("");
					$("#middle_column_content").html(response);

				}
			})
		});
	});
</script>
