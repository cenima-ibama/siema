// Generated by CoffeeScript 1.6.3
(function() {
  H5.Data.restURL = "http://" + document.domain + "/siema/rest";

  $(document).ready(function() {
    var GeoSearch, Marker, addSelection, bingKey, binghybrid, collapse, date, disabled, drawControl, drawnItems, history, idOcorrencia, latlng, minimapView, nroComunicado, rest, seconds, subjects, table, value, _tipoDanoIdentificado, _tipoEvento, _tipoFonteInformacao, _tipoInstituicaoAtuando, _tipoLocalizacao, _tipoProduto;
    _tipoLocalizacao = null;
    _tipoEvento = null;
    _tipoDanoIdentificado = null;
    _tipoInstituicaoAtuando = null;
    _tipoFonteInformacao = null;
    _tipoProduto = null;
    idOcorrencia = null;
    if (!$("#comunicado").val()) {
      date = new Date();
      seconds = parseInt(date.getSeconds() + (date.getHours() * 60 * 60), 10);
      nroComunicado = "" + parseInt(date.getFullYear(), 10) + parseInt(date.getMonth() + 1, 10) + parseInt(date.getDate(), 10) + seconds;
      $("#comunicado").val(nroComunicado);
      $("#nroComunicado").html(nroComunicado);
    }
    rest = new H5.Rest({
      url: H5.Data.restURL,
      table: "ocorrencia",
      fields: "id_ocorrencia",
      parameters: "nro_ocorrencia%3D'" + $("#comunicado").prop('value') + "'"
    });
    $.each(rest.data, function(e, prop) {
      return $.each(prop, function(nameField, nameValue) {
        return idOcorrencia = nameValue;
      });
    });
    rest = new H5.Rest({
      url: H5.Data.restURL,
      table: "produto",
      fields: "id_produto,nome,num_onu,classe_risco",
      order: "nome"
    });
    _tipoProduto = rest.data;
    history = [];
    collapse = 2;
    $('#addMeModal').on('hidden', function() {
      var btnBack;
      history = [];
      collapse = 2;
      btnBack = document.getElementById("modalBtnBack");
      btnBack.href = '#tab1';
      $("#modalBtnBack").tab('show');
      $("#modalBtnBack").show();
      $("#modalBtnNext").show();
      $("#submit").hide();
      $("#modalBtnCancel").hide();
      $("#btnClose").hide();
      return $(".modal-footer").show();
    });
    $("#btn-form").click(function(event) {
      return $(".modal-footer").hide();
    });
    $("#modalBtnBack").click(function(event) {
      var btnNext, tab;
      event.preventDefault();
      btnNext = document.getElementById("modalBtnNext");
      if (history.length > 0) {
        tab = history.pop();
        this.href = tab.tab;
        collapse = tab.collapse;
      }
      $(".modal-footer").hide();
      return $(this).tab('show');
    });
    $("#modalBtnCancel").click(function(event) {
      var btnBack, btnNext, tab;
      event.preventDefault();
      btnNext = document.getElementById("modalBtnNext");
      btnBack = document.getElementById("modalBtnBack");
      if (history.length > 0) {
        tab = history.pop();
        this.href = tab.tab;
        collapse = tab.collapse;
      }
      $(".modal-footer").show();
      $(btnNext).show();
      $(btnBack).show();
      $("#submit").hide();
      $(this).hide();
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "tmp_ocorrencia_produto",
        restService: "ws_deletequery.php"
      });
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "tmp_pol",
        restService: "ws_deletequery.php"
      });
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "tmp_lin",
        restService: "ws_deletequery.php"
      });
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "tmp_pon",
        restService: "ws_deletequery.php"
      });
      return $(this).tab('show');
    });
    $("#btnBeginForm").click(function(event) {
      var btnLogout, checkedUser, containerProgress, i, progressAnimetion, progressBar, textProgress, tipoForm;
      if ((document.getElementById('divLogin')) == null) {
        progressBar = document.getElementById("authProgress");
        textProgress = document.getElementById("textProgress");
        containerProgress = document.getElementById("containerProgress");
        checkedUser = document.getElementById("checkedUser");
        tipoForm = document.getElementById("tipoForm");
        btnLogout = document.getElementById("btnLogout");
        $(tipoForm).hide();
        $(btnLogout).hide();
        i = 0;
        progressAnimetion = setInterval(function() {
          $(progressBar).width(i++ + "0%");
          if (i === 15) {
            $(containerProgress).hide();
            $(textProgress).hide();
            $(textProgress).html('Usuário registrado.');
            $(textProgress).fadeToggle();
            $(checkedUser).show();
            $(tipoForm).show();
            $(btnLogout).show();
            return clearInterval(progressAnimetion);
          }
        }, 100);
      }
      if ($("#containerProgress").is(":hidden")) {
        $(tipoForm).show();
        return $(btnLogout).show();
      }
    });
    $("#modalBtnNext").click(function(event) {
      var action, btnLogout, checkedUser, containerProgress, defaultHtml, hasOleo, i, isAcidOleo, isAtual, isOutros, isPubExt, isServIBAMA, progressAnimetion, progressBar, textProgress, tipoForm;
      event.preventDefault();
      history.push({
        tab: "#tab" + collapse,
        collapse: collapse
      });
      this.href = "#tab" + ++collapse;
      if (("#tab" + collapse) === "#tab2") {
        if ((document.getElementById('divLogin')) == null) {
          progressBar = document.getElementById("authProgress");
          textProgress = document.getElementById("textProgress");
          containerProgress = document.getElementById("containerProgress");
          checkedUser = document.getElementById("checkedUser");
          tipoForm = document.getElementById("tipoForm");
          btnLogout = document.getElementById("btnLogout");
          $(tipoForm).hide();
          $(btnLogout).hide();
          i = 0;
          progressAnimetion = setInterval(function() {
            $(progressBar).width(i++ + "0%");
            if (i === 15) {
              $(containerProgress).hide();
              $(textProgress).hide();
              $(textProgress).html('Usuário registrado.');
              $(textProgress).fadeToggle();
              $(checkedUser).show();
              $(tipoForm).show();
              $(btnLogout).show();
              return clearInterval(progressAnimetion);
            }
          }, 100);
        }
        $(".modal-footer").hide();
      } else {
        $(".modal-footer").show();
        if (("#tab" + collapse) === "#tab4") {
          isPubExt = document.getElementById("radioPubExt").checked;
          if (isPubExt) {
            collapse = 5;
            this.href = "#tab" + 5;
          }
        } else if (("#tab" + collapse) === "#tab8") {
          isAcidOleo = document.getElementById("optionsAcidenteOleo").checked;
          isOutros = document.getElementById("optionsAcidenteOutros").checked;
          isAtual = document.getElementById("optionsAtualizarAcidente").checked;
          hasOleo = document.getElementById("hasOleo");
          isServIBAMA = document.getElementById("isServIBAMA");
          hasOleo.checked = isAcidOleo;
        }
      }
      if (("#tab" + collapse) === "#tab8") {
        $("#submit").show();
        $("#modalBtnNext").hide();
        $("#modalBtnBack").hide();
        $("#modalBtnCancel").show();
        if (isAtual) {
          if ($("#inputRegistro").prop("value") !== "") {
            defaultHtml = document.getElementById("defaultHtml");
            if (defaultHtml.innerHTML === "") {
              defaultHtml.innerHTML = $("#formLoad").prop("action");
            }
            action = defaultHtml.innerHTML + "/" + $("#inputRegistro").prop("value");
            $("#formLoad").prop("action", action);
            $("#formLoad").submit();
          } else {
            $("#inputRegistro").focus();
          }
        } else {
          $("#formCreate").submit();
        }
      }
      return $(this).tab('show');
    });
    $("#tipoForm").click(function(event) {
      event.preventDefault();
      history.push({
        tab: "#tab2",
        collapse: collapse
      });
      this.href = "#tab7";
      collapse = 7;
      $(".modal-footer").show();
      return $(this).tab('show');
    });
    $("#denunciaAnonima").click(function(event) {
      event.preventDefault();
      history.push({
        tab: "#tab2",
        collapse: collapse
      });
      this.href = "#tab7";
      collapse = 7;
      $(".modal-footer").show();
      return $(this).tab('show');
    });
    $("#btnCadastrar").click(function(event) {
      event.preventDefault();
      history.push({
        tab: "#tab2",
        collapse: collapse
      });
      this.href = "#tab3";
      collapse = 3;
      $(".modal-footer").show();
      return $(this).tab('show');
    });
    bingKey = "AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h";
    binghybrid = new L.BingLayer(bingKey, {
      type: "AerialWithLabels",
      attribution: ""
    });
    $('#minimap').css("height", "371px");
    $('#minimap').css("width", "100%");
    $('#minimap').css("box-shadow", "0 0 0 1px rgba(0, 0, 0, 0.15)");
    $('#minimap').css("border-radius", "4px");
    Marker = new L.Marker([0, 0], {
      draggable: true
    });
    Marker.on("move", function(event) {
      var latlng;
      $("#inputLat").val(event.latlng.lat);
      $("#inputLng").val(event.latlng.lng);
      $("#inputEPSG").val("4674");
      $("#inputEPSG").prop("disabled", "disabled");
      if (!window.parent.H5.isMobile.any()) {
        latlng = new L.LatLng($("#inputLat").prop("value"), $("#inputLng").prop("value"));
        return window.parent.H5.Map.base.setView(latlng, minimapView.getZoom(), false);
      }
    });
    minimapView = new L.Map("minimap", {
      center: new L.LatLng(-10.0, -50.0),
      zoom: 3,
      layers: [binghybrid],
      zoomControl: true
    });
    drawnItems = new L.FeatureGroup();
    minimapView.addLayer(drawnItems);
    drawControl = new L.Control.Draw({
      draw: {
        marker: false
      },
      edit: {
        featureGroup: drawnItems
      }
    });
    minimapView.addControl(drawControl);
    minimapView.on('draw:created', function(e) {
      var firstPoint, layer, sql, type;
      type = e.layerType;
      layer = e.layer;
      console.log(e.layer);
      drawnItems.addLayer(layer);
      if (type === 'polygon') {
        firstPoint = "";
        sql = "(id_tmp_pol, id_ocorrencia, shape) values ( " + layer._leaflet_id + "," + idOcorrencia + ",ST_MakePolygon(ST_GeomFromText('LINESTRING(";
        $.each(layer._latlngs, function() {
          if (firstPoint === "") {
            firstPoint = this;
          }
          sql = sql + this.lat + " " + this.lng;
          return sql = sql + ",";
        });
        sql = sql + firstPoint.lat + " " + firstPoint.lng + ")', " + $("#inputEPSG").val() + ")))";
        console.log(sql);
        return rest = new H5.Rest({
          url: H5.Data.restURL,
          fields: sql,
          table: "tmp_pol",
          restService: "ws_insertquery.php"
        });
      } else if (type === 'polyline') {
        firstPoint = "";
        sql = "(id_tmp_lin, id_ocorrencia, shape) values ( " + layer._leaflet_id + "," + idOcorrencia + ",ST_GeomFromText('LINESTRING(";
        $.each(layer._latlngs, function() {
          if (firstPoint === "") {
            firstPoint = true;
            return sql = sql + this.lat + " " + this.lng;
          } else {
            return sql = sql + "," + this.lat + " " + this.lng;
          }
        });
        sql = sql + ")', " + $("#inputEPSG").val() + "))";
        console.log(sql);
        return rest = new H5.Rest({
          url: H5.Data.restURL,
          fields: sql,
          table: "tmp_lin",
          restService: "ws_insertquery.php"
        });
      }
    });
    minimapView.on('draw:deleted', function(e) {
      return console.log(e);
    });
    GeoSearch = {
      _provider: new L.GeoSearch.Provider.Google,
      _geosearch: function(qry) {
        var error, results, url;
        try {
          console.log(qry);
          if (typeof this._provider.GetLocations === "function") {
            return results = this._provider.GetLocations(qry, (function(results) {
              console.log(results);
              return this._processResults(results);
            }).bind(this));
          } else {
            url = this._provider.GetServiceUrl(qry);
            return $.getJSON(url, data((function() {
              var error;
              try {
                results = this._provider.ParseJSON(data);
                return this._processResults(results);
              } catch (_error) {
                error = _error;
                return this._printError(error);
              }
            }).bind(this)));
          }
        } catch (_error) {
          error = _error;
          return this._printError(error);
        }
      },
      _processResults: function(results) {
        if (results) {
          return this._showLocation(results[0]);
        }
      },
      _showLocation: function(location) {
        var latlng;
        latlng = new L.LatLng(location.Y, location.X);
        if (!minimapView.hasLayer(Marker)) {
          minimapView.addLayer(Marker);
        }
        Marker.setLatLng(latlng).update();
        minimapView.setView(latlng, 15, false);
        if (!window.parent.H5.isMobile.any()) {
          window.parent.H5.Map.base.setView(latlng, 10, false);
        }
        $("#inputLat").val(location.Y);
        return $("#inputLng").val(location.X);
      },
      _printError: function(error) {
        return alert("Erro na Busca: " + error);
      }
    };
    $("#inputLat, #inputLng").on('change', function(event) {
      var latlng;
      if ((($("#inputLat").prop("value")) !== "") && (($("#inputLng").prop("value")) !== "")) {
        latlng = new L.LatLng($("#inputLat").prop("value"), $("#inputLng").prop("value"));
        if (!minimapView.hasLayer(Marker)) {
          minimapView.addLayer(Marker);
        }
        Marker.setLatLng(latlng).update();
        minimapView.setView(latlng, 8, false);
      }
      if (!window.parent.H5.isMobile.any()) {
        window.parent.H5.Map.base.setView(latlng, 8, false);
      }
      $("#inputEPSG").val("");
      return $("#inputEPSG").removeAttr("disabled");
    });
    $("#inputEndereco").on('keyup', function(event) {
      var enterKey, municipio, uf;
      enterKey = 13;
      if (event.keyCode === enterKey) {
        municipio = $("#inputMunicipio").val();
        uf = $("#inputUF").val();
        if (municipio.length === 0 && uf.length === 0) {
          return GeoSearch._geosearch(this.value);
        } else {
          return GeoSearch._geosearch(this.value + ", " + municipio + " - " + uf);
        }
      }
    });
    minimapView.on("click", function(event) {
      if (!minimapView.hasLayer(Marker)) {
        minimapView.addLayer(Marker);
      }
      Marker.setLatLng(event.latlng).update();
      $("#inputLat").prop("value", event.latlng.lat);
      $("#inputLng").prop("value", event.latlng.lng);
      $("#inputEPSG").val("4674");
      return $("#inputEPSG").prop("disabled", "disabled");
    });
    if ((($("#inputLat").prop("value")) !== "") && (($("#inputLng").prop("value")) !== "")) {
      latlng = new L.LatLng($("#inputLat").prop("value"), $("#inputLng").prop("value"));
      disabled = $("#inputEPSG").prop("disabled");
      value = $("#inputEPSG").prop("value");
      Marker.setLatLng(latlng).update();
      $("#inputEPSG").prop("disabled", disabled);
      $("#inputEPSG").prop("value", value);
      minimapView.addLayer(Marker);
    }
    addSelection = function(idField, value) {
      var field;
      field = document.getElementById(idField);
      return field.innerHTML = value;
    };
    $(function() {
      var labelOutros, subjects, tipoDanoIdentificado, tipoEvento, tipoFonteInformacao, tipoInstituicaoAtuando, tipoLocalizacao, total,
        _this = this;
      tipoLocalizacao = document.getElementById("tipoLocalizacao");
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "tipo_localizacao",
        fields: "id_tipo_localizacao, des_tipo_localizacao",
        order: "id_tipo_localizacao"
      });
      total = rest.data.length;
      labelOutros = "";
      $.each(rest.data, function(key, value) {
        var input, label, span;
        input = document.createElement("input");
        input.id = "TL" + value.id_tipo_localizacao;
        input.name = "tipoLocalizacao[]";
        input.type = "checkbox";
        input.value = value.id_tipo_localizacao;
        if (($("#semOrigem").attr("checked")) != null) {
          input.disabled = "disabled";
        }
        $("span[data-id='postTL']").each(function() {
          if (this.innerHTML === input.value) {
            input.checked = "checked";
            $(this).remove();
            return addSelection('labelInputCompOrigem', value.des_tipo_localizacao);
          }
        });
        $(input).click(function() {
          if ($(this).is(":checked")) {
            return addSelection('labelInputCompOrigem', value.des_tipo_localizacao);
          }
        });
        span = document.createElement("span");
        span.innerHTML = value.des_tipo_localizacao;
        label = document.createElement("label");
        $(label).addClass("checkbox");
        $(label).append(input, span);
        if (value.des_tipo_localizacao !== "Outro(s)") {
          return $(tipoLocalizacao).append(label);
        } else {
          return labelOutros = label;
        }
      });
      $(tipoLocalizacao).append(labelOutros);
      _tipoLocalizacao = tipoLocalizacao;
      tipoEvento = document.getElementById("tipoEvento");
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "tipo_evento",
        fields: "id_tipo_evento, nome",
        order: "id_tipo_evento"
      });
      total = rest.data.length;
      labelOutros = "";
      $.each(rest.data, function(key, value) {
        var input, label, span;
        input = document.createElement("input");
        input.id = "TE" + value.id_tipo_evento;
        input.name = "tipoEvento[]";
        input.type = "checkbox";
        input.value = value.id_tipo_evento;
        if (($("#semEvento").attr("checked")) != null) {
          input.disabled = "disabled";
        }
        $("span[data-id='postTE']").each(function() {
          if (this.innerHTML === input.value) {
            input.checked = "checked";
            return $(this).remove();
          }
        });
        $(input).click(function() {
          if ($(this).is(":checked")) {
            return addSelection('labelInputCompEvento', value.nome);
          }
        });
        span = document.createElement("span");
        span.innerHTML = value.nome;
        label = document.createElement("label");
        $(label).addClass("checkbox");
        $(label).append(input, span);
        if (value.nome !== "Outro(s)") {
          return $(tipoEvento).append(label);
        } else {
          return labelOutros = label;
        }
      });
      $(tipoEvento).append(labelOutros);
      _tipoEvento = tipoEvento;
      tipoDanoIdentificado = document.getElementById("tipoDanoIdentificado");
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "tipo_dano_identificado",
        fields: "id_tipo_dano_identificado, nome",
        order: "id_tipo_dano_identificado"
      });
      total = rest.data.length;
      labelOutros = "";
      $.each(rest.data, function(key, value) {
        var input, label, span;
        input = document.createElement("input");
        input.id = "TDI" + value.id_tipo_dano_identificado;
        input.name = "tipoDanoIdentificado[]";
        input.type = "checkbox";
        input.value = value.id_tipo_dano_identificado;
        if (($("#semDanos").attr("checked")) != null) {
          input.disabled = "disabled";
        }
        $("span[data-id='postTDI']").each(function() {
          if (this.innerHTML === input.value) {
            input.checked = "checked";
            return $(this).remove();
          }
        });
        $(input).click(function() {
          if ($(this).is(":checked")) {
            return addSelection('labelInputCompDano', value.nome);
          }
        });
        span = document.createElement("span");
        span.innerHTML = value.nome;
        label = document.createElement("label");
        $(label).addClass("checkbox");
        $(label).append(input, span);
        if (value.nome !== "Outro(s)") {
          return $(tipoDanoIdentificado).append(label);
        } else {
          return labelOutros = label;
        }
      });
      $(tipoDanoIdentificado).append(labelOutros);
      _tipoDanoIdentificado = tipoDanoIdentificado;
      tipoInstituicaoAtuando = document.getElementById("tipoInstituicaoAtuando");
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "instituicao_atuando_local",
        fields: "id_instituicao_atuando_local, nome",
        order: "id_instituicao_atuando_local"
      });
      total = rest.data.length;
      labelOutros = "";
      $.each(rest.data, function(key, value) {
        var input, label, span;
        input = document.createElement("input");
        input.id = "IAL" + value.id_instituicao_atuando_local;
        input.name = "instituicaoAtuandoLocal[]";
        input.type = "checkbox";
        input.value = value.id_instituicao_atuando_local;
        if (($("#semInstituicao").attr("checked")) != null) {
          input.disabled = "disabled";
        }
        $("span[data-id='postIAL']").each(function() {
          if (this.innerHTML === input.value) {
            input.checked = "checked";
            return $(this).remove();
          }
        });
        $(input).click(function() {
          if ($(this).is(":checked")) {
            return addSelection('labelInputCompInstituicao', value.nome);
          }
        });
        span = document.createElement("span");
        span.innerHTML = value.nome;
        label = document.createElement("label");
        $(label).addClass("checkbox");
        $(label).append(input, span);
        if (value.nome !== "Outra(s)") {
          return $(tipoInstituicaoAtuando).append(label);
        } else {
          return labelOutros = label;
        }
      });
      $(tipoInstituicaoAtuando).append(labelOutros);
      _tipoInstituicaoAtuando = tipoInstituicaoAtuando;
      tipoFonteInformacao = document.getElementById("tipoFonteInformacao");
      rest = new H5.Rest({
        url: H5.Data.restURL,
        table: "tipo_fonte_informacao",
        fields: "id_tipo_fonte_informacao, nome",
        order: "id_tipo_fonte_informacao"
      });
      total = rest.data.length;
      labelOutros = "";
      $.each(rest.data, function(key, value) {
        var input, label, span;
        input = document.createElement("input");
        input.id = "TFI" + value.id_tipo_fonte_informacao;
        input.name = "tipoFonteInformacao[]";
        input.type = "checkbox";
        input.value = value.id_tipo_fonte_informacao;
        $("span[data-id='postTFI']").each(function() {
          if (this.innerHTML === input.value) {
            input.checked = "checked";
            return $(this).remove();
          }
        });
        span = document.createElement("span");
        span.innerHTML = value.nome;
        label = document.createElement("label");
        $(label).addClass("checkbox");
        $(label).append(input, span);
        if (value.nome !== "Outra(s)") {
          return $(tipoFonteInformacao).append(label);
        } else {
          return labelOutros = label;
        }
      });
      $(tipoFonteInformacao).append(labelOutros);
      _tipoFonteInformacao = tipoFonteInformacao;
      subjects = [];
      $.each(_tipoProduto, function() {
        return subjects.push(this.nome);
      });
      $("#nomeProduto").typeahead({
        source: subjects
      });
      $("#btnAddProduto").on('click', function() {
        return $.each(_tipoProduto, function() {
          var newRow, td;
          if (this.nome === $("#nomeProduto").prop('value')) {
            newRow = document.getElementById('tblProdutos').insertRow();
            td = newRow.insertCell();
            td.innerHTML = '<input name="produtos[]" value=' + this.id_produto + ' />' + '<input name=' + this.id_produto + '[]" value=' + this.id_produto + ' />' + (td.style = 'display:none;');
            td = newRow.insertCell();
            td.innerHTML = this.nome;
            td = newRow.insertCell();
            td.innerHTML = this.num_onu;
            td = newRow.insertCell();
            return td.innerHTML = this.classe_risco;
          }
        });
      });
      $("#semLocalizacao").on('click', function() {
        if ($(this).is(":checked")) {
          $("#inputLat").attr("disabled", "disabled");
          $("#inputLng").attr("disabled", "disabled");
          $("#inputEPSG").attr("disabled", "disabled");
          $("#inputMunicipio").attr("disabled", "disabled");
          $("#inputUF").attr("disabled", "disabled");
          $("#inputEndereco").attr("disabled", "disabled");
          $("#btnAddToMap").attr("disabled", "disabled");
          return $("button[data-id='slctLicenca']").attr("disabled", "disabled");
        } else {
          $("#inputLat").removeAttr("disabled");
          $("#inputLng").removeAttr("disabled");
          $("#inputEPSG").removeAttr("disabled");
          $("#inputMunicipio").removeAttr("disabled");
          $("#inputUF").removeAttr("disabled");
          $("#inputEndereco").removeAttr("disabled");
          $("#btnAddToMap").removeAttr("disabled");
          return $("button[data-id='slctLicenca']").removeAttr("disabled");
        }
      });
      $("#semNavioInstalacao").on('click', function() {
        if ($(this).is(":checked")) {
          $("#inputNomeNavio").attr("disabled", "disabled");
          return $("#inputNomeInstalacao").attr("disabled", "disabled");
        } else {
          $("#inputNomeNavio").removeAttr("disabled");
          return $("#inputNomeInstalacao").removeAttr("disabled");
        }
      });
      $("#semDataObs").on('click', function() {
        if ($(this).is(":checked")) {
          $("#inputDataObs").attr("disabled", "disabled");
          $("#inputHoraObs").attr("disabled", "disabled");
          $("#PerObsMatu").attr("disabled", "disabled");
          $("#PerObsVesper").attr("disabled", "disabled");
          $("#PerObsNotu").attr("disabled", "disabled");
          return $("#PerObsMadru").attr("disabled", "disabled");
        } else {
          $("#inputDataObs").removeAttr("disabled");
          $("#inputHoraObs").removeAttr("disabled");
          $("#PerObsMatu").removeAttr("disabled");
          $("#PerObsVesper").removeAttr("disabled");
          $("#PerObsNotu").removeAttr("disabled");
          return $("#PerObsMadru").removeAttr("disabled");
        }
      });
      $("#semDataInci").on('click', function() {
        if ($(this).is(":checked")) {
          $("#inputDataInci").attr("disabled", "disabled");
          $("#inputHoraInci").attr("disabled", "disabled");
          $("#PerInciMatu").attr("disabled", "disabled");
          $("#PerInciVesper").attr("disabled", "disabled");
          $("#PerInciNotu").attr("disabled", "disabled");
          return $("#PerInciMadru").attr("disabled", "disabled");
        } else {
          $("#inputDataInci").removeAttr("disabled");
          $("#inputHoraInci").removeAttr("disabled");
          $("#PerInciMatu").removeAttr("disabled");
          $("#PerInciVesper").removeAttr("disabled");
          $("#PerInciNotu").removeAttr("disabled");
          return $("#PerInciMadru").removeAttr("disabled");
        }
      });
      $("#semOrigem").on('click', function() {
        if ($(this).is(":checked")) {
          $("input[name='tipoLocalizacao[]']").each(function() {
            return $(this).attr("disabled", "disabled");
          });
          $("#inputOrigemOutro").attr("disabled", "disabled");
          return $("#inputCompOrigem").attr("disabled", "disabled");
        } else {
          $("input[name='tipoLocalizacao[]']").each(function() {
            return $(this).removeAttr("disabled");
          });
          $("#inputOrigemOutro").removeAttr("disabled");
          return $("#inputCompOrigem").removeAttr("disabled");
        }
      });
      $("#semEvento").on('click', function() {
        if ($(this).is(":checked")) {
          $("input[name='tipoEvento[]']").each(function() {
            return $(this).attr("disabled", "disabled");
          });
          $("#inputEventoOutro").attr("disabled", "disabled");
          return $("#inputCompEvento").attr("disabled", "disabled");
        } else {
          $("input[name='tipoEvento[]']").each(function() {
            return $(this).removeAttr("disabled");
          });
          $("#inputEventoOutro").removeAttr("disabled");
          return $("#inputCompEvento").removeAttr("disabled");
        }
      });
      $("#semProduto").on('click', function() {
        if ($(this).is(":checked")) {
          $("#myTable").attr("style", "display:none;");
          return $("#productsInfo").attr("style", "display:none;");
        } else {
          $("#myTable").removeAttr("style");
          return $("#productsInfo").removeAttr("style");
        }
      });
      $("#semSubstancia").on('click', function() {
        if ($(this).is(":checked")) {
          $("#inputTipoSubstancia").attr("disabled", "disabled");
          return $("#inputValorEstimado").attr("disabled", "disabled");
        } else {
          $("#inputTipoSubstancia").removeAttr("disabled");
          return $("#inputValorEstimado").removeAttr("disabled");
        }
      });
      $("#semCausa").on('click', function() {
        if ($(this).is(":checked")) {
          return $("#inputCausaProvavel").attr("disabled", "disabled");
        } else {
          return $("#inputCausaProvavel").removeAttr("disabled");
        }
      });
      $("#semDanos").on('click', function() {
        if ($(this).is(":checked")) {
          $("input[name='tipoDanoIdentificado[]']").each(function() {
            return $(this).attr("disabled", "disabled");
          });
          $("#inputDanoOutro").attr("disabled", "disabled");
          $("#inputCompDano").attr("disabled", "disabled");
          return $("#inputDesDanos").attr("disabled", "disabled");
        } else {
          $("input[name='tipoDanoIdentificado[]']").each(function() {
            return $(this).removeAttr("disabled");
          });
          $("#inputDanoOutro").removeAttr("disabled");
          $("#inputCompDano").removeAttr("disabled");
          return $("#inputDesDanos").removeAttr("disabled");
        }
      });
      $("#semInstituicao").on('click', function() {
        if ($(this).is(":checked")) {
          $("input[name='instituicaoAtuandoLocal[]']").each(function() {
            return $(this).attr("disabled", "disabled");
          });
          $("#inputInstituicaoOutro").attr("disabled", "disabled");
          return $("#inputCompInstituicao").attr("disabled", "disabled");
        } else {
          $("input[name='instituicaoAtuandoLocal[]']").each(function() {
            return $(this).removeAttr("disabled");
          });
          $("#inputInstituicaoOutro").removeAttr("disabled");
          return $("#inputCompInstituicao").removeAttr("disabled");
        }
      });
      return $("#semResponsavel").on('click', function() {
        if ($(this).is(":checked")) {
          $("#inputResponsavel").attr("disabled", "disabled");
          $("#inputCPFCNPJ").attr("disabled", "disabled");
          return $("#slctLicenca").attr("disabled", "disabled");
        } else {
          $("#inputResponsavel").removeAttr("disabled");
          $("#inputCPFCNPJ").removeAttr("disabled");
          return $("#slctLicenca").removeAttr("disabled");
        }
      });
    });
    $("#inputHoraObs").on('change', function() {
      var obsHour;
      if (($(this).prop('value')) !== "") {
        obsHour = parseInt($(this).prop('value').split(':')[0], 10);
        if (obsHour < 6) {
          $("#PerObsMadru").prop('checked', 'checked');
        } else if (obsHour < 12) {
          $("#PerObsMatu").prop('checked', 'checked');
        } else if (obsHour < 18) {
          $("#PerObsVesper").prop('checked', 'checked');
        } else {
          $("#PerObsNotu").prop('checked', 'checked');
        }
        return $("#divPeriodoObs").prop('style', 'display:none;');
      } else {
        return $("#divPeriodoObs").prop('style', '');
      }
    });
    $("#inputHoraInci").on('change', function() {
      var obsHour;
      if (($(this).prop('value')) !== "") {
        obsHour = parseInt($(this).prop('value').split(':')[0], 10);
        if (obsHour < 6) {
          $("#PerInciMadru").prop('checked', 'checked');
        } else if (obsHour < 12) {
          $("#PerInciMatu").prop('checked', 'checked');
        } else if (obsHour < 18) {
          $("#PerInciVesper").prop('checked', 'checked');
        } else {
          $("#PerInciNotu").prop('checked', 'checked');
        }
        return $("#divPeriodoInci").prop('style', 'display:none;');
      } else {
        return $("#divPeriodoInci").prop('style', 'display:auto;');
      }
    });
    if (($("#inputHoraObs").prop('value')) !== '') {
      $("#divPeriodoObs").prop('style', 'display:none;');
    }
    if (($("#inputHoraInci").prop('value')) !== '') {
      $("#divPeriodoInci").prop('style', 'display:none;');
    }
    $("#inputDataObs").mask("99/99/9999");
    $("#inputHoraObs").mask("99:99");
    $("#inputDataInci").mask("99/99/9999");
    $("#inputHoraInci").mask("99:99");
    $('#inputCompOrigem').add('#inputCompEvento').add('#inputCompInstituicao').add('#inputCompDano').add('#inputCausaProvavel').add('#inputMedidasTomadas').add('#inputDesOcorrencia').add('#inputDesObs').add('#inputDesDanos').maxlength({
      alwaysShow: true,
      threshold: 10,
      warningClass: "label label-info",
      limitReachedClass: "label label-important",
      placement: 'bottom',
      preText: '',
      separator: ' de ',
      postText: ' caracteres'
    });
    subjects = [];
    $.each(_tipoProduto, function() {
      var element;
      element = {
        value: this.id_produto,
        text: $.trim(this.nome) + '-' + $.trim(this.num_onu) + '-' + $.trim(this.classe_risco)
      };
      return subjects.push(element);
    });
    if ($(window.top.document.getElementById("optionsAtualizarAcidente")).is(":checked")) {
      return table = new H5.Table({
        container: "myTable",
        url: H5.Data.restURL,
        table: "ocorrencia_produto%20left%20join%20produto%20on%20(produto.id_produto%3Docorrencia_produto.id_produto)%20left%20join%20ocorrencia%20on%20(ocorrencia_produto.id_ocorrencia%3Docorrencia.id_ocorrencia)",
        primaryTable: 'ocorrencia_produto',
        parameters: "ocorrencia_produto.id_ocorrencia%3D'" + idOcorrencia + "'",
        fields: {
          id_ocorrencia_produto: {
            columnName: "Identificador",
            tableName: "id_ocorrencia_produto",
            isVisible: false,
            validation: null
          },
          nome: {
            columnName: "Nome da Substância - Nro. da Onu - Classe de Risco",
            tableName: "trim(nome) || '-' || trim(num_onu) || '-' || trim(classe_risco) as nome",
            primaryField: "id_produto",
            validation: null,
            searchData: subjects
          },
          quantidade: {
            columnName: "Qtd.",
            tableName: "quantidade",
            validation: null
          },
          unidade_medida: {
            columnName: "Unidade",
            tableName: "unidade_medida",
            validation: null
          },
          id_ocorrencia: {
            columnName: "Nro. Ocorrencia",
            tableName: "ocorrencia_produto.id_ocorrencia",
            defaultValue: idOcorrencia,
            validation: null,
            isVisible: false
          }
        },
        uniqueField: {
          field: "id_ocorrencia_produto",
          insertable: false
        }
      });
    } else {
      return table = new H5.Table({
        container: "myTable",
        url: H5.Data.restURL,
        table: "tmp_ocorrencia_produto%20left%20join%20produto%20on%20(produto.id_produto%3Dtmp_ocorrencia_produto.id_produto)",
        primaryTable: 'tmp_ocorrencia_produto',
        fields: {
          id_ocorrencia_produto: {
            columnName: "Identificador",
            tableName: "id_ocorrencia_produto",
            isVisible: false,
            validation: null
          },
          nome: {
            columnName: "Nome da Substância - Nro. da Onu - Classe de Risco",
            tableName: "trim(nome) || '-' || trim(num_onu) || '-' || trim(classe_risco) as nome",
            primaryField: "id_produto",
            validation: null,
            searchData: subjects
          },
          quantidade: {
            columnName: "Qtd.",
            tableName: "quantidade",
            validation: null
          },
          unidade_medida: {
            columnName: "Unidade",
            tableName: "unidade_medida",
            validation: null
          }
        },
        uniqueField: {
          field: "id_ocorrencia_produto",
          insertable: false
        }
      });
    }
  });

}).call(this);
