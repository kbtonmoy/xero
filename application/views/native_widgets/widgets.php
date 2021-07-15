<style>
	#text_area_for_traffic_code{height:unset !important; }
	.section-style .card-body .left-side { border-right: .5px solid #f9f9f9; }
	.waiting{padding-top: 100px;}
</style>

<?php 
	$domain_list_id = $this->uri->segment(3); 
	$domain_info = $this->basic->get_data('visitor_analysis_domain_list',['where'=>["id"=>$domain_list_id,"user_id"=>$this->user_id]]);
	$domain_code = isset($domain_info[0]['domain_code']) ? $domain_info[0]['domain_code'] : 0;
?>

<section class="section">
	<div class="section-header">
		<h1 class="mr-4"><i class="fas fa-puzzle-piece"></i> <?php echo $page_title; ?></h1>
		<div>
			<form action="<?php echo base_url("native_widgets/get_widget"); ?>" method="POST">
				<div class="form-group mb-0">
					<div class="input-group">
						<select class="custom-select select2" id="domain_list_id" name="domain_list_id" >
							<option value=""><?php echo $this->lang->line('Please Select Domain'); ?></option>
							<?php
								foreach($domain_name_array as $value){
									if($value['id'] == $domain_list_id) $selected = 'selected';
									else $selected = "";

									echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['domain_name'].'</option>';
								}
							?>
						</select>
						<div class="input-group-append">
						    <button class="btn btn-primary"><?php echo $this->lang->line('Generate'); ?></button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>


	<?php if(!empty($domain_list_id) && $this->basic->is_exist('visitor_analysis_domain_list',array("id"=>$domain_list_id,"user_id"=>$this->user_id))) : ?>
	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card card-primary section-style">
					<div class="card-header">
						<h4><i class="fa fa-delicious"></i> <?php echo $this->lang->line("Overview Report : Widget"); ?></h4>
						<div class="card-header-action">
							<a data-collapse="#overview-collapse" href="#"><i class="fas fa-minus"></i></a>
						</div>
					</div>

					<div class="collapse show" id="overview-collapse">
						<div class="card-body pb-0">
							<div class="row">
								<div class="col-12 mb-2">
									<pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo htmlspecialchars('<iframe src="'.base_url().'native_widgets/public_traffic_source_data/'.$domain_code.'" width="100%" height="400" frameborder="0"></iframe>'); ?></span></code></pre>
								</div>

								<div class="col-12">
									<div id="text_area_for_country_report_code">
										<iframe src="<?php echo base_url('native_widgets/public_traffic_source_data/'.$domain_code); ?>" frameborder="0" width="100%" height="400" style=""></iframe>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="card card-primary section-style">
					<div class="card-header">
						<h4><i class="fa fa-delicious"></i> <?php echo $this->lang->line("Country Wise New Visitor Report : Widget"); ?></h4>
						<div class="card-header-action">
							<a data-collapse="#country-collapse" href="#"><i class="fas fa-minus"></i></a>
						</div>
					</div>

					<div class="collapse show" id="country-collapse">
						<div class="card-body pb-0">
							<div class="row">
								<div class="col-12 mb-2">
									<pre class="language-javascript"><code class="dlanguage-javascript text-left"><span class="token keyword" id="text_area_for_traffic_code"><?php echo htmlspecialchars('<iframe src="'.base_url().'native_widgets/public_country_report_data/'.$domain_code.'" width="100%" height="450" frameborder="0"></iframe>'); ?></span></code></pre>
								</div>

								<div class="col-12">
									<div id="text_area_for_country_report_code">
										<iframe src="<?php echo base_url('native_widgets/public_country_report_data/'.$domain_code); ?>" frameborder="0" width="100%" height="450" style=""></iframe>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="card card-primary section-style">
					<div class="card-header">
						<h4><i class="fa fa-delicious"></i> <?php echo $this->lang->line("Content Overview Report : Widget"); ?></h4>
						<div class="card-header-action">
							<a data-collapse="#content-collapse" href="#"><i class="fas fa-minus"></i></a>
						</div>
					</div>

					<div class="collapse show" id="content-collapse">
						<div class="card-body pb-0">
							<div class="row">
								<div class="col-12">
									<pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword" id="text_area_for_traffic_code"><?php echo htmlspecialchars('<iframe src="'.base_url().'native_widgets/public_content_overview_data/'.$domain_code.'" width="100%" height="450" frameborder="0"></iframe>'); ?></span></code></pre>
								</div>

								<div class="col-12">
									<div id="text_area_for_country_report_code">
										<iframe src="<?php echo base_url('native_widgets/public_content_overview_data/'.$domain_code); ?>" frameborder="0" width="100%" height="450" style=""></iframe>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<?php else : ?>
		<div class="section-body">
			<div class="card">
				<div class="card-body text-center">
					<img class="img-fluid" src="<?php echo base_url("assets/img/drawkit/drawkit-nature-man-colour.svg"); ?>" style="height: 300px" alt="image">
					<h4><?php echo $this->lang->line('We couldn`t find any data'); ?></h4>
					<p class="lead"><?php echo $this->lang->line("Sorry we can`t find any data, please select domain name from the above dropdown to generate widgets."); ?></p>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>