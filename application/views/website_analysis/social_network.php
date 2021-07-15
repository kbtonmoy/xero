
<div id="social_network_success_msg" class="text-center" ></div>

<div class="card shadow-none">
	<div class="row">
		<div class="col-12">
			<div class="card-header">
				<h4><i class="fas fa-chart-pie"></i> <?php echo $this->lang->line('Social Network Comaprison'); ?></h4>
			</div>
			
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="social_chart_container mt-4">
							<canvas id="social_network_shared_data"></canvas>
						</div>

						<ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0 mt-4 ml-4 color_codes_div" id="color_codes">
							
						</ul>

					</div>

					<div class="col-12 col-md-6">
						<ul class="list-group">
							<li class="list-group-item active"><i class="fas fa-share-alt"></i> <?php echo $this->lang->line('Social Network Info'); ?></li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-facebook"></i> <?php echo $this->lang->line('Facebook Share'); ?></span>
								<span class="badge badge-primary badge-pill" id="fb_total_share"></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-facebook"></i> <?php echo $this->lang->line('Facebook Reaction'); ?></span>
								<span class="badge badge-primary badge-pill" id="fb_total_reaction"></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-facebook"></i> <?php echo $this->lang->line('Facebook Comment'); ?></span>
								<span class="badge badge-primary badge-pill" id="fb_total_comment"></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-pinterest"></i> <?php echo $this->lang->line('Pinterest Info'); ?></span>
								<span class="badge badge-primary badge-pill" id="pinterest_pin"></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-buffer"></i> <?php echo $this->lang->line('Buffer Info'); ?></span>
								<span class="badge badge-primary badge-pill" id="buffer_share"></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-xing-square"></i> <?php echo $this->lang->line('Xing Info'); ?></span>
								<span class="badge badge-primary badge-pill" id="xing_share"></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-reddit"></i> <?php echo $this->lang->line('Reddit Score'); ?></span>
								<span class="badge badge-primary badge-pill" id="reddit_score"></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-reddit"></i> <?php echo $this->lang->line('Reddit Ups'); ?></span>
								<span class="badge badge-primary badge-pill" id="reddit_ups"></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><i class="fab fa-reddit"></i> <?php echo $this->lang->line('Reddit Downs'); ?></span>
								<span class="badge badge-primary badge-pill" id="reddit_downs"></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
