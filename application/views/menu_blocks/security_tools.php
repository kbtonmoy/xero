<style>.no_hover{text-decoration: none !important;}</style>
<section class="section">
  <div class="section-header">
    <h1><i class="fa fa-shield"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fab fa-typo3"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Malware Scan"); ?></h4>
            <p><?php echo $this->lang->line("Scan any websites’ malware status"); ?></p>
            <a href="<?php echo base_url("antivirus/scan"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fab fa-css3-alt"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("VirusTotal Scan"); ?></h4>
            <p><?php echo $this->lang->line("scan in 67 different places."); ?></p>
            <a href="<?php echo base_url("antivirus/virus_total"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>


    </div>
  </div>
</section>