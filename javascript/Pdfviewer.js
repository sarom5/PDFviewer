'use strict';

var pdfviContextMenu;

pdfviContextMenu = Class.create(Zikula.UI.ContextMenu, {
    selectMenuItem: function ($super, event, item, item_container) {
        // open in new tab / window when right-clicked
        if (event.isRightClick()) {
            item.callback(this.clicked, true);
            event.stop(); // close the menu
            return;
        }
        // open in current window when left-clicked
        return $super(event, item, item_container);
    }
});

/**
 * Initialises the context menu for item actions.
 */
function pdfviInitItemActions(objectType, func, containerId) {
    var triggerId, contextMenu, iconFile;

    triggerId = containerId + 'trigger';

    // attach context menu
    contextMenu = new pdfviContextMenu(triggerId, { leftClick: true, animation: false });

    // process normal links
    $$('#' + containerId + ' a').each(function (elem) {
        // hide it
        elem.addClassName('z-hide');
        // determine the link text
        var linkText = '';
        if (func === 'display') {
            linkText = elem.innerHTML;
        } else if (func === 'view') {
            elem.select('img').each(function (imgElem) {
                linkText = imgElem.readAttribute('alt');
            });
        }

        // determine the icon
        iconFile = '';
        if (func === 'display') {
            if (elem.hasClassName('z-icon-es-preview')) {
                iconFile = 'xeyes.png';
            } else if (elem.hasClassName('z-icon-es-display')) {
                iconFile = 'kview.png';
            } else if (elem.hasClassName('z-icon-es-edit')) {
                iconFile = 'edit';
            } else if (elem.hasClassName('z-icon-es-saveas')) {
                iconFile = 'filesaveas';
            } else if (elem.hasClassName('z-icon-es-delete')) {
                iconFile = '14_layer_deletelayer';
            } else if (elem.hasClassName('z-icon-es-back')) {
                iconFile = 'agt_back';
            }
            if (iconFile !== '') {
                iconFile = Zikula.Config.baseURL + 'images/icons/extrasmall/' + iconFile + '.png';
            }
        } else if (func === 'view') {
            elem.select('img').each(function (imgElem) {
                iconFile = imgElem.readAttribute('src');
            });
        }
        if (iconFile !== '') {
            iconFile = '<img src="' + iconFile + '" width="16" height="16" alt="' + linkText + '" /> ';
        }

        contextMenu.addItem({
            label: iconFile + linkText,
            callback: function (selectedMenuItem, isRightClick) {
                var url;

                url = elem.readAttribute('href');
                if (isRightClick) {
                    window.open(url);
                } else {
                    window.location = url;
                }
            }
        });
    });
    $(triggerId).removeClassName('z-hide');
}

function pdfviCapitaliseFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/**
 * Submits a quick navigation form.
 */
function pdfviSubmitQuickNavForm(objectType) {
    $('pdfvi' + pdfviCapitaliseFirstLetter(objectType) + 'QuickNavForm').submit();
}

/**
 * Initialise the quick navigation panel in list views.
 */
function pdfviInitQuickNavigation(objectType, controller) {
    if ($('pdfvi' + pdfviCapitaliseFirstLetter(objectType) + 'QuickNavForm') == undefined) {
        return;
    }

    if ($('catid') != undefined) {
        $('catid').observe('change', function () { pdfviSubmitQuickNavForm(objectType); });
    }
    if ($('sortby') != undefined) {
        $('sortby').observe('change', function () { pdfviSubmitQuickNavForm(objectType); });
    }
    if ($('sortdir') != undefined) {
        $('sortdir').observe('change', function () { pdfviSubmitQuickNavForm(objectType); });
    }
    if ($('num') != undefined) {
        $('num').observe('change', function () { pdfviSubmitQuickNavForm(objectType); });
    }

    switch (objectType) {
    case 'document':
        if ($('workflowState') != undefined) {
            $('workflowState').observe('change', function () { pdfviSubmitQuickNavForm(objectType); });
        }
        break;
    default:
        break;
    }
}
