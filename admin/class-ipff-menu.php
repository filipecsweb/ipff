<?php
/**
 * Class responsible for managing new admin menu items.
 *
 * @package     Ipff
 * @subpackage  Ipff/Admin
 * @since       1.0.0
 */

class Ipff_Menu {

	/**
	 * @var     string  $menu_item_slug
	 */
	public $menu_item_slug;

	/**
	 * @param   array   $args   Array of arguments:
	 *                          menu_slug
	 */
	public function add($args) {

		if (empty($args['menu_slug'])) {
			return;
		}

		$this->menu_item_slug = $args['menu_slug'];

		switch ($args['menu_slug']) {
			case 'ipff_settings_page':
				$args = array_merge(
					array(
						'parent_slug' => 'options-general.php',
						'page_title' => _x('Instagram Pro for Free', 'Settings page title', 'ipff'),
						'menu_title' => _x('Instagram', 'Menu item title', 'ipff'),
						'capability' => apply_filters('ippf_settings_page_cap', 'update_core')
					),
					$args
				);
		}

		add_submenu_page(
			$args['parent_slug'],
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$args['menu_slug'],
			array($this, 'menu_item_output')
		);

	}

	public function menu_item_output() {

		$slug = $this->menu_item_slug;

		if ($slug == 'ipff_settings_page') {
			require IPFF_PATH . '/admin/view/settings-page.php';
		}

	}

}
