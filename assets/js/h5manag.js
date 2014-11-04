// Generated by CoffeeScript 1.7.1
(function() {
  var deleteTempData, html, i, key, properties, reg, rest, _ref, _ref1, _ref2;

  H5.Data.restURL = "http://" + document.domain + "/siema/rest";

  H5.DB.ocorrencia = {};

  H5.DB.ocorrencia.table = "ocorrencia";

  H5.DB.ocorrencia.data = {
    init: function() {
      return this.ocorrencia = {};
    },
    populate: function(nro_ocorrencia, des_ocorrencia, dt_ocorrencia, validado, legado) {
      var self;
      self = this.ocorrencia;
      self[nro_ocorrencia] = {};
      self[nro_ocorrencia].nro_ocorrencia = nro_ocorrencia;
      self[nro_ocorrencia].des_ocorrencia = des_ocorrencia;
      self[nro_ocorrencia].dt_ocorrencia = dt_ocorrencia;
      self[nro_ocorrencia].validado = validado;
      return self[nro_ocorrencia].legado = legado;
    }
  };

  rest = new H5.Rest({
    url: H5.Data.restURL,
    table: H5.DB.ocorrencia.table,
    fields: "nro_ocorrencia, des_ocorrencia, dt_ocorrencia, validado, legado",
    order: "validado ASC, dt_ocorrencia ASC"
  });

  H5.DB.ocorrencia.data.init();

  _ref = rest.data;
  for (i in _ref) {
    properties = _ref[i];
    H5.DB.ocorrencia.data.populate(properties.nro_ocorrencia, properties.des_ocorrencia, properties.dt_ocorrencia, properties.validado, properties.legado);
  }

  $("#btn_manage1").addClass("active");

  $(".nav-sidebar a").on("click", function(event) {
    $(this).each(function() {
      return $(".nav-sidebar a").parent().removeClass("active");
    });
    $(this).parent().addClass("active");
    if ($(this).prop("id") === "btn-manage1") {
      $("#manage1").show();
      $("#manage2").hide();
      $("#manage3").hide();
      return $("#manage4").hide();
    } else if ($(this).prop("id") === "btn-manage2") {
      $("#manage1").hide();
      $("#manage2").show();
      $("#manage3").hide();
      return $("#manage4").hide();
    } else if ($(this).prop("id") === "btn-manage3") {
      $("#manage1").hide();
      $("#manage2").hide();
      $("#manage3").show();
      return $("#manage4").hide();
    } else if ($(this).prop("id") === "btn-manage4") {
      $("#manage1").hide();
      $("#manage2").hide();
      $("#manage3").hide();
      return $("#manage4").show();
    }
  });

  html = '';

  html = '<table class="table table-striped">';

  html += '  <thead>';

  html += '    <tr>';

  html += '      <th>Número da Ocorrência</th>';

  html += '      <th>Descrição da Ocorrência</th>';

  html += '      <th>Validado</th>';

  html += '      <th>Editar</th>';

  html += '      <th>Excluir</th>';

  html += '    </tr>';

  html += '  </thead>';

  html += '  <tbody id="fbody" class="fbody">';

  _ref1 = H5.DB.ocorrencia.data.ocorrencia;
  for (key in _ref1) {
    reg = _ref1[key];
    if (!reg.legado && reg.validado === 'N') {
      html += '    <tr>';
      html += '      <td>' + reg.nro_ocorrencia + '</td>';
      html += '      <td>' + reg.des_ocorrencia + '</td>';
      html += reg.validado === 'S' ? '      <td><span style="color:#0088CC;"><i class="icon-thumbs-up icon-white"></i></span></td>' : '      <td><span style="color:#D80000;"><i class="icon-thumbs-down icon-white"></i></span></td>';
      html += '      <td><a data-toggle="modal" class="editOcorrencia" data-ocorrencia="' + reg.nro_ocorrencia + '"href="#editMeModal"><i class="icon-edit icon-white"></i></a></td>';
      html += '      <td><a class="removeOcorrencia" data-ocorrencia="' + reg.nro_ocorrencia + '"href="#"><i class="icon-trash icon-white"></i></a></td>';
      html += '    </tr>';
    }
  }

  html += '  </tbody>';

  html += '</table>';

  $("#tableNaoValidado").html(html);

  html = '';

  html = '<table class="table table-striped">';

  html += '  <thead>';

  html += '    <tr>';

  html += '      <th>Número da Ocorrência</th>';

  html += '      <th>Descrição da Ocorrência</th>';

  html += '      <th>Validado</th>';

  html += '      <th>Editar</th>';

  html += '      <th>Excluir</th>';

  html += '    </tr>';

  html += '  </thead>';

  html += '  <tbody id="fbody" class="fbody">';

  _ref2 = H5.DB.ocorrencia.data.ocorrencia;
  for (key in _ref2) {
    reg = _ref2[key];
    if (!reg.legado && reg.validado === 'S') {
      html += '    <tr>';
      html += '      <td>' + reg.nro_ocorrencia + '</td>';
      html += '      <td>' + reg.des_ocorrencia + '</td>';
      html += reg.validado === 'S' ? '      <td><span style="color:#0088CC;"><i class="icon-thumbs-up icon-white"></i></span></td>' : '      <td><span style="color:#D80000;"><i class="icon-thumbs-down icon-white"></i></span></td>';
      html += '      <td><a data-toggle="modal" class="editOcorrencia" data-ocorrencia="' + reg.nro_ocorrencia + '"href="#editMeModal"><i class="icon-edit icon-white"></i></a></td>';
      html += '      <td><a class="removeOcorrencia" data-ocorrencia="' + reg.nro_ocorrencia + '"href="#"><i class="icon-trash icon-white"></i></a></td>';
      html += '    </tr>';
    }
  }

  html += '  </tbody>';

  html += '</table>';

  $("#tableValidado").html(html);

  $("#searchInput").keyup(function() {
    var data, jo;
    data = this.value.split(" ");
    jo = $(".fbody").find("tr");
    if (this.value === "") {
      jo.show();
      return;
    }
    jo.hide();
    return jo.filter(function(i, v) {
      var $t, element, index, _i, _len;
      $t = $(this);
      for (index = _i = 0, _len = data.length; _i < _len; index = ++_i) {
        element = data[index];
        if ($t.is(":contains('" + data[index] + "')")) {
          return true;
        }
      }
      return false;
    }).show();
  }).focus(function() {
    this.value = "";
    return $(this).unbind('focus');
  });

  $('#editMeModal').modal({
    keyboard: false,
    backdrop: false,
    show: false
  });

  $("#editMeModal").draggable({
    handle: ".modal-header"
  });

  $('#editMeModal').on('hidden', function() {
    return deleteTempData();
  });

  deleteTempData = function() {
    var nroOcorrencia;
    nroOcorrencia = $(window.top.form_frame_edit.document.getElementById("comunicado")).val();
    rest = new window.parent.H5.Rest({
      url: window.parent.H5.Data.restURL,
      table: "tmp_ocorrencia_produto",
      parameters: "nro_ocorrencia%3D" + nroOcorrencia,
      restService: "ws_deletequery.php"
    });
    rest = new window.parent.H5.Rest({
      url: window.parent.H5.Data.restURL,
      table: "tmp_pol",
      parameters: "nro_ocorrencia%3D" + nroOcorrencia,
      restService: "ws_deletequery.php"
    });
    rest = new window.parent.H5.Rest({
      url: window.parent.H5.Data.restURL,
      table: "tmp_lin",
      parameters: "nro_ocorrencia%3D" + nroOcorrencia,
      restService: "ws_deletequery.php"
    });
    return rest = new window.parent.H5.Rest({
      url: window.parent.H5.Data.restURL,
      table: "tmp_pon",
      parameters: "nro_ocorrencia%3D" + nroOcorrencia,
      restService: "ws_deletequery.php"
    });
  };

  $("a.editOcorrencia").on("click", function(event) {
    var nroOcorrencia;
    nroOcorrencia = $(this).attr("data-ocorrencia");
    $("#nroOcorrenciaLoadAdmin").val(nroOcorrencia);
    H5.formType = 'validation';
    return $("#formLoadAdmin").submit();
  });

  $("a.removeOcorrencia").on("click", function(event) {
    var nroOcorrencia;
    event.preventDefault();
    if (confirm("Você deseja excluir essa linha do banco de dados?")) {
      nroOcorrencia = $(this).attr("data-ocorrencia");
      rest = new H5.Rest({
        url: H5.Data.restURL,
        functionName: "f_deleteOcorrencia",
        parameters: nroOcorrencia,
        restService: "ws_functioncall.php"
      });
      if (rest.data.length === 1) {
        return $(this).closest('tr').remove();
      }
    } else {
      return alert("Operação cancelada");
    }
  });

  $("#manag").hide();

  $("#searchCPF").mask("99999999999");

  $("#searchPerson").on("click", function() {
    console.log("search for persons info");
    return $.ajax({
      url: window.location.href.replace("#", "") + "index.php/Auth/search_user",
      dataType: 'json',
      type: 'get',
      data: {
        'cpf': $("#searchCPF").val()
      },
      success: function(data) {
        $("#inputNome").val(data.Nome);
        $("#inputEmail").val(data.Desc_Email);
        $("#inputEmail").val(data.Desc_Email);
        return $("#inputTelefone").val(data.Telefone);
      },
      error: function(data, status) {
        $("#inputNome").val("");
        $("#inputEmail").val("");
        $("#inputEmail").val("");
        $("#inputTelefone").val("");
        return console.log('CPF não encontrado!');
      }
    });
  });

  $("#storePerson").on("click", function() {
    console.log("send form to save");
    return $.ajax({
      url: window.location.href.replace("#", "") + "index.php/Auth/create_intern_user",
      dataType: 'json',
      type: 'post',
      data: {
        'id_perfil': document.getElementById('selectPerfil').value,
        'cpf': $('#searchCPF').val(),
        'nome': $('#inputNome').val()
      },
      success: function(data) {
        return $("#infoBox").html("Enviado para o servidor!").fadeOut(2000);
      },
      error: function(data, status) {
        $("#inputNome").val("");
        $("#inputEmail").val("");
        $("#inputEmail").val("");
        $("#inputTelefone").val("");
        return $("#errorBox").html("Enviado para o servidor!").fadeOut(2000);
      }
    });
  });

  $("#validationSubmit").on('click', function() {
    var input;
    input = document.createElement('input');
    input.style = 'display:none;';
    input.id = 'generatepdf';
    input.name = 'generatepdf';
    input.value = 'true';
    window.top.form_frame_edit.document.formAcidentes.appendChild(input);
    return window.top.form_frame_edit.document.formAcidentes.submit();
  });

}).call(this);
