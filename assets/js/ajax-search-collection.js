module.exports = function ($) {
    // VARS
    const $module = $('.ajax-search-collection');
    const MINIMAL_SEARCH_WORD_LENGTH = 2;
    const $searchInput = $module.find('.search-input');
    const $select = $module.find('.collection-container');
    const $searchContainer = $module.find('.search-container');
    const $searchResults = $module.find('.search-results');
    const $selectedItemList = $module.find('.selected-list');
    let multiple = $searchInput.data('multiple');

    let autoCompleteList = [];
    let searchInProgress = false;

    // FUNCTIONS
    const setFoundedItems = function (items) {
        if (items.length === 0) {
            return;
        }

        autoCompleteList = [];

        for (let i = 0; i < items.length; i++) {
            autoCompleteList.push({
                id: items[i].id,
                text: items[i].text,
            });
        }
    };

    const getSelectedValues = function () {
        const $options = $select.children("option:selected");
        const values = [];

        for (let i = 0; i < $options.length; i++) {
            const value = $($options[i]).val();

            if (value !== '') {
                values.push();
            }
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

    const renderItems = function () {
        const selectObjects = getSelectedObjects();

        for (let i = 0; i < selectObjects.length; i++) {
            const item = selectObjects[i];
            $selectedItemList.find('.selected-items').remove();

            if (item.id !== '' && item.text !== '') {
                $selectedItemList.append(
                    $('<div class="selected-item label label-success" data-id="' + item.id + '"><span class="remove-item">X</span>' + item.text + '</div>')
                );
            }
        }

    };

    const handleAjaxResponse = function (response) {
        searchInProgress = false;
        setFoundedItems(response.items);
        renderAutocompleteList();
    };

    const renderAutocompleteList = function () {
        $searchResults.addClass('active');
        $searchResults.find('.autocomplete-item').remove();

        for (let i = 0; i < autoCompleteList.length; i++) {
            const item = autoCompleteList[i];
            $searchResults.find('.item').remove();
            $searchResults.append(
                $('<div class="autocomplete-item" data-id="' + item.id + '">' + item.text + '</div>')
            );
        }
    };

    const hideAutocompleteList = function () {
        $searchResults.removeClass('active');
    };

    const addNewSelectedItem = function (item) {
        if (!multiple) {
            $selectedItemList.find('.selected-item').remove();
            $select.val(item.id);
            $selectedItemList.append(
                $('<div class="selected-item label label-success" data-id="' + item.id + '">' +
                    '<span class="remove-item">X</span>' + item.text +
                    '</div>')
            );

            return;
        }

        const selectedValues = getSelectedValues();

        // if already in list - skip
        for (let i = 0; i < selectedValues.length; i++) {
            if (item.id == selectedValues[i]) {
                return;
            }
        }

        selectedValues.push(item.id);

        const selectedObjects = getSelectedObjects();
        selectedObjects.push(item);

        $select.val(selectedValues);
        $selectedItemList.append(
            $('<div class="selected-item label label-success" data-id="' + item.id + '">' +
                '<span class="remove-item">X</span>' + item.text +
            '</div>')
        );
    };

    const removeItem = function ($item) {
        const id = $item.data('id');
        $item.remove();

        let selectedValues = getSelectedValues();

        selectedValues = selectedValues.filter(function (value, index, arr) {
            return value != id;
        });

        $select.val(selectedValues);
    };

    // APP
    if (multiple === undefined || multiple === null) {
        multiple = true;
    }

    renderItems();

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

    $searchContainer.focus(function () {
        renderAutocompleteList();
    });

    $searchContainer.on('click', '.autocomplete-item', function () {
        hideAutocompleteList();
        const $item = $(this);

        addNewSelectedItem({id: $item.data('id'), text: $item.text()});
    });

    $searchInput.on('focus', function () {
        renderAutocompleteList();
    });

    $selectedItemList.on('click', '.remove-item', function () {
        const $item = $(this).parent('.selected-item');

        removeItem($item);
    });

    $(document).on('click', function (e) {
        const $target = $(e.target);

        if (!$target.hasClass('search-container') && !$target.hasClass('search-input') && !$target.hasClass('search-results')) {
            hideAutocompleteList();
        }
    });
};