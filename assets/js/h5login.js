// Generated by CoffeeScript 1.6.3
(function() {
  $(document).ready(function() {
    var btnLogout, checkedUser, containerProgress, divLogin, divLogout, i, inputLoadForm, loggedList, progressAnimetion, progressBar, textProgress, tipoForm;
    divLogout = window.parent.document.getElementById("divLogout");
    divLogin = window.parent.document.getElementById("divLogin");
    progressBar = window.parent.document.getElementById("authProgress");
    textProgress = window.parent.document.getElementById("textProgress");
    containerProgress = window.parent.document.getElementById("containerProgress");
    checkedUser = window.parent.document.getElementById("checkedUser");
    tipoForm = window.parent.document.getElementById("tipoForm");
    btnLogout = window.parent.document.getElementById("btnLogout");
    inputLoadForm = window.parent.document.getElementById("inputLoadForm");
    loggedList = window.parent.document.getElementsByName("isServIBAMA");
    $(tipoForm).hide();
    $(btnLogout).hide();
    $(divLogin).show();
    $(divLogout).hide();
    $.each(loggedList, function() {
      return this.checked = 'checked';
    });
    i = 0;
    return progressAnimetion = setInterval(function() {
      $(progressBar).width(i++ + "0%");
      if (i === 15) {
        $(containerProgress).hide();
        $(textProgress).html('Usuário registrado.');
        $(checkedUser).show();
        $(tipoForm).show();
        $(btnLogout).show();
        $(inputLoadForm).show();
        return clearInterval(progressAnimetion);
      }
    }, 100);
  });

}).call(this);
