$(document).ready(function(){

  //-------------------------------------------------------------------------
  // ADD/DEL MINISEED BUTTONS
  //-------------------------------------------------------------------------

  $('#btn-add').click(function() {
    var num     = $('.clonedInput').length; // how many "duplicatable" input fields we currently have
    var newNum  = new Number(num + 1);      // the numeric ID of the new input field being added

    if (num < 1) {
      newElem = savedElem;
      $('#input_image').after(newElem);
    }
    else {
      // create the new element via clone(), and manipulate it's ID using newNum value
      var newElem = $('#input' + num).clone().attr('id', 'input' + newNum);

      // manipulate the name/id values of the input inside the new element
      newElem.find('#miniseed').attr('name', 'miniseed' + newNum);
      //newElem.children('#estacao' + num).attr('id', 'estacao' + newNum);

      // insert the new element after the last "duplicatable" input field
      $('#input' + num).after(newElem);
    }

    // business rule: you can only add 5 names
    if (newNum == 10)
      $('#btn-add').prop({ disabled: true });

    // enable the "remove" button
    $('#btn-del').prop({ disabled: false });
  });

  $('#btn-del').click(function() {
    var num = $('.clonedInput').length; // how many "duplicatable" input fields we currently have

    $('#input' + num).remove();     // remove the last element

    // enable the "add" button
    $('#btn-add').prop({ disabled: false });

    // if only one element remains, disable the "remove" button
    if (num-1 == 0)
      $('#btn-del').prop({ disabled: true });
  });

  // save and remove element
  var num = $('.clonedInput').length;
  var savedElem = $('#input' + num).clone();
  $('#input' + num).remove();
  // enable add button
  $('#btn-add').prop({ disabled: false });
  // disable remove button
  if (num == 1)
    $('#btn-del').prop({ disabled: true });


  //-------------------------------------------------------------------------
  // BOOTSTRAP
  //-------------------------------------------------------------------------

  $('[rel=tooltip]').tooltip({'placement':'top'});

  $('.fileupload').fileupload();

  $("#slider").slider({
    range: "min",
    min: 10,
    max: 1000,
    value: 100,
    slide: function (event, ui) {
      $("#amount").val(ui.value);
    }
  });
  $("#amount").val($("#slider").slider("value"));
  jQuery('#slider').mousemove(function(){
    updateCircle();
  });

  //-------------------------------------------------------------------------
  // HIDE DOCKBAR ELEMENTS
  //-------------------------------------------------------------------------

  // definir busca pelo local ou por lat e long
  $("#find-radio").click(function () {
    $(".find-local").hide();
    $(".find-radio").fadeToggle("normal");
  });
  $("#find-local").click(function () {
    $(".find-radio").hide();
    $(".find-local").fadeToggle("normal");
  });
  $(".find-radio").hide();

  $('.dropdown-menu input, .dropdown-menu label').click(function(e) {
    e.stopPropagation();
  });

  //-------------------------------------------------------------------------
  // MAP EFFECTS
  //-------------------------------------------------------------------------

  // verifica o tamanho da tela para inserir a funcao de resize

    $("#resizeMapSmall").hide();
    $('#resizeMapFull').click(function() {
      $('#navbar').addClass('hide-navbar');
      $('.dockbar').hide();
      $('#map-container').removeClass('map');
      $('#map-container').addClass('overlay-map');
      $('.dockbar').addClass('overlay-dock');
      $(".dockbar").fadeToggle(700);
      $("#resizeMapFull").hide();
      $("#resizeMapSmall").fadeToggle();
      $("body").addClass('disable-scrolling');
      $("html").addClass('disable-scrolling');
    });
    $('#resizeMapSmall').click(function() {
      $('#navbar').removeClass('hide-navbar');
      $('.dockbar').removeClass('overlay-dock');
      $('.dockbar').hide();
      $(".dockbar").fadeToggle(700);
      $('#map-container').removeClass('overlay-map');
      $('#map-container').addClass('map');
      $("#map-container").hide();
      $("#map-container").show("fade", {}, 500);
      $("#resizeMapSmall").hide();
      $("#resizeMapFull").fadeToggle();
      $("body").removeClass('disable-scrolling');
      $("html").removeClass('disable-scrolling');
    });
  if ( $(window).width() > 640) {
    // calendar pick
    $('#data_inicio, #data_fim').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    startView: 2,
    todayHighlight: true,
    todayBtn: 'linked'
    });
  }


  //-------------------------------------------------------------------------
  // CUSTOM FUNCTIONS
  //-------------------------------------------------------------------------

  //change the case to Letter case, ex: helmuth saatkamp to Helmuth Saatkamp
  String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
  };

  // precionar o enter ativa busca
  $(function(){
    $('input').keydown(function(e){
      if (e.keyCode == 13) {
        setTimeout(function() {
          $('#submit').click();
        }, 0);
      }
    });
  });
});
