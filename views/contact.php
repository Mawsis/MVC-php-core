<?php

/**
 * @var $model \app\models\ContactForm
 */
?>
<h1>Contact</h1>
<?php $form = \app\form\Form::begin('contact', 'post') ?>
<?= new \app\form\InputField($model, 'subject') ?>
<?= new \app\form\InputField($model, 'email') ?>
<?= new \app\form\TextareaField($model, 'body') ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?= \app\form\Form::end() ?>