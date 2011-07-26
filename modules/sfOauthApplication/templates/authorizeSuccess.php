<?php use_helper('I18N'); ?>

<?php
slot('title', __("Autorisation d'application"));
?>

<div id="content">
	<div class="module">
	
		<form method="post" action="<?php echo url_for('application/authorize'); ?>">
			<?php if ($consumer->getProtocole()==1) : ?>
			<input type="hidden" name="oauth_callback" value="<?php echo $callback; ?>" />
			<input type="hidden" name="oauth_token" value="<?php echo $token; ?>" />
	
			<?php elseif ($consumer->getProtocole()==2) : ?>
			<input type="hidden" name="redirect_uri" value="<?php echo $redirect_uri; ?>" />
			<input type="hidden" name="response_type" value="code" />
			<?php endif; ?>
			<input type="hidden" name="client_id" value="<?php echo $consumer->getConsumerKey(); ?>" />
		  Voulez-vous autoriser l'application : <?php echo $consumer->getName(); ?> a accéder à vos données ?
		  <p>
		  <p>Voici les permissions : <?php echo $consumer->getScope(); ?></p>
			<input type="submit" name="accept" value="Yes" />
			<input type="submit" name="accept" value="No" />
		  </p>
		</form>
	</div>
</div>
