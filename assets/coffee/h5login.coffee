$(document).ready ->

  divLogout = window.parent.document.getElementById("divLogout")
  divLogin = window.parent.document.getElementById("divLogin")

  progressBar = window.parent.document.getElementById("authProgress")
  textProgress = window.parent.document.getElementById("textProgress")
  containerProgress = window.parent.document.getElementById("containerProgress")
  checkedUser = window.parent.document.getElementById("checkedUser")
  tipoForm = window.parent.document.getElementById("tipoForm")
  btnLogout = window.parent.document.getElementById("btnLogout")

  $(tipoForm).hide()
  $(btnLogout).hide()

  $(divLogin).show()
  $(divLogout).hide()

  i=0
  progressAnimetion = setInterval( ->
    $(progressBar).width(i++ + "0%")
    if i is 15
      $(containerProgress).hide()
      $(textProgress).html('Usuário registrado.')
      $(checkedUser).show()
      $(tipoForm).show()
      $(btnLogout).show()
      clearInterval(progressAnimetion)
  , 100)
