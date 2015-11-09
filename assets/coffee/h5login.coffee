$(document).ready ->

  divLogout = window.parent.document.getElementById("divLogout")
  divLogin = window.parent.document.getElementById("divLogin")

  progressBar = window.parent.document.getElementById("authProgress")
  textProgress = window.parent.document.getElementById("textProgress")
  containerProgress = window.parent.document.getElementById("containerProgress")
  checkedUser = window.parent.document.getElementById("checkedUser")
  tipoForm = window.parent.document.getElementById("tipoForm")
  btnLogout = window.parent.document.getElementById("btnLogout")
  inputLoadForm = window.parent.document.getElementById("inputLoadForm")
  loggedList = window.parent.document.getElementsByName("isServIBAMA")

  # Consultas and Cadastros tab
  consultTab = window.parent.document.getElementById("btn-consult")
  manageTab = window.parent.document.getElementById("btn-manag")

  # Login button
  manageTab = window.parent.document.getElementById("li-login")
  sessionName = window.document.getElementById("sessionName")


  $(tipoForm).hide()
  $(btnLogout).hide()

  $(divLogin).show()
  $(divLogout).hide()

  $.each loggedList, ()->
    @.checked = 'checked'

  i=0
  progressAnimetion = setInterval( ->
    $(progressBar).width(i++ + "0%")
    if i is 15
      $(containerProgress).hide()
      $(textProgress).html('Usuário registrado.')
      $(checkedUser).show()
      $(tipoForm).show()
      $(btnLogout).show()
      $(inputLoadForm).show()
      clearInterval(progressAnimetion)

      # parent.location.reload()
      window.parent.location.replace( window.parent.location.href )

      # working with a non reloaded page
      # $(consultTab.parentNode).show()
      # $(manageTab.parentNode).show()

      # console.log sessionName.val()

  , 100)