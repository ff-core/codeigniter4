<?= $this->extend('layout/bootstrap') ?>

<?= $this->section('content') ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div id="regionTable">
      <?php
        if(isset($data))
          print_r($data); 
      ?>
      </div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('style-meu') ?>
  <link href="<?= base_url('/assets/vendor/datatables/extensions/jquery.dataTables.css') ?>" rel="stylesheet">
  <script>
    <?php
      $p = "";
      foreach ($params as $key => $value) {
        $p .= $key == 0 ? "$value" : "/$value";
      }
    ?>
    var base_url = '<?= base_url($controller.'/'.$function.'/'.$p) ?>';
    var alias = '<?= isset($alias) ? $alias : "" ?>';
  </script>
<?= $this->endSection() ?>

<?= $this->section('script-meu') ?>
<div id="myError"></div>
  <script src="<?= base_url('/assets/vendor/datatables/jquery.dataTables.min.js') ?>" ></script>
  
  <script>
  $(document).ready( function () {
      var table = $('#datatable-primary').DataTable();
  } );
  </script>

  <script src="<?= base_url('/assets/dist/js/custom/cadastros.js') ?>" ></script>
<?= $this->endSection() ?>