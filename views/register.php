<h1>Register</h1>
<?php $form = app\core\form\Form::begin('/register','post') ?>
  <?php echo new app\core\form\InputField($model,'username'); ?>
  <?php echo new app\core\form\InputField($model,'email'); ?>
  <?php echo (new app\core\form\InputField($model,'password'))->setType('password'); ?>
  <?php echo (new app\core\form\InputField($model,'confirmPassword'))->setType('password'); ?>
  <button type="submit" class="btn btn-primary">Submit</button>
<?= app\core\form\Form::end() ?>
