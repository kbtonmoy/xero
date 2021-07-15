 <style type="text/css">.no_hover:hover{text-decoration: none;}</style>
 <section class="section">
  <div class="section-header">
    <h1><i class="fas fa-ellipsis-h"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(13,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-at"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Email Encoder/Decoder"); ?></h4>
            <p><?php echo $this->lang->line("Email Encode, Decode, Csv Download"); ?></p>
            <a href="<?php echo base_url("tools/email_encoder_decoder"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-tags"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Metatag Generator"); ?></h4>
            <p><?php echo $this->lang->line("Metatag Generator Facebook, Google, Twitter"); ?></p>
            <a href="<?php echo base_url("tools/meta_tag_list"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(12,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-language"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Plagiarism Check"); ?></h4>
            <p><?php echo $this->lang->line("Plagiarism Checker, files"); ?></p>
            <a href="<?php echo base_url("tools/plagarism_check_list"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div> 
      <?php endif; ?>
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(13,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Valid Email Check"); ?></h4>
            <p><?php echo $this->lang->line("Email Checker, Files"); ?></p>
            <a href="<?php echo base_url("tools/index"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-envelope-square"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Duplicate Email Filter"); ?></h4>
            <p><?php echo $this->lang->line("Email Filter, Files"); ?></p>
            <a href="<?php echo base_url("tools/duplicate_email_filter_list"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>


      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-link"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("URL Encoder/Decoder"); ?></h4>
            <p><?php echo $this->lang->line("Link Encode, Decode"); ?></p>
            <a href="<?php echo base_url("tools/url_encode_list"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>      
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-external-link-square-alt"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("URL Canonical Check"); ?></h4>
            <p><?php echo $this->lang->line("URL Canonical Checker, Files"); ?></p>
            <a href="<?php echo base_url("tools/url_canonical_check"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>      
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa fa-file-archive"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Gzip Check"); ?></h4>
            <p><?php echo $this->lang->line("Gzip Checker, files"); ?></p>
            <a href="<?php echo base_url("tools/gzip_check"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>      
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fab fa-centercode"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Base64 Encoder/Decoder"); ?></h4>
            <p><?php echo $this->lang->line("Base64 Encode, Decode, files"); ?></p>
            <a href="<?php echo base_url("tools/base64_encode_list"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>      
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-file-code"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Robot Code Generator"); ?></h4>
            <p><?php echo $this->lang->line("Code Generator, files"); ?></p>
            <a href="<?php echo base_url("tools/robot_code_generator"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?>


    </div>
  </div>
</section>

