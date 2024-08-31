<?php

use BigBrother\Events;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\comment\widgets\CommentControls;
use humhub\modules\ui\menu\widgets\Menu;
use humhub\modules\content\widgets\WallEntryControls;

return [
	'id' => 'BigBrother',
	'class' => 'BigBrother\Module',
	'namespace' => 'BigBrother',
	'events' => [
		[AdminMenu::class, AdminMenu::EVENT_INIT, [Events::class, 'onAdminMenuInit']],
        [CommentControls::class, Menu::EVENT_INIT, [Events::class, 'onCommentControlsInit']],
        [WallEntryControls::class, WallEntryControls::EVENT_INIT, [Events::class, 'onWallEntryControlsInit']],
	],
];
