<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fas fa-robot"></i> <?php echo $page_title;?></h1>
		<div class="section-header-breadcrumb">

      		<div class="breadcrumb-item"><a href="<?php echo base_url('menu_loader/utlities') ?>"><?php echo $this->lang->line("Utilities");?></a></div>
			<div class="breadcrumb-item"><?php echo $page_title;?></div>
		</div>
	</div>

	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card main_card">

					<div class="card-body">
						<form class="form-horizontal" enctype="multipart/form-data"  method="POST" >
							<div class="row">
								<div class="form-group col-12 col-md-4" style="padding: 10px;">
									<label> <?php echo $this->lang->line('Default -  All Robots are'); ?></label>
									<select  class="form-control select2 select2-hidden-accessible" id="basic_all_robots" style="width:100%;" tabindex="-1" aria-hidden="true">
										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>       
										
									</select>
									<code><?php echo form_error('basic_all_robots'); ?></code>


								</div>  
								<div class="form-group col-12 col-md-4" style="padding: 10px;">
									<label><?php echo $this->lang->line('Crawl-Delay'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="crawl_delay" style="width:100%;" tabindex="-1" aria-hidden="true">
										<option value="0" selected><?php echo $this->lang->line('Default- No delay'); ?></option>
										<option value="5"><?php echo $this->lang->line('5 Seconds'); ?></option>                
										<option value="10"><?php echo $this->lang->line('10 Seconds'); ?></option>                
										<option value="20"><?php echo $this->lang->line('20 Seconds'); ?></option>                
										<option value="60"><?php echo $this->lang->line('60 Seconds'); ?></option>                
										<option value="120" ><?php echo $this->lang->line('120 Seconds'); ?></option>     
										
									</select>
									<code><?php echo form_error('crawl_delay'); ?></code>                     
								</div>
								<div class="form-group col-12 col-md-4" style="padding: 10px;">
									<label><?php echo $this->lang->line('Sitemap'); ?></label>
									  <input type="text" name="site_map" id="site_map" class="form-control" placeholder="<?php echo $this->lang->line('Leave Blank for none'); ?>">

								</div>
								<div class="form-group col-12 col-md-4">
								  <div class="custom-control custom-checkbox">
								      <input type="checkbox" class="custom-control-input" id="do_u_want_to_more_specific_robot" value="1" name="do_u_want_to_more_specific_robot">
								      <label class="custom-control-label" for="do_u_want_to_more_specific_robot"><?php echo $this->lang->line('Do You Want To More Specific Search Robots?'); ?></label>
								    </div>
								</div>
				
							</div> 

							<div class="row" id="custom_setting" style="display: none;">
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Crawl-Delay'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="custom_crawl_delay" style="width:100%;" tabindex="-1" aria-hidden="true">
										<option value="0" selected><?php echo $this->lang->line('Default- No delay'); ?></option>
										<option value="5"><?php echo $this->lang->line('5 Seconds'); ?></option>                
										<option value="10"><?php echo $this->lang->line('10 Seconds'); ?></option>                
										<option value="20"><?php echo $this->lang->line('20 Seconds'); ?></option>                
										<option value="60"><?php echo $this->lang->line('60 Seconds'); ?></option>                
										<option value="120" ><?php echo $this->lang->line('120 Seconds'); ?></option>     
										
									</select>
									<code><?php echo form_error('custom_crawl_delay'); ?></code>                     
								</div>
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Sitemap'); ?></label>
									<input type="text" class="form-control" id="custom_site_map" placeholder="<?php echo $this->lang->line('Leave Blank for none'); ?>">
									 <code><?php echo form_error('custom_site_map'); ?></code>
								</div>
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Google'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="google" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('google'); ?></code>                     
								</div>
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('MSN Search'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="msn_search" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('msn_search'); ?></code>                     
								</div>
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Yahoo'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="yahoo" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('yahoo'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Ask/Teoma'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="ask_teoma" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('ask_teoma'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Cuil'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="cuil" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('cuil'); ?></code>                     
								</div>
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('GigaBlast'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="gigablast" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('gigablast'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Scrub The Web'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="scrub" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('scrub'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('DMOZ Checker'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="dmoz_checker" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('dmoz_checker'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Nutch'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="nutch" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('nutch'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Alexa/Wayback'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="alexa_wayback" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('alexa_wayback'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Baidu'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="baidu" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('baidu'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Naver'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="never" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('never'); ?></code>                     
								</div>
							</div>
							
							<div class="row" id="custom_setting2" style="display: none;">
						
								<div class="card-header">
									<div class="section-title "><?php echo $this->lang->line('Specific Special Bots'); ?></div>
								</div>
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Google Image'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="google_image" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('google_image'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Google Mobile'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="google_mobile" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('google_mobile'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Yahoo MM'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="yahoo_mm" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('yahoo_mm'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('MSN Picture Search'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="msn_picsearch" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('msn_picsearch'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Singing Fish'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="singing_fish" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('singing_fish'); ?></code>                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label><?php echo $this->lang->line('Yahoo Blogs'); ?> </label>
									<select  class="form-control select2 select2-hidden-accessible" id="yahoo_blogs" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected><?php echo $this->lang->line('Allowed'); ?></option>
										<option value="refused"><?php echo $this->lang->line('Refused'); ?></option>      
										
									</select>
									<code><?php echo form_error('yahoo_blogs'); ?></code>                     
								</div>
								

							</div>

							<div class="row" id="custom_setting3" style="display: none;">
								<div class="card-header">
									<div class="section-title"><?php echo $this->lang->line('Restricted Directories'); ?>
									</div>
								</div>
								<div class="float-right">
									
									<div class="form-group col-12">
										<button type="button" id="btn2" class="btn btn-primary"><i class='fa fa-plus-circle'></i> <?php echo $this->lang->line('Add Directories'); ?></button>
									</div>
								</div>
								<div class="form-group col-12">
									<label><?php echo $this->lang->line('Directory'); ?> </label>
									<input type="text" class="form-control" id ="restricted_dir0"  placeholder="<?php echo $this->lang->line('Eg. /temp/img/'); ?>" name="directory[]">                 
								</div>
								
		
							
							</div>

							<div class="text-center">
								<button class="btn btn-lg btn-primary" id="generate_robot_code" type="button"><i class="fas fa-robot"></i> <?php echo $this->lang->line('Generate Robot Code'); ?></button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	var number_dir = 0;
	var all_robot = 1;
	var custom_robot = 0;
	$(document).ready(function(){

		$(document).on('click','#btn2',function(event){
			event.preventDefault();
			number_dir++;
			var added_dir = "restricted_dir"+number_dir;
			var str = '<div class="form-group col-12"><input type="text" class="form-control" id = "'+added_dir+'" placeholder="Eg. /temp/img/" name="directory[]"></div>';
			
			$("#custom_setting3").append(str);   

		});
		$(document).on('click','#generate_robot_code',function(event){
			event.preventDefault();
			var i;
			var dir_str = '';
			var dir = '';
			var restricted_dir = '';
			for(i = 0; i<= number_dir; i++){
			  dir = 'restricted_dir'+i;
			  dir_str = $('#'+dir+'').val();
			  restricted_dir = restricted_dir+dir_str+',';
			}
			$("#set_auto_comment_templete_modal").modal();
			$("#generate_robot_code").addClass('btn-progress');
			var base_url="<?php echo base_url(); ?>";
			  $.ajax({
			    type:'POST',
			    url: base_url+"tools/robot_code_generator_action",
			    data:{all_robot:all_robot,
			          custom_robot:custom_robot,

			          basic_all_robots:$("#basic_all_robots").val(),
			          crawl_delay:$("#crawl_delay").val(),
			          site_map:$("#site_map").val(),
			          custom_crawl_delay:$("#custom_crawl_delay").val(),
			          custom_site_map:$("#custom_site_map").val(),
			          google:$("#google").val(),
			          msn_search:$("#msn_search").val(),
			          yahoo:$("#yahoo").val(),
			          ask_teoma:$("#ask_teoma").val(),
			          cuil:$("#cuil").val(),
			          gigablast:$("#gigablast").val(),
			          scrub:$("#scrub").val(),
			          dmoz_checker:$("#dmoz_checker").val(),
			          nutch:$("#nutch").val(),
			          alexa_wayback:$("#alexa_wayback").val(),
			          baidu:$("#baidu").val(),
			          never:$("#never").val(),

			          google_image:$("#google_image").val(),
			          google_mobile:$("#google_mobile").val(),
			          yahoo_mm:$("#yahoo_mm").val(),
			          msn_picsearch:$("#msn_picsearch").val(),
			          SingingFish:$("#SingingFish").val(),
			          yahoo_blogs:$("#yahoo_blogs").val(),
			          restricted_dir:restricted_dir
			          
			    },
			    success:function(response){
			     $("#unique_email_download_div").html('<p><?php echo $this->lang->line('Your file is ready download'); ?></p> <a href="<?php echo base_url()."download/robot/robot_{$this->user_id}_{$this->download_id}.txt" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b><?php echo $this->lang->line("download"); ?></b></a>');
			        $("#generate_robot_code").removeClass('btn-progress');
			        $("#success_msg").html(response);

			    }

			  });
		})
		$(document).on('change','#do_u_want_to_more_specific_robot',function(event){
		  event.preventDefault();
		 
		 if($('input[name=do_u_want_to_more_specific_robot]').prop('checked')){
		    all_robot = 0;
            custom_robot =1;
		    $('#custom_setting').slideDown(500);
		    $('#custom_setting2').slideDown(500);
		    $('#custom_setting3').slideDown(500);
		    $('#show_hide').hide();
		 }

		 else{
		    all_robot = 1;
            custom_robot =0;
		    $('#custom_setting').slideUp(500);
		    $('#custom_setting2').slideUp(500);
		    $('#custom_setting3').slideUp(500);
		  }

		}); 





	});

</script>


<div class="modal fade show" id="set_auto_comment_templete_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #fefefe;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-robot"></i> <?php echo $this->lang->line('Robot Code Generated'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      
      <div class="modal-body text-center" id="unique_email_download_div"> 
       
      </div>
      
    </div>
  </div>
</div>