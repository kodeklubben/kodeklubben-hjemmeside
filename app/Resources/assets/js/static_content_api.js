/**
 * @param submitButton
 * @param id
 * @param contentInput
 */
function handleStaticContentFormSubmit(submitButton, id, contentInput) {
    submitButton.on('click', function (e) {
        var content = contentInput.val();
        if (content.length < 1)return;
        e.preventDefault();
        var btnTitle = submitButton.html();
        animateButtonUpdate(submitButton)
        saveContent(id, content, function (data) {
            if (data.success) {
                animateButtonSuccess(submitButton, btnTitle);
            } else {
                animateButtonError(submitButton, btnTitle);
            }
        });
    })
}
function saveContent(idString, content, callback) {
    if (content.length < 1)return;
    $.post('/kontrollpanel/api/statisk_innhold', {idString: idString, content: content}, callback)
}
function animateButtonUpdate(button) {
    button.attr('disabled', true);
    button.html('Oppdaterer ');
    button.append(loading);
}
function animateButtonSuccess(button, buttonTitle) {
    button.html('Oppdatert!');
    button.removeClass('btn-primary').addClass('btn-success');
    setTimeout(function () {
        button.removeClass('btn-success').addClass('btn-primary').removeAttr('disabled').html(buttonTitle);
    }, 3000);
}
function animateButtonError(button, buttonTitle) {
    button.html('Feil Oppstått!');
    button.removeClass('btn-primary').addClass('btn-danger');
    setTimeout(function () {
        button.removeClass('btn-danger').addClass('btn-primary').removeAttr('disabled').html(buttonTitle);
    }, 3000);
}
