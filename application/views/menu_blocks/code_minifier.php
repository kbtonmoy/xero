<style>.no_hover{text-decoration: none !important;}</style>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-cogs"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fab fa-html5"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("HTML Minifier"); ?></h4>
            <p><?php echo $this->lang->line("Minified HTML Files."); ?></p>
            <a href="<?php echo base_url("code_minifier/html_minifier"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fab fa-css3-alt"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("CSS Minifier"); ?></h4>
            <p><?php echo $this->lang->line("Minified CSS Files."); ?></p>
            <a href="<?php echo base_url("code_minifier/css_minifier"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fab fa-js"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("JS Minifier"); ?></h4>
            <p><?php echo $this->lang->line("Minified JS Files."); ?></p>
            <a href="<?php echo base_url("code_minifier/js_minifier"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>


    </div>
  </div>
</section>