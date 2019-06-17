$(document).ready(function () {
    const $ratingUpButton = $('#rating-up');
    const upUrl = $ratingUpButton.data('url');
    const $ratingDownButton = $('#rating-down');
    const downUrl = $ratingDownButton.data('url');
    const $ratingCounter = $('#rating-counter');

    const handleRatingChangeResponse = function (response) {
        const rating = response.rating;

        if (rating !== undefined) {
            $ratingCounter.text(rating);
        }
    };

    $ratingUpButton.click(function () {
        $.ajax({
            type: 'POST',
            url: upUrl,
            dataType: 'json',
            success: handleRatingChangeResponse
        });
    });

    $ratingDownButton.click(function () {
        $.ajax({
            type: 'POST',
            url: downUrl,
            dataType: 'json',
            success: handleRatingChangeResponse
        });
    });
});