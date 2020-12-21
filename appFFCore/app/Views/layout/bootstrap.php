<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>Dashboard Template · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
  
    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('/assets/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <?= $this->renderSection('style-meu') ?>

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="<?= base_url('/assets/dist/css/custom/dashboard.css') ?>" rel="stylesheet">
  </head>
  <body>
    
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">ff-Core</a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="#">Sign out</a>
        </li>
      </ul>
    </header>

    <div class="container-fluid">
      <div class="row">
        
      <nav id="sidebarMenu" class="bd-aside col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <ul class="list-unstyled">
          <li class="my-2">
            <button class="btn d-inline-flex align-items-center collapsed" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#menuEscritorio" aria-controls="menuEscritorio">Escritórios</button>
            <ul class="list-unstyled ps-3 collapse" id="menuEscritorio">
              <li><a class="d-inline-flex align-items-center rounded" href="<?= base_url("Exemples/Escritorios") ?>">Escritórios</a></li>
            </ul>
          </li>

          <li class="my-2">
            <button class="btn d-inline-flex align-items-center collapsed" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#menuFilme" aria-controls="menuFilme">Filmes</button>
            <ul class="list-unstyled ps-3 collapse" id="menuFilme">
              <li><a class="d-inline-flex align-items-center rounded" href="<?= base_url("Exemples/Filmes") ?>">Filmes</a></li>
              <li><a class="d-inline-flex align-items-center rounded" href="<?= base_url("Exemples/Atores") ?>">Atores</a></li>
              <li><a class="d-inline-flex align-items-center rounded" href="<?= base_url("Exemples/FilmeAtores") ?>">Filmes por Atores</a></li>
            </ul>
          </li>

          <?php if(isset($menus)) : ?>
          <?php foreach ($menus as $key => $menu) : ?>
          <li class="my-2">
            <button class="btn d-inline-flex align-items-center collapsed" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#menu<?= $menu->me_id ?>" aria-controls="menu<?= $menu->me_id ?>"><?= $menu->me_nome ?></button>
            <ul class="list-unstyled ps-3 collapse" id="menu<?= $menu->me_id ?>">
              <?php foreach ($menu->submenus as $key => $submneu) : ?>
              <li><a class="d-inline-flex align-items-center rounded" href="<?= base_url("Home/Index/".$submneu->sm_slug) ?>"><?= $submneu->sm_nome ?></a></li>
              <?php endforeach; ?>
            </ul>
          </li>
          <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </nav>
      
        <?= $this->renderSection('content') ?>
        
      </div>
    </div>

    <script src="<?= base_url('/assets/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>

    <?= $this->renderSection('script-meu') ?>
  </body>
</html>
