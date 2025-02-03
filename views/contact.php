<?php
/**
 * @var $model \app\models\ContactForm
 */
?>
<h1>Contact</h1>
<?php $form = \app\core\form\Form::begin('contact','post') ?>
  <?= new \app\core\form\InputField($model,'subject') ?>
  <?= new \app\core\form\InputField($model,'email') ?>
  <?= new \app\core\form\TextareaField($model,'body') ?>
  <button type="submit" class="btn btn-primary">Submit</button>
<?= \app\core\form\Form::end() ?>
