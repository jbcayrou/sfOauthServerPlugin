<?php use_helper('I18N'); ?>

<h1>Application : <?php echo $application->getName(); ?> </h1>
  <?php if ($isDeveloper): ?>
    <?php echo __('<p> <b>Your Public key </b> : %key% <br /> <b>Your Private key</b> %secret% </p>',array('%key%' => $application->getConsumerKey(),'%secret%' => $application->getConsumerSecret())); ?> 
   <?php endif; ?>
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
    <?php foreach ($application->getDevelopers() as $developer): ?>
      <li><?php echo $developer; ?></li>
    <?php endforeach; ?>
  <ol/>
</p>
<br/>
<?php if ($isDeveloper): ?>
   <?php echo link_to(__('Edit'), 'sfOauthDeveloper/edit?id='.$application->getId()); ?>
<?php endif; ?>
