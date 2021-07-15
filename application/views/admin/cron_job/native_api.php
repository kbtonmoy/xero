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

			<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'native_api/get_api_action';?>" method="GET">
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
        <?php $call_sync_contact_url=site_url("native_api/sync_contact"); ?>


			<?php
			if($api_key!="") 
			{ ?>
				<div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Get Content Overview Data (Your website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/get_content_overview_data?api_key=<?php echo $api_key; ?>&domain_code=Domain_Code_Get_From_Visitor_Analysis_Menu</span></code></pre>
                    <br>
                    <?php $example_url=site_url()."native_api/get_content_overview_data?api_key=".$api_key."&domain_code=2022261578919857-1";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                     {""total_view_for_this_domain":0,"content_overview_data":[]"}
                  </div>
                </div>
          

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> 
                      <?php echo $this->lang->line("Get Overview Data (Your website)");?>
                     </h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/get_overview_data?api_key=<?php echo $api_key; ?>&domain_code=Domain_Code_Get_From_Visitor_Analysis_Menu</span></code></pre>
                    <br>
                    <?php $example_url=site_url()."native_api/get_overview_data?api_key=".$api_key."&domain_code=36180644";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                     {"total_page_view":"0","total_unique_visitro":"0","average_visit":"0","average_stay_time":"0:0:0","bounce_rate":0}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Facebook Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/facebook_ckeck?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                    <?php $example_url=site_url()."native_api/facebook_ckeck?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                     {"status":"1","details":"Success","total_share":583,"total_reaction":841,"total_comment":96,"total_comment_plugin":0}
                  </div>
                </div> 

<!--                 <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Linkedin Check (any website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/linkedin_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/linkedin_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","total_share":0}
                  </div>
                </div>  -->

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Xing Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/xing_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/xing_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","total_share":"0 "}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Reddit Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/reddit_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/reddit_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","score":0,"downs":0,"ups":0}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Pinterest Check (Any Website))");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/pinterest_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/pinterest_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","pinterest_pin":0}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Buffer Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/buffer_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/buffer_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","buffer_share":0}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Page Status Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/pagestatus_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/pagestatus_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","http_code":200,"total_time":12.293,"namelookup_time":0.124,"connect_time":0.14,"speed_download":6102}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Alexa Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/alexa_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/alexa_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                    {"reach_rank":"515095","country":"Egypt","country_rank":"16776","traffic_rank":"429248"}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("SimilarWeb Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/similar_web_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/similar_web_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Bing Index Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/bing_index_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/bing_index_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response:'); ?> <br>
                    {9}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Yahoo Index Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/yahoo_index_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/yahoo_index_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response:'); ?> <br>
                    {9}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Link Analysis Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/link_analysis_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                    <?php $example_url=site_url()."native_api/link_analysis_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Backlink Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/backlink_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/backlink_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response:'); ?> <br>
                    {9}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Google Safe Browser Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo site_url();?>native_api/google_malware_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/google_malware_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response:'); ?> <br>
                    {"safe"}
                  </div>
                </div>      
                
                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("McAfee Malware Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo site_url();?>native_api/macafee_malware_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/macafee_malware_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response:'); ?> <br>
                    {"safe"}
                  </div>
                </div>

<!--                 <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("AVG Malware Check (any website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo site_url();?>native_api/avg_malware_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/avg_malware_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response:'); ?> <br>
                    {"safe"}
                  </div>
                </div> -->

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Norton Malware Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo $this->lang->line('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo site_url();?>native_api/norton_malware_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                    <br>
                   <?php $example_url=site_url()."native_api/norton_malware_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo $this->lang->line('Example Response:'); ?> <br>
                    {"safe"}
                  </div>
                </div>

               <div class="card">
                 <div class="card-header">
                   <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Domain IP Check (Any Website)");?></h4>
                 </div>
                 <div class="card-body">
                   <?php echo $this->lang->line('API HTTP URL:'); ?>
                   <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo site_url();?>native_api/domain_ip_check?api_key=<?php echo $api_key; ?>&domain=ANY_WEBSITE_DOMAIN</span></code></pre>
                   <br>
                  <?php $example_url=site_url()."native_api/domain_ip_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                   <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                   <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                   <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                   {"isp":"GoDaddy.com, LLC","ip":"166.62.28.90","city":"Scottsdale","region":"Arizona","country":" United States","time_zone":"America\/Phoenix","longitude":"-111.890600","latitude":"33.611900"}
                 </div>
               </div> 
               <div class="card">
                 <div class="card-header">
                   <h4><i class="fas fa-circle"></i> <?php echo $this->lang->line("Sites in Same IP Check (Any Website)");?></h4>
                 </div>
                 <div class="card-body">
                   <?php echo $this->lang->line('API HTTP URL:'); ?>
                   <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo site_url();?>native_api/sites_in_same_ip_check?api_key=<?php echo $api_key; ?>&ip=ANY_IP_ADDRESS</span></code></pre>
                   <br>
                  <?php $example_url=site_url()."native_api/sites_in_same_ip_check?api_key=".$api_key."&ip=192.64.112.13";?>
                   <?php echo $this->lang->line('Example API HTTP URL:'); ?> <br>
                   <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                   <?php echo $this->lang->line('Example Response (JSON):'); ?> <br>
                   {"twitter.com"}
                 </div>
               </div> 

			<?php }?>
	  </div>
	</div>
  </div>
</section>