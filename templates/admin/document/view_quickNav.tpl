{* purpose of this template: documents view filter form in admin area *}
{checkpermissionblock component='Pdfviewer:Document:' instance='::' level='ACCESS_EDIT'}
{assign var='objectType' value='document'}
<form action="{$modvars.ZConfig.entrypoint|default:'index.php'}" method="get" id="pdfviDocumentQuickNavForm" class="pdfviQuickNavForm">
    <fieldset>
        <h3>{gt text='Quick navigation'}</h3>
        <input type="hidden" name="module" value="{modgetinfo modname='Pdfviewer' info='displayname'}" />
        <input type="hidden" name="type" value="admin" />
        <input type="hidden" name="func" value="view" />
        <input type="hidden" name="ot" value="document" />
        {gt text='All' assign='lblDefault'}
        {if !isset($workflowStateFilter) || $workflowStateFilter eq true}
            <label for="workflowState">{gt text='Workflow state'}</label>
            <select id="workflowState" name="workflowState">
                <option value="">{$lblDefault}</option>
            {foreach item='option' from=$workflowStateItems}
                <option value="{$option.value}"{if $option.title ne ''} title="{$option.title|safetext}"{/if}{if $option.value eq $workflowState} selected="selected"{/if}>{$option.text|safetext}</option>
            {/foreach}
            </select>
        {/if}
        {if !isset($searchFilter) || $searchFilter eq true}
            <label for="searchterm">{gt text='Search'}:</label>
            <input type="text" id="searchterm" name="searchterm" value="{$searchterm}" />
        {/if}
        {if !isset($sorting) || $sorting eq true}
            <label for="sortby">{gt text='Sort by'}</label>
            &nbsp;
            <select id="sortby" name="sort">
            <option value="id"{if $sort eq 'id'} selected="selected"{/if}>{gt text='Id'}</option>
            <option value="workflowState"{if $sort eq 'workflowState'} selected="selected"{/if}>{gt text='Workflow state'}</option>
            <option value="title"{if $sort eq 'title'} selected="selected"{/if}>{gt text='Title'}</option>
            <option value="datei"{if $sort eq 'datei'} selected="selected"{/if}>{gt text='Datei'}</option>
            <option value="begindate"{if $sort eq 'begindate'} selected="selected"{/if}>{gt text='Begindate'}</option>
            <option value="enddate"{if $sort eq 'enddate'} selected="selected"{/if}>{gt text='Enddate'}</option>
            <option value="createdDate"{if $sort eq 'createdDate'} selected="selected"{/if}>{gt text='Creation date'}</option>
            <option value="createdUserId"{if $sort eq 'createdUserId'} selected="selected"{/if}>{gt text='Creator'}</option>
            <option value="updatedDate"{if $sort eq 'updatedDate'} selected="selected"{/if}>{gt text='Update date'}</option>
            </select>
            <select id="sortdir" name="sortdir">
                <option value="asc"{if $sdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
                <option value="desc"{if $sdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
            </select>
        {else}
            <input type="hidden" name="sort" value="{$sort}" />
            <input type="hidden" name="sdir" value="{if $sdir eq 'desc'}asc{else}desc{/if}" />
        {/if}
        {if !isset($pageSizeSelector) || $pageSizeSelector eq true}
            <label for="num">{gt text='Page size'}</label>
            &nbsp;
            <select id="num" name="num">
                <option value="5"{if $pageSize eq 5} selected="selected"{/if}>5</option>
                <option value="10"{if $pageSize eq 10} selected="selected"{/if}>10</option>
                <option value="15"{if $pageSize eq 15} selected="selected"{/if}>15</option>
                <option value="20"{if $pageSize eq 20} selected="selected"{/if}>20</option>
                <option value="30"{if $pageSize eq 30} selected="selected"{/if}>30</option>
                <option value="50"{if $pageSize eq 50} selected="selected"{/if}>50</option>
                <option value="100"{if $pageSize eq 100} selected="selected"{/if}>100</option>
            </select>
        {/if}
        <input type="submit" name="updateview" id="quicknav_submit" value="{gt text='OK'}" />
    </fieldset>
</form>

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        pdfviInitQuickNavigation('document', 'admin');
        {{if isset($searchFilter) && $searchFilter eq false}}
            {{* we can hide the submit button if we have no quick search field *}}
            $('quicknav_submit').addClassName('z-hide');
        {{/if}}
    });
/* ]]> */
</script>
{/checkpermissionblock}
