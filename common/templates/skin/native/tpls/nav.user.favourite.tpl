{**
 * Навигация в профиле пользователя в разделе "Избранное"
 *}

<ul class="nav nav-pills mb-30">
	<li {if $sMenuSubItemSelect=='topics'}class="active"{/if}>
		<a href="{$oUserProfile->getUserWebPath()}favourites/topics/">{$aLang.user_menu_profile_favourites_topics}  {if $iCountTopicFavourite}<span class="block-count">{$iCountTopicFavourite}</span>{/if}</a>
	</li>
	<li {if $sMenuSubItemSelect=='comments'}class="active"{/if}>
		<a href="{$oUserProfile->getUserWebPath()}favourites/comments/">{$aLang.user_menu_profile_favourites_comments}  {if $iCountCommentFavourite}<span class="block-count">{$iCountCommentFavourite}</span>{/if}</a>
	</li>

	{hook run='menu_profile_favourite_item' oUserProfile=$oUserProfile}
</ul>

{hook run='menu_profile_favourite' oUserProfile=$oUserProfile}