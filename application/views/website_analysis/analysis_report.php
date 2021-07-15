<style>
	.box-card .card-statistic-1{border:.5px solid #dee2e6;border-radius: 4px;}
	.box-card .card-icon{ border: .5px solid #dee2e6; }
	.bg-body {background: #FAFDFB !important;}
	.social_shared_icon{ width: 40px;height: 10px; }
	.color_codes_div .media { border-bottom: 0; }
	.bg-direction { background-color: #a45fff !important; }
	.font-12 { font-size:12px !important; }
</style>

<input type="hidden" id="domain_id" value="<?php echo $id; ?>"/>

<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-globe"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="<?php echo base_url("menu_loader/analysis_tools"); ?>"><?php echo $this->lang->line("Analysis Tools"); ?></a></div>
			<div class="breadcrumb-item active"><a href="<?php echo base_url('website_analysis'); ?>"><?php echo $this->lang->line("Website Analysis"); ?></a></div>
			<div class="breadcrumb-item"><?php echo $this->lang->line('Report'); ?></div>
		</div>
	</div>

	<div class="section-body">
		<div class="card">

			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo "Domain Name - <span class='red'>".$domain_info[0]['domain_name']."</span>"; ?></h4>
				<div class="card-header-action">
					<a href="<?php echo base_url('website_analysis/download_analysis_report/'.$id); ?>" class="btn btn-lg btn-primary" title="<?php echo $this->lang->line('Download Report as Pdf'); ?>"><i class="fas fa-cloud-download-alt"></i> <?php echo $this->lang->line('Download Report'); ?></a>
				</div>
			</div>
			
			<div class="card-body">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><?php echo $this->lang->line('General'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="social_network-tab" data-toggle="tab" href="#social_network" role="tab" aria-controls="social_network" aria-selected="false"><?php echo $this->lang->line('Social Network Information'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="meta_tag_info-tab" data-toggle="tab" href="#meta_tag_info" role="tab" aria-controls="meta_tag_info" aria-selected="false"><?php echo $this->lang->line('Keyword & Meta Information'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="alexa_info-tab" data-toggle="tab" href="#alexa_info" role="tab" aria-controls="alexa_info" aria-selected="false"><?php echo $this->lang->line('Alexa Information'); ?></a>
					</li>
				</ul>
				<div class="tab-content tab-bordered" id="myTab3Content">
					<div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
						<?php $this->load->view('website_analysis/general'); ?>
					</div>
					<div class="tab-pane fade" id="social_network" role="tabpanel" aria-labelledby="social_network-tab">
						<?php $this->load->view("website_analysis/social_network"); ?>
					</div>
					<div class="tab-pane fade" id="meta_tag_info" role="tabpanel" aria-labelledby="meta_tag_info-tab">
						<?php $this->load->view("website_analysis/meta_tag_info"); ?>
					</div>
					<div class="tab-pane fade" id="alexa_info" role="tabpanel" aria-labelledby="alexa_info-tab">
						<?php $this->load->view('website_analysis/alexa_info'); ?>	
					</div>

				</div>
			</div>

		</div>
	</div>
</section>


<script type="text/javascript">
	$('.reservation').daterangepicker();

	$('[data-toggle="popover"]').popover();
	$('[data-toggle=\"tooltip\"]').tooltip();

	var function_name;

	$("document").ready(function(){
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			e.preventDefault();
			var target = $(e.target).attr("href");
			function_name = target.replace('#','');
			ajax_call(function_name);		

		}); // end of $('a[data-toggle="tab"]')


		$(document).on('click','.search_button',function(){
			ajax_call(function_name);			
		});	


		function ajax_call(function_name)
		{
			var domain_id = $("#domain_id").val();
			var date_range = $("#"+function_name+"_date").val();

			if(function_name == 'visitor_analysis')
				date_range = $("#overview_date").val();

			var base_url="<?php echo base_url(); ?>";
			var data_type = "JSON";
			if(function_name == 'alexa_info' || function_name == 'general' || function_name == 'browser_report' || function_name == 'similarweb_info' || function_name == 'os_report' || function_name == 'device_report' || function_name == 'meta_tag_info')
				data_type = '';
			$('#'+function_name+'_success_msg').html('<img class="center-block" style="margin-top:10px;" src="'+base_url+'assets/pre-loader/full-screenshots.gif" alt="Searching...">');

			$.ajax({
				type: "POST",
				url : "<?php echo site_url('website_analysis/ajax_get_"+function_name+"_data/'); ?>",
				data:{domain_id:domain_id,date_range:date_range},
				dataType: data_type,
				async: false,
				success:function(response){
					$('#'+function_name+'_success_msg').html('');
					$("#"+function_name+"_name").html(response);
					var pieOptions = {
					    //Boolean - Whether we should show a stroke on each segment
					    segmentShowStroke: true,
					    //String - The colour of each segment stroke
					    segmentStrokeColor: "#fff",
					    //Number - The width of each segment stroke
					    segmentStrokeWidth: 1,
					    //Number - The percentage of the chart that we cut out of the middle
					    percentageInnerCutout: 30, // This is 0 for Pie charts
					    //Number - Amount of animation steps
					    animationSteps: 100,
					    //String - Animation easing effect
					    animationEasing: "easeOutBounce",
					    //Boolean - Whether we animate the rotation of the Doughnut
					    animateRotate: true,
					    //Boolean - Whether we animate scaling the Doughnut from the centre
					    animateScale: false,
					    //Boolean - whether to make the chart responsive to window resizing
					    responsive: true,
					    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
					    maintainAspectRatio: false,
					    //String - A tooltip template
					    tooltipTemplate: "<%=value %> <%=label%>"
					};

					/*************** for general page **********************/
					if(function_name == 'general'){
						$("#hide_after_ajax").hide();
					}
					/************** end of general page *******************/


					/********************* for social network page *************************/
					if (function_name == 'social_network') {

						var social_network_shared_config = document.getElementById('social_network_shared_data').getContext('2d');

						var only_keys = Object.keys(response.social_network_info);
						var only_values = Object.values(response.social_network_info);

						var social_network_shared_chart_data = {
						 	type: 'doughnut',
						 	data: {
						 		datasets: [{
						 			data: only_values,
						 			backgroundColor: [
						 				'#003f5c',
						 				'#4571ef',
						 				'#ce6f45',
						 				'#58508d',
						 				'#bc5090',
						 				'#ff6361',
						 				'#ffa600',
						 			],
						 			
						 		}],
						 		labels: only_keys,
						 	},
						 	options: {
						 		responsive: true,
						 		legend: {
						 			display: false,
						 		},
						 		
						 		animation: {
						 			animateScale: true,
						 			animateRotate: true
						 		}
						 	}
						 };

						var social_network_info_my_chart = new Chart(social_network_shared_config, social_network_shared_chart_data);

						$(".domain_name").text(response.domain_name);

						$("#color_codes").html(response.color_codes);
						$("#fb_total_reaction").text(response.fb_total_reaction);
						$("#fb_total_comment").text(response.fb_total_comment);
						$("#fb_total_share").text(response.fb_total_share);
						$("#fb_total_comment_plugin").text(response.fb_total_comment_plugin);

						$("#reddit_score").text(response.reddit_score);
						$("#reddit_ups").text(response.reddit_ups);
						$("#reddit_downs").text(response.reddit_downs);

						$("#google_plus_share").text(response.google_plus_share);
						$("#pinterest_pin").text(response.pinterest_pin);
						$("#buffer_share").text(response.buffer_share);
						$("#xing_share").text(response.xing_share);
						$("#linkedin_share").text(response.linkedin_share);


					}
					/******************* end of social network page **********************/
					
				} //end of success

			}); // end of ajax
		} //end of function ajax_call

	});
</script>

