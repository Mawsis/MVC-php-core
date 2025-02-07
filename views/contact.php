<?php

/**
 * @var $formData \app\models\ContactForm
 */
?>
<h1>Contact</h1>
<?php $form = \app\form\Form::begin('contact', 'post') ?>
<?= new \app\form\InputField($formData, 'subject') ?>
<?= new \app\form\InputField($formData, 'email') ?>
<?= new \app\form\TextareaField($formData, 'body') ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?= \app\form\Form::end() ?>