<?php

/** @var $exception \Exception */
?>
<h3 class="alert text-danger alert-danger text-center"><?= $exception->getCode() . ' - ' . $exception->getMessage()?></h3>