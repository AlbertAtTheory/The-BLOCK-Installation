<div id="pagedimmer"></div>
<div id="page-wrapper" class="<?php if (arg(0) == 'messages') { print 'privatemessages'; } else { print implode(" ", $variables['extra_classes']); } ?>"><div id="page">
<?php if($page['leaderboard']): ?>
	<div id="leaderboard-wrapper"><div class="container clearfix">
		<?php print render($page['leaderboard']); ?>
	</div></div>
<?php endif; ?>
	<div id="header-wrapper"><div class="container clearfix">
		<header class="clearfix">
<?php if ($linked_site_logo): ?>
			<div id="logo"><?php print $linked_site_logo; ?></div>
<?php endif; ?>
			<?php print render($page['header']); ?>
		</header>
	</div></div>
<?php if ($page['menu_bar'] || $primary_navigation || $secondary_navigation): ?>
	<div id="nav-wrapper"><div class="container clearfix">
		<?php print render($page['menu_bar']); ?>
		<?php if ($primary_navigation): print $primary_navigation; endif; ?>
	</div></div>
<?php endif; ?>
<?php if ($messages || $page['help']): ?>
	<div id="messages-help-wrapper"><div class="container clearfix">
		<?php print render($page['help']); ?>
		<?php print $messages; ?>
	</div></div>
<?php endif; ?>
<?php if ($breadcrumb): ?>
	<div id="breadcrumb-wrapper"><div class="container clearfix">
		<section id="breadcrumb" class="clearfix">
			<?php print $breadcrumb; ?>
		</section>
	</div></div>
<?php endif; ?>
<?php if ($page['secondary_content']): ?>
    <div id="secondary-content-wrapper"><div class="container clearfix">
      <?php print render($page['secondary_content']); ?>
    </div></div>
<?php endif; ?>
	<div id="content-wrapper"><div class="container">
		<div id="columns"><div class="columns-inner clearfix">
			<div id="content-column"><div class="content-inner">
				<?php print render($page['highlighted']); ?>
				<?php $tag = $title ? 'section' : 'div'; ?>
				<<?php print $tag; ?> id="main-content">
<?php if ($title || $primary_local_tasks || $secondary_local_tasks || $action_links): ?>
					<header>
						<?php print render($title_prefix); ?>
<?php if (arg(0) == 'user' && arg(1) == 'register'): ?>
						<h1 id="page-title" class="title-with-buttons">Register</h1>
<?php elseif (arg(0) == 'user' && arg(1) == 'password'): ?>
						<h1 id="page-title" class="title-with-buttons">Reset Password</h1>
<?php elseif (arg(0) == 'user' && arg(1) == 'login' || arg(0) == 'user' && arg(1) == null): ?>
						<h1 id="page-title" class="title-with-buttons">My Account</h1>
<?php elseif ($title): ?>
						<h1 id="page-title" <?php if (arg(0) == 'events' || arg(0) == 'downloads' || arg(0) == 'drivetrains' || arg(0) == 'parts' || arg(0) == 'vehicles' || arg(0) == 'messages' || arg(0) == 'events'): ?>class="title-with-buttons"<?php endif; ?>><?php print $title; ?></h1>
<?php endif; ?>
<?php if (arg(0) == 'downloads' || arg(0) == 'drivetrains' || arg(0) == 'parts' || arg(0) == 'vehicles'): ?>
						<a href="/<?php print arg(0); ?>" class="block-button-ui<?php if (arg(1) == null): ?> active<?php endif; ?>">Most Recent</a><a href="/<?php print arg(0); ?>/popular" class="block-button-ui<?php if (arg(1) == 'popular'): ?> active<?php endif; ?>">Most Popular</a><?php if (arg(0) == 'parts'): ?><a href="/<?php print arg(0); ?>/all" class="block-button-ui<?php if (arg(1) == 'all'): ?> active<?php endif; ?>">Add A Part</a><?php endif; ?>
<?php endif; ?>
<?php if (arg(0) == 'events'): ?>
						<a href="node/add/competition" class="block-button-ui">Add an Event</a>
<?php endif; ?>
<?php if (arg(0) == 'user' && arg(1) == 'register' || arg(0) == 'user' && arg(1) == 'password' || arg(0) == 'user' && arg(1) == 'login' || arg(0) == 'user' && arg(1) == null): ?>
						<a href="/user/login" class="block-button-ui<?php if (arg(0) == 'user' && arg(1) == 'login' || arg(0) == 'user' && arg(1) == null) print " active"; ?>">Login</a><a href="/user/register" class="block-button-ui<?php if (arg(0) == 'user' && arg(1) == 'register') print " active"; ?>">Register</a><a href="/user/password" class="block-button-ui<?php if (arg(0) == 'user' && arg(1) == 'password') print " active"; ?>">Forgot Password</a>
<?php endif; ?>
						<?php print render($title_suffix); ?>
<?php if ($primary_local_tasks || $secondary_local_tasks || $action_links): ?>
						<div id="tasks">
<?php if ($primary_local_tasks): ?>
							<ul class="tabs primary"><?php print render($primary_local_tasks); ?></ul>
<?php endif; ?>
<?php if ($secondary_local_tasks): ?>
							<ul class="tabs secondary"><?php print render($secondary_local_tasks); ?></ul>
<?php endif; ?>
<?php if ($action_links): ?>
							<ul class="action-links"><?php print render($action_links); ?></ul>
<?php endif; ?>
						</div>
<?php endif; ?>
					</header>
<?php endif; ?>
				<div id="content">
					<?php print render($page['content']); ?>
					<?php //print render($page['content_aside']); ?>
				</div>
				<?php print $feed_icons; ?>
				<div class="clearfix"></div>
			</<?php print $tag; ?>>
		</div></div>
		<div id="floating_sidebar_first">
			<?php print render($page['sidebar_first']); ?>
		</div>
		<?php print render($page['sidebar_second']); ?>
	</div></div>
</div></div>
<?php if ($page['tertiary_content']): ?>
<div id="tertiary-content-wrapper"><div class="container clearfix">
	<?php print render($page['tertiary_content']); ?>
</div></div>
<?php endif; ?>
<?php if ($page['footer']): ?>
<div id="footer-wrapper"><div class="container clearfix">
	<footer class="clearfix">
		<?php print render($page['footer']); ?>
	</footer>
</div></div>
<?php endif; ?>
</div></div>