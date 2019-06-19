module.exports = function ($) {
    // VARS
    const $module = $('.ajax-search-collection');
    const MINIMAL_SEARCH_WORD_LENGTH = 2;
    const $searchInput = $module.find('.search-input');
    const $select = $module.find('.collection-container');
    const $searchResults = $module.find('.search-results');

    let autoCompleteList = [];
    let searchInProgress = false;

    // FUNCTIONS
    const setFoundedItems = function (items) {
        console.log(items);
        if (items.length === 0) {
            return;
        }

        autoCompleteList = [];

        for (let i = 0; i < items.length; i++) {
            autoCompleteList.push({
                id: items[i].id,
                text: items[i].name,
            });
        }

        console.log(autoCompleteList);
    };

    const getSelectedValues = function () {
        const $options = $select.children("option:selected");
        const values = [];

        for (let i = 0; i < $options.length; i++) {
            values.push($($options[i]).val());
        }

        return values;
    };

    const getSelectedObjects = function () {
        const $options = $select.children("option:selected");
        const values = [];

        for (let i = 0; i < $options.length; i++) {
            values.push({
                id: $($options[i]).val(),
                text: $($options[i]).text()
            });
        }

        return values;
    };

    const updateView = function (items) {
        // render tags
        renderItems();
    };

    const renderItems = function () {
        console.log(getSelectedObjects());
    };

    const handleAjaxResponse = function (response) {
        searchInProgress = false;
        setFoundedItems(response.items);
        renderAutocompleteList();
    };

    const renderAutocompleteList = function () {
        // TODO: implement
        for (let i = 0; i < autoCompleteList.length; i++) {
            const item = autoCompleteList[i];
            $searchResults.find('.item').remove();
            $searchResults.append(
                $('<label class="label label-success" data-item-id="' + item.id + '">' + item.text + '</label>')
            );
        }
    };

    const hideAutocompleteList = function () {
        // TODO: implement
    };

    // APP
    $searchInput.keyup(function (e) {
        if (searchInProgress) {
            return;
        }

        const searchText = $(this).val();

        if (searchText.length < MINIMAL_SEARCH_WORD_LENGTH) {
            return;
        }

        searchInProgress = true;

        $.ajax({
            type: "POST",
            url: $searchInput.data("search-url"),
            data: "search=" + searchText,
            success: handleAjaxResponse,
            dataType: "json"
        });
    });

    $searchInput.focus(function (e) {
        renderAutocompleteList();
    });

    $searchInput.focusout(function (e) {
        hideAutocompleteList();
    });
};