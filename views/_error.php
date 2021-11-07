<?php 
/** @var $exception \Exception */


?>

<!-- Exception view -->
<h1>Error <?php echo $exception->getCode(); ?></h1>
<p><?php echo $exception->getMessage(); ?></p>
<pre><?php echo $exception->getTraceAsString(); ?></pre>
