$("#btn_manage1").addClass("active")
$(".nav-sidebar a").on "click", (event) ->
  # clean all selection
  $(@).each ->
    $("a").parent().removeClass("active")
  # mark selected option
  $(@).parent().addClass("active")

  if $(@).prop("id") is "btn-manage1"
    $("#manage2").hide()
    $("#manage1").show()
  else if $(@).prop("id") is "btn-manage2"
    $("#manage1").hide()
    $("#manage2").show()




  #makes mangage area invisible after loading
$("#manag").hide()
# }}}