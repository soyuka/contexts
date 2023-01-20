<?php

error_reporting(\E_ALL);

header('Date: '.date('c'));
header('Expires: '.date('c', time() + 60));
header('Content-Type: text/html; charset=utf-8');

$body = file_get_contents('php://input');
if ('PUT' === $_SERVER['REQUEST_METHOD'] && !empty($body)) {
    // For PUT with body we simulate an empty response with 204
    header('HTTP/1.0 204 No Content');
    exit;
}

?>

You have sent a <?php echo $_SERVER['REQUEST_METHOD']; ?> request.

<?php echo count($_SERVER); ?> header(s) received.
<?php foreach (array_filter($_SERVER) as $key => $value) { ?>
  <br /><?php echo $key; ?> : <?php echo $value; ?>
<?php } ?>

<?php if (0 == count($_REQUEST)) { ?>
  <br />No parameter received.
<?php } else { ?>
  <br /><?php echo count($_REQUEST); ?> parameter(s) received.
  <?php foreach ($_REQUEST as $key => $value) { ?>
    <br /><?php echo $key; ?> : <?php echo $value; ?>
  <?php } ?>
<?php } ?>

<?php if (0 == count($_FILES)) { ?>
  <br />No files received.
<?php } else { ?>
  <br /><?php echo count($_FILES); ?> file(s) received.
  <?php foreach ($_FILES as $key => $value) { ?>
    <br /><?php echo $key; ?> - name : <?php echo $value['name']; ?>
    <br /><?php echo $key; ?> - error : <?php echo $value['error']; ?>
    <br /><?php echo $key; ?> - size : <?php echo $value['size']; ?>
  <?php } ?>
<?php } ?>

<?php if (null == $body) { ?>
  <br />No body received.
<?php } else { ?>
  <br />Body : <?php echo $body; ?>
<?php } ?>
