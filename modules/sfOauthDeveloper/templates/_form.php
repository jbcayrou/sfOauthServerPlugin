<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('sfOauthDeveloper/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('sfOauthDeveloper/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'sfOauthDeveloper/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['description']->renderLabel() ?></th>
        <td>
          <?php echo $form['description']->renderError() ?>
          <?php echo $form['description'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['protocole']->renderLabel() ?></th>
        <td>
          <?php echo $form['protocole']->renderError() ?>
          <?php echo $form['protocole'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['base_domain']->renderLabel() ?></th>
        <td>
          <?php echo $form['base_domain']->renderError() ?>
          <?php echo $form['base_domain'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['callback']->renderLabel() ?></th>
        <td>
          <?php echo $form['callback']->renderError() ?>
          <?php echo $form['callback'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['scope']->renderLabel() ?></th>
        <td>
          <?php echo $form['scope']->renderError() ?>
          <?php echo $form['scope'] ?>
        </td>
      </tr>
    <?php if (!$form->getObject()->isNew()) : ?>
      <tr>
        <th><?php echo $form['developers_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['developers_list']->renderError() ?>
          <?php echo $form['developers_list'] ?>
        </td>
      </tr>
	 <?php endif; ?>
    </tbody>
  </table>
</form>
