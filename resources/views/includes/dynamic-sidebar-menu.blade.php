<?php 
	function buildSidebarMenu($data) {

		$menuMarkup = '';

		if (!empty($data)) {

			foreach ($data as $category) {

				$title  = ucwords($category['name']);
				$cateID = $category['id'];

				$categoriesRoute = categoriesProductRoute($cateID, $title);
				$path = (Request::url()) == $categoriesRoute ? 'active' : '';

				if (!empty($category['children'])) {
					
					$menuMarkup .= "<li class='".$path." nav-item'><a href='".$categoriesRoute."' class='nav-link'>".$title."</a><ul class='lw-sidebar-dropdown-links'>";
					$menuMarkup .= buildSidebarMenu($category['children']);
					$menuMarkup .= "</ul></li>";

				} else {

					$menuMarkup .= "<li class='".$path." nav-item'><a href='".$categoriesRoute."' class='nav-link'>".$title."</a></li>";

				}
			}
		}
		
		return $menuMarkup;
	}
?>

<?= buildSidebarMenu($menuData['sideBarCategoriesMenuData']) ?>