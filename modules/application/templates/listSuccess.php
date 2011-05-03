<h1>Applications that you have accepted</h1>
<?php
foreach($applications as $application)
{
  echo $application->getConsumer()->getName().', its permissions :'.$application->getScope();
  echo link_to('delete','application/delete?id='.$application->getId());
  echo '<br />';
}
?>
