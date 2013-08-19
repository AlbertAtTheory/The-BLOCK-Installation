<article id="article-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
	<?php print $unpublished; ?>
	
	<?php print render($title_prefix); ?>
	<?php if ($title && !$page): ?>
	<header>
		<?php if ($title): ?>
		<h1<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a></h1>
		<?php endif; ?>
	</header>
	<?php endif; ?>
	<?php print render($title_suffix); ?>
	
	<?php if ($display_submitted): ?>
	<footer class="submitted<?php $user_picture ? print ' with-user-picture' : ''; ?>">
		<?php print $user_picture; ?>
		<p class="author-datetime"><?php print $submitted; ?></p>
	</footer>
	
	<div class="toolbar-social">
		<div id="twitter" data-url="http://theblock.com<?php print $node_url; ?>" data-text="<?php print $title; ?>"></div>
		<div id="facebook" data-url="http://theblock.com<?php print $node_url; ?>" data-text="<?php print $title; ?>"></div>
		<div id="googleplus" data-url="http://theblock.com<?php print $node_url; ?>" data-text="<?php print $title; ?>"></div>
	</div>
	
	<?php print render($article_side); ?>
	<?php endif; ?>
	
	<?php global $user; ?>
	<?php if ($type == 'engines') { ?><a href="/garage/<?php print $user->uid; ?>/install/engine/<?php print $vid; ?>" class="block-button-ui installbutton" title="Install this Engine in your vehicle">Install in My Vehicle</a><?php } ?>
	<?php if ($type == 'parts') { ?><a href="/garage/<?php print $user->uid; ?>/install/part/<?php print $vid; ?>" class="block-button-ui installbutton" title="Install this Part in your vehicle">Install in My Vehicle</a><?php } ?>
	
	<div<?php print $content_attributes; ?>>
		<?php
			hide($content['comments']);
			hide($content['links']);
			print render($content);
		?>
	</div>
	
	<?php print render($content_aside); ?>
	
	<?php if ($links = render($content['links'])): ?>
	<nav class="clearfix"><?php print $links; ?></nav>
	<?php endif; ?>
	
	<?php print render($content['comments']); ?>
	
</article>