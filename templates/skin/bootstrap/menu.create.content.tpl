<script type="text/javascript">
    jQuery(window).load(function () {
        var trigger = $('#dropdown-create-trigger');
        var menu = $('#dropdown-create-menu');
        var pos = trigger.offset();


        // Dropdown
        menu.find('li.active').prependTo(menu).click(function () {
            menu.hide();
            return false;
        });
        menu.appendTo('body').css({ 'left': pos.left - 18, 'top': pos.top - 13, 'display': 'none' });

        trigger.click(function () {
            menu.toggle();
            return false;
        });


        // Hide menu
        $(document).click(function () {
            menu.hide();
        });

        $('body').on("click", "#dropdown-create-trigger, #dropdown-create-menu", function (e) {
            e.stopPropagation();
        });

        $(window).resize(function () {
            menu.css({ 'left': $('#dropdown-create-trigger').offset().left - 18 });
        });
    });
</script>

{if $sEvent!='saved'}
<div class="dropdown-create">
	{strip}
		<h2 class="page-header">{$aLang.block_create}
{* Пока убрал, но могу вернуть чтобы так было, тут явный косяк верстки
			<li class="btn-group">
				<a href="#" class="btn">
					{if $sAction=='content'}
						{foreach from=$aContentTypes item=oType}
							{if $sEvent==$oType->getContentUrl()}{$oType->getContentTitle()|escape:'html'}{/if}
						{/foreach}
					{elseif $sMenuItemSelect=='blog'}
						{$aLang.blog_menu_create}
					{elseif $sMenuItemSelect=='talk'}
						{$aLang.block_create_talk}
					{else}
						{hook run='menu_create_item_select' sMenuItemSelect=$sMenuItemSelect}
					{/if}
				</a>
				<a href="#" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
				<ul class="dropdown-menu">
					{foreach from=$aContentTypes item=oType}
						<li {if $sEvent==$oType->getContentUrl()}class="active"{/if}><a href="{router page='content'}{$oType->getContentUrl()}/add/">{$oType->getContentTitle()|escape:'html'}</a></li>
					{/foreach}
					<li {if $sMenuItemSelect=='blog'}class="active"{/if}><a href="{router page='blog'}add/">{$aLang.blog_menu_create}</a></li>
					<li {if $sMenuItemSelect=='talk'}class="active"{/if}><a href="{router page='talk'}add/">{$aLang.block_create_talk}</a></li>
					{hook run='menu_create_item' sMenuItemSelect=$sMenuItemSelect}
				</ul>
			</li>
*}

		</h2>
	{/strip}
</div>

<ul class="nav nav-pills mb-30">
    {foreach from=$aContentTypes item=oType}
        <li {if $sEvent==$oType->getContentUrl()}class="active"{/if}><a href="{router page='content'}{$oType->getContentUrl()}/add/">{$oType->getContentTitle()|escape:'html'}</a></li>
    {/foreach}
    <li {if $sMenuItemSelect=='blog'}class="active"{/if}><a href="{router page='blog'}add/">{$aLang.blog_menu_create}</a></li>
    <li {if $sMenuItemSelect=='talk'}class="active"{/if}><a href="{router page='talk'}add/">{$aLang.block_create_talk}</a></li>
    {hook run='menu_create_item' sMenuItemSelect=$sMenuItemSelect}
</ul>
{/if}

{if $sMenuItemSelect=='topic'}
    {if $iUserCurrentCountTopicDraft}
        <a href="{router page='content'}saved/" class="drafts">{$aLang.topic_menu_saved}
            ({$iUserCurrentCountTopicDraft})</a>
    {/if}
{*<ul class="nav nav-pills mb-30">
    <li {if $sMenuSubItemSelect=='topic'}class="active"{/if}><a href="{router page='content'}add/">{$aLang.topic_menu_add_topic}</a></li>
    <li {if $sMenuSubItemSelect=='question'}class="active"{/if}><a href="{router page='question'}add/">{$aLang.topic_menu_add_question}</a></li>
    <li {if $sMenuSubItemSelect=='link'}class="active"{/if}><a href="{router page='link'}add/">{$aLang.topic_menu_add_link}</a></li>
    <li {if $sMenuSubItemSelect=='photoset'}class="active"{/if}><a href="{router page='photoset'}add/">{$aLang.topic_menu_add_photoset}</a></li>
    {hook run='menu_create_topic_item'}
</ul>*}
{/if}

{hook run='menu_create' sMenuItemSelect=$sMenuItemSelect sMenuSubItemSelect=$sMenuSubItemSelect}
