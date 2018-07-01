$(function() {

    // 初期化
    initialize();

    // 写真選択ボタンクリックイベント
    $('#btnImage').click(clickImage);

    // 写真選択イベント
    $('#menuImage').change(changeMenuImage);

    // 材料追加ボタンクリックイベント
    $('#btnAddMaterial').click(clickAddMaterial);

    // 作り方追加ボタンクリックイベント
    $('#btnAddRecipe').click(clickAddRecipe);

    // 保存ボタンクリックイベント
    $('#btnEntry').click(clickEntry);
});

/**
 * 初期化
 */
function initialize() {

    // 材料・作り方削除イベント設定
    $("div[name ='material']").children('div').children('button').on('click', clickDeleteMaterial);
    $("div[name ='recipe']").children('div').children('button').on('click', clickDeleteRecipe);
}

/**
 * 写真選択ボタンクリックイベント
 */
function clickImage() {

    $('#menuImage').click();
    return false;
}

/**
 * 写真選択イベント
 */
function changeMenuImage() {

    if (!this.files.length) {
        return;
    }

    var file = $(this).prop('files')[0];
    var fr = new FileReader();
    var $preview = $('#preview');
    $preview.prop('src', '');

    fr.onload = function() {
        $preview.prop('src', fr.result);
    }
    fr.readAsDataURL(file);
}

/**
 * 材料追加ボタンクリックイベント
 */
function clickAddMaterial() {

    var count = $('div[name="material"]').length;
    var input = g_materialInput.replace(/Materoals\[count\]/g, 'Materoals[' + String(count) + ']');
    var $input = $(input);

    // 削除ボタンクリックイベント設定
    $input.children('div').children('button').on('click', clickDeleteMaterial);

    // 追加
    $('#addMaterial').before($input);
    return false;
}

/**
 * 材料削除イベント
 */
function clickDeleteMaterial(e) {

    // 削除
    $(e.target).parent().parent().remove();

    // name再設定
    var count = $('div[name="material"]').length;
    var $elements = $('div[name="material"]');
    for (var i = 0; i < count; i++) {
        $elements.eq(i).children('div').eq(0).children('select').attr('name', 'Materoals[' + i + '][type]');
        $elements.eq(i).children('div').eq(1).children('input').attr('name', 'Materoals[' + i + '][name]');
        $elements.eq(i).children('div').eq(2).children('input').attr('name', 'Materoals[' + i + '][quantity]');
    }
    return false;
}

/**
 * 作り方追加ボタンクリックイベント
 */
function clickAddRecipe() {

    var count = $('div[name="recipe"]').length;
    // detail
    var input = g_recipeInput.replace('Recipes[count]', 'Recipes[' + String(count) + ']');
    $input = $(input);

    // 番号設定
    $input.children('label').text(Number(count) + 1);

    // 削除ボタンクリックイベント設定
    $input.children('div').children('button').on('click', clickDeleteRecipe);

    // 追加
    $('#addRecipe').before($input);
    return false;
}

/**
 * 作り方削除イベント
 */
function clickDeleteRecipe(e) {

    // 削除
    $(e.target).parent().parent().remove();

    // name再設定
    var count = $('div[name="recipe"]').length;
    var $elements = $('div[name="recipe"]');
    for (var i = 0; i < count; i++) {
        $elements.eq(i).children('div').eq(0).children('input').attr('name', 'Recipes[' + i + '][detail]');
    }

    // 番号再設定
    $recipes = $("div[name ='recipe']");
    for (var i = 0; i < $recipes.length; i++) {
        $recipes.eq(i).children('label').text(i + 1);
    }
    return false;
}

/**
 * 保存ボタンクリックイベント
 */
function clickEntry() {

    // 登録ボタン無効
    $('#btnEntry').prop("disabled", true);

    var form = $('form').get()[0];
    var formData = new FormData(form);

    // 入力値チェック
    $.ajax({
        type: 'POST',
        dataType: "text",
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
                // 入力エラーの場合
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
    for (var key in data['Menu']['name']) {

        var message = data['Menu']['name'][key];
        $('input[name = "Menu[name]"]').css('background-color', ERROR_INPUT_COLOR);
        $('input[name = "Menu[name]"]').after(element.replace('%s', message));
    }
    for (var key in data['Menu']['type']) {

        var message = data['Menu']['type'][key];
        $('select[name = "Menu[type]"]').css('background-color', ERROR_INPUT_COLOR);
        $('select[name = "Menu[type]"]').after(element.replace('%s', message));
    }
    for (var key in data['Menu']['quantity']) {

        var message = data['Menu']['quantity'][key];
        $('select[name = "Menu[quantity]"]').css('background-color', ERROR_INPUT_COLOR);
        $('select[name = "Menu[quantity]"]').after(element.replace('%s', message));
    }
    for (var key in data['Menu']['image']) {

        var message = data['Menu']['image'][key];
        $('#preview').after(element.replace('%s', message));
    }

    // 材料
    for (var i in data['Materoals']) {

        if (data['Materoals'][i]['empty']) {
            var message = data['Materoals'][i]['empty'];
            $('#addMaterial').before(element.replace('%s', message));
        }

        for (var key in data['Materoals'][i]['type']) {

            var message = data['Materoals'][i]['type'][key];
            $('select[name = "Materoals[' + i + '][type]"]').css('background-color', ERROR_INPUT_COLOR);
            $('select[name = "Materoals[' + i + '][type]"]').after(element.replace('%s', message));
        }
        for (var key in data['Materoals'][i]['name']) {

            var message = data['Materoals'][i]['name'][key];
            $('input[name = "Materoals[' + i + '][name]"]').css('background-color', ERROR_INPUT_COLOR);
            $('input[name = "Materoals[' + i + '][name]"]').after(element.replace('%s', message));
        }
        for (var key in data['Materoals'][i]['quantity']) {

            var message = data['Materoals'][i]['quantity'][key];
            $('input[name = "Materoals[' + i + '][quantity]"]').css('background-color', ERROR_INPUT_COLOR);
            $('input[name = "Materoals[' + i + '][quantity]"]').after(element.replace('%s', message));
        }
    }

    // 作り方
    for (var i in data['Recipes']) {

        if (data['Recipes'][i]['empty']) {
            var message = data['Recipes'][i]['empty'];
            $('#addRecipe').before(element.replace('%s', message));
        }

        for (var key in data['Recipes'][i]['detail']) {

            var message = data['Recipes'][i]['detail'][key];
            $('input[name = "Recipes[' + i + '][detail]"]').css('background-color', ERROR_INPUT_COLOR);
            $('input[name = "Recipes[' + i + '][detail]"]').after(element.replace('%s', message));
        }
    }
}