<?php $__env->startSection('content'); ?>
<script src="<?php echo e(asset('public/js/login.js')); ?>"></script>
<div class="container mt-8 pb-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      <div class="card red-shadow border-0">
        <div class="card-body px-lg-5 py-lg-5">
          <div class="text-center text-muted mb-4">
            <img src="<?php echo e(asset('public/images/favicon.png')); ?>">
          </div>
          <?php echo csrf_field(); ?>
          <div class="form-group<?php echo e($errors->has('email') ? ' has-danger' : ''); ?> mb-3">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-email-83 app-text-default"></i></span>
              </div>
              <input id="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Email')); ?>" type="" name="email" value="<?php echo e(old('email')); ?>" required>
            </div>
            <?php if($errors->has('email')): ?>
            <span class="invalid-feedback" style="display: block;" role="alert">
            <strong><?php echo e($errors->first('email')); ?></strong>
            </span>
            <?php endif; ?>
          </div>
          <div class="form-group<?php echo e($errors->has('password') ? ' has-danger' : ''); ?>">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-lock-circle-open app-text-default"></i></span>
              </div>
              <input id="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" placeholder="<?php echo e(__('Password')); ?>" type="password" required>
            </div>
            <?php if($errors->has('password')): ?>
            <span class="invalid-feedback" style="display: block;" role="alert">
            <strong><?php echo e($errors->first('password')); ?></strong>
            </span>
            <?php endif; ?>
          </div>
          <!-- <div class="custom-control custom-control-alternative custom-checkbox">
            <input class="custom-control-input" name="remember" id="customCheckLogin" type="checkbox" <?php echo e(old('remember') ? 'checked' : ''); ?>>
            <label class="custom-control-label" for="customCheckLogin">
                <span class="text-muted"><?php echo e(__('Remember me')); ?></span>
            </label>
            </div> -->
          <div class="text-center">
            <button id="but_submit" class="btn app-btn-primary my-4"><?php echo e(__('Sign in')); ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['class' => 'bg-img', 'login' => false], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>