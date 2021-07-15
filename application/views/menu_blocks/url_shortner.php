<style>.no_hover{text-decoration: none !important;}</style>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-cut"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">

      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(18,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fab fa-bity"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Bitly Url shortener"); ?></h4>
            <p><?php echo $this->lang->line("Shorten, share and track your shortened URLs"); ?></p>
            <a href="<?php echo base_url("url_shortener/index"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fab fa-bandcamp"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Rebrandly"); ?></h4>
            <p><?php echo $this->lang->line("Shorten, share and track your shortened URLs"); ?></p>
            <a href="<?php echo base_url("url_shortener/rebrandly_shortener"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>