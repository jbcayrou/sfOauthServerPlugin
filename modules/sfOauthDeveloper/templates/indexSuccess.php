<h1>Your applications<h2>
<?php foreach ($applications as $application)
echo '<a href="'.url_for('sfOauthDeveloper/show?id='.$application->getId()).'">'.$application->getName().'</a><br/>';
?>
<br/>
<a href="<?php echo url_for('sfOauthDeveloper/new') ?>">create a new application</a>
