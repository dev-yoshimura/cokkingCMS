$(function() {

    // はいボタンクリックイベント
    $('#btnModalYes').click(clickModalYes);
});

/**
 * はいボタンクリックイベント
 */
function clickModalYes() {
    var h = $(window).height();
    $('#loader-bg ,#loader').height(h).css('display', 'block');

    $('form').submit();
}