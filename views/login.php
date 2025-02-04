<?php
/** @var $model \app\models\User */
?>
<h1>Login</h1>
<?php app\core\form\Form::begin('/login', 'post') ?>
<?php echo new app\core\form\InputField($model, 'email'); ?>
<?php echo (new app\core\form\InputField($model, 'password'))->setType('password'); ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?= app\core\form\Form::end() ?>