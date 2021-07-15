<style>.no_hover{text-decoration: none !important;}</style>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-link"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-vector-square"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Google Backlink Search"); ?></h4>
            <p><?php echo $this->lang->line("Google backlink search any domains "); ?></p>
            <a href="<?php echo base_url("backlink/backlink_search"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-paperclip"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Backlink Generator"); ?></h4>
            <p><?php echo $this->lang->line("Back Link generator for any domains"); ?></p>
            <a href="<?php echo base_url("backlink/index"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>      

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
           <i class="fas fa-anchor"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Website Ping"); ?></h4>
            <p><?php echo $this->lang->line("Ping for any domains"); ?></p>
            <a href="<?php echo base_url("ping/index"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>


    </div>
  </div>
</section>