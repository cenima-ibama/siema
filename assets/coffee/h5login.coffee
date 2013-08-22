$(document).ready ->

  tipoForm = window.parent.document.getElementById("denunciaAnonima")
  modalFooter = window.parent.document.getElementsByClassName("modal-footer")
  btnCadastrar = window.parent.document.getElementById('btnCadastrar')
  btnLogar = window.parent.document.getElementById('btnLogar')
  tipoForm = window.parent.document.getElementById('tipoForm')
  divDenuncia = window.parent.document.getElementById('divDenuncia')

  $(divDenuncia).hide()
  $(btnCadastrar).hide()
  $(btnLogar).hide()
  $(tipoForm).show()
  tipoForm.click()
