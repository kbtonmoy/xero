<style>.no_hover{text-decoration: none !important;}</style>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-map-marker-alt"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-cogs"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Tracking Settings"); ?></h4>
            <p><?php echo $this->lang->line("Shorten, share and track your shortened URLs"); ?></p>
            <a href="<?php echo base_url("keyword_position_tracking/index"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-bars"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Position Report"); ?></h4>
            <p><?php echo $this->lang->line("Analytics of shortened URL."); ?></p>
            <a href="<?php echo base_url("keyword_position_tracking/keyword_position_report"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>