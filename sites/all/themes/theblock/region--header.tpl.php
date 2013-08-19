<?php
//<div id="beta-button">
//<a href="/forums/beta-talk" class="block-button-ui">BETA</a>
//</div>
?>
<?php if ($content): ?>
	<div class="<?php print $classes; ?>">
		<?php if ($logged_in): ?>
		<div id="user-logged-in-menu">
			<a href="/user" title="View my profile"><?php
				global $user;
				$user = user_load($user->uid);
				if (isset($user->picture)) {
					print theme('image_style', array(
						'style_name' => '20x20',
						'path' => $user->picture->uri,
						'attributes' => array('class' => 'avatar')
					));
				} else {
			?><img src="/sites/all/themes/theblock/images/icon-helmet.png" class="avatar" style="width: 20px; height: 20px;" /><?php } ?></a>
			<a href="/qr" class="toolbar myqrcodes" title="My QR codes">QR Codes</a>
			<a href="/messages" class="toolbar mymessages<?php if (privatemsg_unread_count()>=1) { ?> active<?php } ?>" title="Private messages" /><?php print privatemsg_unread_count(); ?></a>
			<a href="/user/<?php print $user->uid; ?>/edit" class="toolbar mysettings" title="Edit my profile">Settings</a>
		</div>
		<?php endif; ?>
		<?php print $content; ?>
	</div>
<?php endif; ?>