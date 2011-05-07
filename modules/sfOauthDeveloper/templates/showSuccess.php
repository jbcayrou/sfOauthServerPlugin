<h1>Application : <?php echo $application->getName(); ?> </h1>

 <?php
if ($isDeveloper)
  echo __('<p> <b>Your Public key </b> : %key% <br /> <b>Your Private key</b> %secret% </p>',array('%key%' => $application->getConsumerKey(),'%secret%' => $application->getConsumerSecret())); 
?>
 
<p>
Description : <br />
<?php echo $application->getDescription(); ?>
</p>

<p>
Number of users : <?php echo $application->getNumberUsers(); ?>
</p>

<p>
<b>Developers</b>
<ol>
<?php foreach ($application->getDevelopers() as $developer)
echo '<li>'.$developer.'</li>';
?>
<ol/>
</p>
<br/>
<?php if ($isDeveloper)
   echo '<a href='.url_for('sfOauthDeveloper/edit?id='.$application->getId()).'>Edit</a>';
?>
