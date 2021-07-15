 <section class="section">
  <div class="section-header">
    <h1><i class="fas fa-hands-helping"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>


    <div class="section-body">
      <div class="row">
        <?php if ($this->session->userdata('user_type') != 'Member'): ?>
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-facebook"></i>
            </div>

            <div class="card-body">
              <h4><?php echo $this->lang->line("Facebook"); ?></h4>
              <p><?php echo $this->lang->line("Set your Facebook app key, secret etc..."); ?></p>
              <a href="<?php echo base_url("social_apps/add_facebook_settings"); ?>" class="card-cta"><?php echo $this->lang->line("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-google"></i>
            </div>
            <div class="card-body">
              <h4><?php echo $this->lang->line("Google"); ?></h4>
              <p><?php echo $this->lang->line("Set your Google app key, secret etc..."); ?></p>
              <a href="<?php echo base_url("social_apps/google_settings"); ?>" class="card-cta"><?php echo $this->lang->line("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
      <?php endif; ?>
        
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-connectdevelop"></i>
            </div>
            <div class="card-body">
              <h4><?php echo $this->lang->line("Connectivity Settings"); ?></h4>
              <p><?php echo $this->lang->line("Set All kind of Connectivity APIs..."); ?></p>
              <a href="<?php echo base_url("social_apps/connectivity_settings"); ?>" class="card-cta">
                <?php echo $this->lang->line("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>    

        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fas fa-user-secret"></i>
            </div>
            <div class="card-body">
              <h4><?php echo $this->lang->line("Proxy Settings"); ?></h4>
              <p><?php echo $this->lang->line("Set Proxies to use features."); ?></p>
              <a href="<?php echo base_url("social_apps/proxy_settings"); ?>" class="card-cta">
                <?php echo $this->lang->line("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>