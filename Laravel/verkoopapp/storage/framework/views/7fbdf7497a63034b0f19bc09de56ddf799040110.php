<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Verkoop')); ?></title>
    <!-- Scripts -->
    <script src="<?php echo e(asset('public/js/app.js')); ?>" defer></script>
    <!-- <script src="<?php echo e(asset('public/js/jquery.js')); ?>"></script> --><script src="http://code.jquery.com/jquery-1.9.1.js"></script>

    <script src="<?php echo e(asset('public/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <!-- <script src="<?php echo e(asset('/public/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')); ?>"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script> -->

    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="<?php echo e(asset('public/js/common-service.js')); ?>"></script>
    <script src="<?php echo e(asset('public/js/sweetalert.min.js')); ?>"></script>
    <script>
      var base_url = "<?= url('/').'/'; ?>";
    </script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> -->
    <!-- Favicon -->
    <link href="<?php echo e(asset('public')); ?>/images/favicon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="<?php echo e(asset('public')); ?>/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="<?php echo e(asset('public')); ?>/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="<?php echo e(asset('public')); ?>/css/argon.css?v=1.0.0" rel="stylesheet">
    <link type="" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <!-- Styles -->
    <!-- <link href="<?php echo e(asset('public/css/app.css')); ?>" rel="stylesheet"> -->
    <link href="<?php echo e(asset('public/css/style.css')); ?>" rel="stylesheet">
  </head>
  <body class="<?php echo e($class ?? ''); ?>">
    <div id="app" class="main-content">
        <?php if($login ?? true): ?>
          <?php echo $__env->make('layouts.navbars.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>
      <div class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
      </div>
    </div>
    <script src="<?php echo e(asset('public')); ?>/js/argon.min.js?v=1.0.0"></script>
  </body>
</html>
