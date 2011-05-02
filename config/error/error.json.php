<?php echo json_encode(array(
  'error'       => array(
    'type'      => get_class($exception),
    'message'   => $exception->getMessage(),
))) ?>
