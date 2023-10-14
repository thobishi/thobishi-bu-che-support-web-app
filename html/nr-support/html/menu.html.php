<div id="wrapper">
	<header class="system-header">
		<div class="row-fluid">
			<div class="span7">
				<img src="images/banner-clear.png" class="system-header-img" alt="National Reviews Online">
			</div>
			<div class="span5">	
	<?php
		if (!empty($this->menuOptions['menuItems'])) {
			$menuCount = 0;
			$total = count($this->menuOptions['menuItems']);
	?>
				<div id="navbar-nr" class="navbar">
					<div class="navbar-inner">
						<ul class="nav">
						<?php
							foreach ($this->menuOptions['menuItems'] as $id => $menuItem) {
								if (!empty($menuItem['children'])) {
						?>
									<li class="dropdown">
										<a id="drop_<?php echo $menuCount; ?>" href="<?php echo $menuItem['url']; ?>" role="menuitem" class="dropdown-toggle" data-toggle="dropdown"><?php echo $menuItem['name']; ?> <b class="caret"></b></a>
										<ul class="dropdown-menu" aria-labelledby="drop_<?php echo $menuCount; ?>">
										<?php foreach ($menuItem['children'] as $childMenuItem) {?>
											<li><a tabindex="-1" href="<?php echo $childMenuItem['url']; ?>"><?php echo $childMenuItem['name']; ?></a></li>
										<?php }	?>
										</ul>
									</li>
								<?php } else { ?>
									<li><a href="<?php echo $menuItem['url']; ?>"><?php echo $menuItem['name']; ?></a></li>
						<?php
									}
								$menuCount++;
							}
						?>
						</ul>
					</div>
				</div>
	<?php
		}
	?>
			</div>
		</div>
	</header>
	
	<?php
		if (!empty($this->NavigationBar)) {
			$totalNavs = count($this->NavigationBar);
			$navsCount = 1;
	?>
		<div class="pageCrumb">
			<div class="row-fluid">
				<div class="offset3 span9">
					<ul class="breadcrumb system-breadcrumb">
		<?php
			foreach ($this->NavigationBar as $navName) {
				echo '<li>' . $navName;
				echo ($navsCount < $totalNavs) ? '<span class="divider">/</span>' : '';
				echo '</li>';
				$navsCount++;
			}
		?>
					</ul>
				</div>
			</div>
		</div>
	<?php
		}
	?>
		<div class="pageHeader">
			<div class="row-fluid">
				<div class="<?php echo ((Settings::get('currentUserID') > 0) || (Settings::get('flowID') == 9)) ? 'offset3 span9' : 'span12'; ?>">
	<?php
		if (Settings::isIsset('active_processes_id') && Settings::isIsset('flowID')) {
			echo '<span class="pathdesc">';
			echo $this->workflowDescription(Settings::get('active_processes_id'), Settings::get('flowID'));
			echo "</span>";
		}
	?>
				</div>
			</div>
		</div>