<?php $this->session->set_userdata('count',"1");?>
<style type="text/css">
  .multi_layout{margin:0;background: #fff}
  .multi_layout .card{margin-bottom:0;border-radius: 0;}
  .multi_layout p, .multi_layout ul:not(.list-unstyled), .multi_layout ol{line-height: 15px;}
  .multi_layout .list-group li{padding: 25px 10px 12px 25px;}
  .multi_layout{border:.5px solid #dee2e6;}
  .multi_layout .collef,.multi_layout .colmid{padding-left: 0px; padding-right: 0px;border-right: .6px solid #dee2e6;border-bottom: .6px solid #dee2e6;}
  .multi_layout .colmid .card-icon{border:.5px solid #dee2e6;}
  .multi_layout .colmid .card-icon i{font-size:30px !important;}
  .multi_layout .main_card{min-height: 450px;}
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
  .waiting {padding-top: 200px;}
  .check_box{position: absolute !important;top: 0 !important;right: 0 !important;margin: 3px;}
  .check_box_background{position: absolute;height: 60px;width: 60px;top: 0;right: 0;font-size: 13px;}
  .profile-widget { margin-top: 0;}
  .profile-widget .profile-widget-items:after {content: ' ';position: absolute;bottom: 0;left: 0px;right: 0;height: 1px;background-color: #f2f2f2;}
  .profile-widget .profile-widget-items:before {content: ' ';position: absolute;top: 0;left: 0px;right: 0;height: 1px;background-color: #f2f2f2;}
  .profile-widget .profile-widget-items .profile-widget-item {flex: 1;text-align: center;padding: 10px 0;}
  .article .article-header {overflow: unset !important;}
  .description_info {padding: 20px;line-height: 17px;font-size: 13px;margin: 0;}
  .option_dropdown {position: absolute;top: 0;left: 0;height: 20px;width: 22px;background-color: #f7fefe;border-radius: 24%;padding-top: 0px;margin-top: 3px;margin-left: 3px;border: 1px solid #4e6e7e;}
  .video_option_background{position: absolute;height: 60px;width: 60px;top: 0;left: 0;}
  .selectric .label {min-height: 0 !important;}
  .opt_btn{border-radius: 30px !important;padding-left: 25px !important;padding-right: 25px !important;}
  .generic_message_block textarea{height: 100px !important;}
  .filter_message_block textarea{height: 100px !important;margin-bottom: 30px;}
  .single_card .card-body .form-group{margin-bottom: 10px;}
  .single_card .card-body{padding-bottom: 0 !important;}
  .bootstrap-tagsinput{height: 100px !important;}
  .profile-widget .profile-widget-items .profile-widget-item .profile-widget-item-value {font-weight: 300;font-size: 13px;
  }
  .padding-0{padding: 0px;}
  .bck_clr{background: #ffffff!important;}
  .mt-30{margin-top: 30px!important;}
  .mt-66{margin-top: 160px!important;}
  .ajax-file-upload{
      bottom: 12px;
  }
</style>




<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-tags"></i> <?php echo $page_title;?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url('menu_loader/utlities') ?>"><?php echo $this->lang->line("Utilities");?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title;?></div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-4 col-lg-4 collef">
    <div class="card main_card">
        

        <div class="card-header">
          <h4><i class="fas fa-tags"></i> <?php echo $this->lang->line('Metatag Generator List'); ?></h4>
        </div>
        <div class="card-body">

          <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="google_check_box" value="1" name="google_check_box">
                <label class="custom-control-label" for="google_check_box"><?php echo $this->lang->line('Google'); ?></label>
              </div>
          </div>
         
          <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="facebook_check_box" value="1" name="facebook_check_box">
                <label class="custom-control-label" for="facebook_check_box"><?php echo $this->lang->line('Facebook'); ?></label>
              </div>
          </div>
        
        <div class="form-group">
          <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="twiter_check_box" value="1" name="twiter_check_box">
              <label class="custom-control-label" for="twiter_check_box"><?php echo $this->lang->line('Twitter'); ?></label>
            </div>
        </div>

        </div>

        <div class="card-footer bg-whitesmoke mt-66">

            <button type="button"  id="new_search_button" class="btn btn-primary "><i class="fa fa-code"></i> <?php echo $this->lang->line("Generate"); ?></button>
            
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('menu_loader/utlities')" type="button"><i class="fa fa-remove"></i> <?php echo $this->lang->line('Cancel'); ?></button>

    

        </div>
    </div>          
  </div>

  <div class="col-12 col-md-8 col-lg-8 colmid">
    <div id="custom_spinner"></div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fas fa-tags"></i> <?php echo $this->lang->line('Metatag Forms'); ?></h4>

        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

       <div class="empty-state" id="show_hide">
          <img class="img-fluid" src="<?php echo base_url("assets/img/drawkit/revenue-graph-colour.svg"); ?>" style="height: 300px" src=" " alt="image">
        </div> 

        <div class="card" id="google_block" style="display: none;">
          <div class="card-header">
            <h4><?php echo $this->lang->line('Google Metatag Form'); ?></h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label><?php echo $this->lang->line('Description'); ?></label>
              <textarea class="form-control" id="google_description" name="google_description"><?php echo set_value('google_description');?></textarea>
              <code><?php echo form_error('google_description'); ?></code>
            </div>
            <div class="form-group">
              <label><?php echo $this->lang->line('Keywords'); ?></label>
                <input type="text" name="google_keywords" class="form-control" id="google_keywords" value="<?php echo set_value('google_keywords');?>">
                <code><?php echo form_error('google_keywords'); ?></code>
            </div>            

            <div class="form-group">
              <label><?php echo $this->lang->line('Author'); ?></label>
                <input type="text" name="google_author" class="form-control" id="google_author" value="<?php echo set_value('google_author');?>">
                <code><?php echo form_error('google_author'); ?></code>
            </div>            
            <div class="form-group">
              <label><?php echo $this->lang->line('Copyright'); ?></label>
                <input type="text" name="google_copyright" class="form-control" id="google_copyright" value="<?php echo set_value('google_copyright');?>">
                <code><?php echo form_error('google_copyright'); ?></code>
            </div>           
             <div class="form-group">
              <label><?php echo $this->lang->line('Application Name'); ?></label>
                <input type="text" name="google_application_name" class="form-control" id="google_application_name" value="<?php echo set_value('google_application_name');?>">
                <code><?php echo form_error('google_application_name'); ?></code>
            </div>


          </div>
        </div>


        <div class="card" id="facebook_block" style="display: none;">
          <div class="card-header">
            <h4><?php echo $this->lang->line('Facebook Metatag Form'); ?></h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label><?php echo $this->lang->line('Title'); ?></label>
                <input type="text" name="facebook_title" class="form-control" id="facebook_title" value="<?php echo set_value('facebook_title');?>">
                <code><?php echo form_error('facebook_title'); ?></code>
            </div> 
            <div class="form-group">
              <label><?php echo $this->lang->line('Description'); ?></label>
              <textarea class="form-control" id="facebook_description" name="facebook_description"><?php echo set_value('facebook_description');?></textarea>
              <code><?php echo form_error('facebook_description'); ?></code>
            </div>
           

            <div class="form-group">
              <label><?php echo $this->lang->line('Type'); ?></label>
                <input type="text" name="facebook_type" class="form-control" id="facebook_type" value="<?php echo set_value('facebook_type');?>">
                <code><?php echo form_error('facebook_type'); ?></code>
            </div>            
            <div class="form-group">
              <label><?php echo $this->lang->line('Image URL'); ?></label>
                <input type="text" name="facebook_image" class="form-control" id="facebook_image" value="<?php echo set_value('facebook_image');?>">
                <code><?php echo form_error('facebook_image'); ?></code>
            </div>           
             <div class="form-group">
              <label><?php echo $this->lang->line('Page URL'); ?></label>
                <input type="text" name="facebook_url" class="form-control" id="facebook_url" value="<?php echo set_value('facebook_url');?>">
                <code><?php echo form_error('facebook_url'); ?></code>
            </div>             
            <div class="form-group">
              <label><?php echo $this->lang->line('Facebook App ID'); ?></label>
                <input type="text" name="facebook_app_id" class="form-control" id="facebook_app_id" value="<?php echo set_value('facebook_app_id');?>">
                <code><?php echo form_error('facebook_app_id'); ?></code>
            </div>

            <div class="form-group">
              <label><?php echo $this->lang->line('Localization'); ?></label>
                <input type="text" name="facebook_localization" class="form-control" id="facebook_localization" value="<?php echo set_value('facebook_localization');?>">
                <code><?php echo form_error('facebook_localization'); ?></code>
            </div>


          </div>
        </div>
        
        <div class="card" id="twiter_block" style="display: none;">
          <div class="card-header">
            <h4><?php echo $this->lang->line('Twitter Metatag Form'); ?></h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label><?php echo $this->lang->line('Card'); ?></label>
                <input type="text" name="twiter_card" class="form-control" id="twiter_card" value="<?php echo set_value('twiter_card');?>">
                <code><?php echo form_error('twiter_card'); ?></code>
            </div>            
            <div class="form-group">
              <label><?php echo $this->lang->line('Title'); ?></label>
                <input type="text" name="twiter_title" class="form-control" id="twiter_title" value="<?php echo set_value('twiter_title');?>">
                <code><?php echo form_error('twiter_title'); ?></code>
            </div>            
            <div class="form-group">
              <label><?php echo $this->lang->line('Description'); ?></label>
              <textarea class="form-control" id="twiter_description" name="twiter_description"><?php echo set_value('twiter_description');?></textarea>
              <code><?php echo form_error('twiter_description'); ?></code>
            </div>

       
             <div class="form-group">
              <label><?php echo $this->lang->line('Image URL'); ?></label>
                <input type="text" name="twiter_image" class="form-control" id="twiter_image" value="<?php echo set_value('twiter_image');?>">
                <code><?php echo form_error('twiter_image'); ?></code>
            </div>


          </div>
        </div>


      </div>
    </div>
  </div>
</div>





<script type="text/javascript">

  var is_google = 0;
  var is_twiter = 0;
  var is_facebook = 0;

  $("document").ready(function(){
    var base_url="<?php echo site_url(); ?>";


    $(document).on('change','#google_check_box',function(event){
      event.preventDefault();
     
     if($('input[name=google_check_box]').prop('checked')){
         is_google = 1;
        $('#google_block').slideDown(500);
        $('#show_hide').hide();
     }

     else{
        is_google = 0;
        $('#google_block').slideUp(500);
      }

    });    
    $(document).on('change','#facebook_check_box',function(event){
      event.preventDefault();
     
     if($('input[name=facebook_check_box]').prop('checked')){
        is_facebook = 1;
         $('#show_hide').hide();
        $('#facebook_block').slideDown(500);

     }

     else{
       is_facebook = 0;
        $('#facebook_block').slideUp(500);
     }

    });    
    $(document).on('change','#twiter_check_box',function(event){
      event.preventDefault();
     
     if($('input[name=twiter_check_box]').prop('checked')){
        is_twiter = 1;
         $('#show_hide').hide();
        $('#twiter_block').slideDown(500);
     }

    else{
        is_twiter = 0;
        $('#twiter_block').slideUp(500);
    }

    });

    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

        var base_url="<?php echo base_url(); ?>";
        var base64=$("#base64").val();
        
        if(is_google ==0 && is_twiter ==0 && is_facebook ==0){
          swal("<?php echo $this->lang->line('Error'); ?>", "<?php echo $this->lang->line('One or more required fields are missing'); ?>", 'error');
          return false;
        }
         $("#set_auto_comment_templete_modal").modal();
        $("#new_search_button").addClass('btn-progress');
        //$("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
        
       
        $.ajax({
          url:base_url+'tools/meta_tag_action',
          type:'POST',
          data:{is_google:is_google,
            is_facebook:is_facebook,
            is_twiter:is_twiter,
            google_description:$("#google_description").val(),          
            google_keywords:$("#google_keywords").val(),          
            google_copyright:$("#google_copyright").val(),          
            google_author:$("#google_author").val(),          
            google_application_name:$("#google_application_name").val(),          
            facebook_title:$("#facebook_title").val(),          
            facebook_type:$("#facebook_type").val(),          
            facebook_image:$("#facebook_image").val(),          
            facebook_url:$("#facebook_url").val(),          
            facebook_description:$("#facebook_description").val(),          
            facebook_app_id:$("#facebook_app_id").val(),          
            facebook_localization:$("#facebook_localization").val(),          
            twiter_card:$("#twiter_card").val(),          
            twiter_title:$("#twiter_title").val(),          
            twiter_description:$("#twiter_description").val(),          
            twiter_image:$("#twiter_image").val() },
          success:function(response){  
            
            $("#new_search_button").removeClass('btn-progress');
           $("#unique_email_download_div").html(' <p><?php echo $this->lang->line('Your file is ready download'); ?></p><a href="<?php echo base_url()."download/metatag/metatag_{$this->user_id}_{$this->download_id}.txt" ?>" target="_blank" class="btn btn-primary"><i class="fas fa-download"></i> <b><?php echo $this->lang->line("download"); ?></b></a>');
            
            $("#success_msg").html(response);
          
            
          }
          
        });
        
    });



  });  

</script>

<div class="modal fade show" id="set_auto_comment_templete_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #fefefe;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-tags"></i> <?php echo $this->lang->line('Metatag Generated'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      
      <div class="modal-body text-center" id="unique_email_download_div"> 
       
      </div>
      
    </div>
  </div>
</div>
