$(function() {

    // 登録ボタンクリックイベント
    $('#btnEntry').click(clickEntry);
});

/**
 * 登録ボタンクリックイベント
 */
function clickEntry() {

    // 登録ボタン無効
    $('#btnEntry').prop("disabled", true);

    var form = $('form').get()[0];
    var formData = new FormData(form);

    // 入力値チェック
    $.ajax({
        type: 'POST',
        processData: false,
        contentType: false,
        url: g_validateURL,
        data: formData,
    }).done(function(data) {
        if (data) {
            data = JSON.parse(data);

            // エラーメッセージ削除
            $('.error-message').remove();

            // 背景色をもとに戻す
            $('input').css('background-color', DEFAULT_INPUT_COLOR);
            $('select').css('background-color', DEFAULT_INPUT_COLOR);

            if (data === null) {

                // 登録ボタン有効
                $('#btnEntry').prop("disabled", false);

                // モーダルダイアログ表示
                // backdrop: 'static' でダイアログの外側をクリックでダイアログが閉じないようにしています
                // keyboard: falseで[ESC]ボタンクリックでダイアログが閉じないようしています。
                $('#confirmation').modal({ backdrop: 'static', keyboard: false, show: true });
                return;
            }

            if (data['isException'] === true) {
                // システムエラーの場合
                alert('システムエラーが発生しました。\nしばらく経ってから再度ご利用くださいますようお願い申し上げます。');
            } else {
                // エラーメッセージ設定
                setErrorMessage(data);
            }


            // 登録ボタン有効
            $('#btnEntry').prop("disabled", false);
        }
    }).fail(function(data) {

        if (data.statusText === 'Forbidden') {
            // CSRF token mismatch
            // Session timeout
            window.location.href = g_errorURL;
        } else {
            alert('システムエラーが発生しました。\nしばらく経ってから再度ご利用くださいますようお願い申し上げます。');
        }
    });
}

/**
 * エラーメッセージ設定
 */
function setErrorMessage(data) {

    var element = '<div class="error-message">%s</div>'
        // メニュー
    for (var column in data) {
        for (var key in data[column]) {
            var message = data[column][key];
            $('input[name = "' + column + '"]').css('background-color', ERROR_INPUT_COLOR);
            $('input[name = "' + column + '"]').after(element.replace('%s', message));
        }
    }
}