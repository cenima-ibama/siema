// Generated by CoffeeScript 1.6.3
(function() {
  $(document).ready(function() {
    var roundNumber;
    $('#addMeModal').modal({
      keyboard: false,
      backdrop: false,
      show: true
    });
    $("#addMeModal").draggable({
      handle: ".modal-header"
    });
    $('.selectpicker').selectpicker();
    $(".dropdown-menu input, .dropdown-menu label").click(function(e) {
      return e.stopPropagation();
    });
    $(".navbar a").on("click", function(event) {
      var where;
      $(this).each(function() {
        return $("a").parent().removeClass("active");
      });
      $(this).parent().addClass("active");
      if ($(this).prop("id") === "btn-map") {
        $("#dash").hide();
        $("#login").hide();
        $("#map").show();
        if (H5.Data.changed) {
          if (H5.Data.state === "Todos") {
            where = "ano='" + H5.Data.selectedYear + "'";
          } else {
            where = "estado='" + H5.Data.state + "' AND ano='" + H5.Data.selectedYear + "'";
          }
          H5.Map.layer.alerta.setOptions({
            where: where
          });
          H5.Map.layer.clusters.setOptions({
            where: where
          });
          H5.Map.layer.alerta.redraw();
          H5.Map.layer.clusters.redraw();
          H5.Data.changed = false;
        }
      } else if ($(this).prop("id") === "btn-charts") {
        $("#login").hide();
        $("#map").hide();
        $("#dash").show();
      } else if ($(this).prop("id") === "btn-login") {
        $("#dash").hide();
        $("#map").show();
        $("#login").show();
      }
      return $('.nav-collapse').collapse('hide');
    });
    String.prototype.toProperCase = function() {
      return this.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      });
    };
    roundNumber = function(number, digits) {
      var multiple, rndedNum;
      multiple = Math.pow(10, digits);
      rndedNum = Math.round(number * multiple) / multiple;
      return rndedNum;
    };
    return $(".loading").hide(700);
  });

}).call(this);
