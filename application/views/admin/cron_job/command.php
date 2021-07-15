<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-tasks"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>      
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <div class="section-body">
  	<div class="row">
      <div class="col-12">
      	<div class="card">
	                  
		  	<?php
			$text= $this->lang->line("Generate API Key");
			$get_key_text=$this->lang->line("Get Your API Key");
			if(isset($api_key) && $api_key!="")
			{
				$text=$this->lang->line("Re-generate API Key");
				$get_key_text=$this->lang->line("Your API Key");
			}
			if($this->is_demo=='1') $api_key='xxxxxxxxxxxxxxxxxxxxxxxxxx';
			?>

			<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'cron_job/get_api_action';?>" method="GET">
				<div class="card-header">
		            <h4><i class="fas fa-key"></i> <?php echo $get_key_text; ?></h4>
		          </div>
		          <div class="card-body">
		            <h4><?php echo $api_key; ?></h4>
		            <?php if($api_key=="") echo $this->lang->line("Every cron url must contain the API key for authentication purpose. Generate your API key to see the cron job list."); ?>
		          </div>
		          <div class="card-footer bg-whitesmoke">
		          	<button type="submit" name="button" class="btn btn-primary btn-lg btn <?php if($this->is_demo=='1') echo 'disabled';?>"><i class="fas fa-redo"></i> <?php echo $text; ?></button>
		          </div>
		        </div>
		    </form>


			<?php
			if($api_key!="") 
			{ ?>
				<div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Send Notification");?> <code><?php echo $this->lang->line("Once/Day"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/send_notification")."/".$api_key." >/dev/null 2>&1"; ?></span></code></pre>
                  </div>
                </div>
          

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> 
                      <?php echo $this->lang->line("Auction Domain");?>
                      <code><?php echo $this->lang->line("Once/Day"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/auction_domain")."/".$api_key." >/dev/null 2>&1"; ?></span></code></pre>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Keyword Rank Tracking");?> <code><?php echo $this->lang->line("Once/15 minutes"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/get_keyword_position_data")."/".$api_key." >/dev/null 2>&1"; ?></span></code></pre>
                  </div>
                </div>                
                
                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Junk Data Delete");?> <code><?php echo $this->lang->line("Once/Day"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/delete_junk_files")."/".$api_key." >/dev/null 2>&1"; ?></span></code></pre>
                  </div>
                </div> 

			<?php }?>
	  </div>
	</div>
  </div>
</section>