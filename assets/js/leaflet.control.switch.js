// Generated by CoffeeScript 1.6.3
(function() {
  L.control["switch"] = L.Control.extend({
    options: {
      collapsed: true,
      position: "topright",
      autoZIndex: true,
      tab: "outros"
    },
    initialize: function(baseLayers, overlayers, tabs, options) {
      var _this = this;
      L.setOptions(this, options);
      this._layers = {};
      this._lastZIndex = 0;
      this._handlingClick = false;
      $.each(baseLayers, function(name, obj) {
        return _this._addLayer(obj.layer, name, false, false);
      });
      if (typeof tabs !== "undefined") {
        this._tabs = tabs;
      }
      return $.each(overlayers, function(name, obj) {
        var control;
        control = typeof obj.overlayControl === "boolean" ? obj.overlayControl : false;
        return _this._addLayer(obj.layer, name, true, control, obj.tab);
      });
    },
    onAdd: function(map) {
      this._initLayout();
      this._update();
      map.on("layeradd", this._onLayerChange, this).on("layerremove", this._onLayerChange, this);
      return this._container;
    },
    onRemove: function(map) {
      return map.off("layeradd", this._onLayerChange).off("layerremove", this._onLayerChange);
    },
    addBaseLayer: function(layer, name) {
      this._addLayer(layer, name);
      this._update();
      return this;
    },
    addOverLayer: function(layer, name, overlayControl, tab) {
      this._addLayer(layer, name, true, overlayControl, tab);
      this._update();
      return this;
    },
    removeLayer: function(layer) {
      var id;
      id = L.stamp(layer);
      delete this._layers[id];
      this._update();
      return this;
    },
    _initLayout: function() {
      var className, container, form, link, obj,
        _this = this;
      className = "switch-control-layers";
      container = this._container = L.DomUtil.create("div", "leaflet-bar " + className);
      container.setAttribute('aria-haspopup', true);
      if (!L.Browser.touch) {
        L.DomEvent.disableClickPropagation(container);
        L.DomEvent.on(container, "mousewheel", L.DomEvent.stopPropagation);
      } else {
        L.DomEvent.on(container, "click", L.DomEvent.stopPropagation);
      }
      form = this._form = L.DomUtil.create("form", className + "-list form-layer-list");
      if (this.options.collapsed) {
        L.DomEvent.on(container, "mouseover", this._expand, this).on(container, "mouseout", this._collapse, this);
        link = this._layersLink = L.DomUtil.create("a", className + "-toggle", container);
        link.href = "#";
        link.title = "Layers";
        L.DomEvent.on(link, "mouseover", this._activeTab, this);
        if (L.Browser.touch) {
          L.DomEvent.on(link, "click", L.DomEvent.stop).on(link, "click", this._expand, this);
        } else {
          L.DomEvent.on(link, "focus", this._expand, this);
        }
        this._map.on("click", this._collapse, this);
      } else {
        this._expand();
      }
      this._baseLayersList = L.DomUtil.create('div', className + '-base', form);
      if (typeof this._tabs === "undefined") {
        this._separator = L.DomUtil.create('div', className + '-separator', form);
      }
      this._overlayersList = L.DomUtil.create('div', className + '-overlayers', form);
      if (typeof this._tabs !== "undefined") {
        this._tabsOverLayers = L.DomUtil.create('ul', 'nav nav-tabs', form);
        $(this._tabsOverLayers).attr('id', 'tabsOverLayers');
        this._tabsContentOverLayers = L.DomUtil.create('div', 'tab-content', form);
        $(this._tabsContentOverLayers).attr('id', 'tabsContent');
        $.each(this._tabs, function(tab, obj) {
          _this._createTab(tab, obj);
          if (obj.tabs === void 0) {
            return _this._hasTabOutros = true;
          }
        });
        if (this._hasTabOutros) {
          obj = {
            icon: "http://" + document.domain + "/siema/assets/img/icons/world.png"
          };
          this._createTab("outros", obj);
        }
      }
      return $(container).append(form);
    },
    _createTab: function(tab, obj) {
      var id, newTab, newTabContent, newTabName;
      id = "tab" + tab;
      newTab = L.DomUtil.create('li', '', this._tabsOverLayers);
      newTabContent = L.DomUtil.create('div', 'tab-pane', this._tabsContentOverLayers);
      $(newTabContent).attr('id', tab);
      newTabName = L.DomUtil.create('a', '', newTab);
      $(newTabName).attr('id', id);
      $(newTabName).attr('href', '#' + tab);
      $(newTabName).attr('data-toggle', 'tab');
      if (obj.name) {
        newTabName.innerHTML = obj.name;
      } else {
        newTabName.innerHTML = '<img src=" ' + obj.icon + '" width="22px" height="22px">';
      }
      if (obj.selected) {
        this._selectedTab = newTabName;
        this._selectedTabContent = newTabContent;
      }
      L.DomEvent.on(newTabName, "click", (function() {
        this._selectedTab = newTabName;
        return this._selectedTabContent = newTabContent;
      }), this);
    },
    _activeTab: function() {
      return $(this._selectedTab).trigger("click");
    },
    _addLayer: function(layer, name, overlayer, overlayControl, tab) {
      var id;
      id = L.stamp(layer);
      this._layers[id] = {
        layer: layer,
        name: name,
        overlayer: overlayer,
        overlayControl: overlayControl,
        tab: tab
      };
      if (this.options.autoZIndex && layer.setZIndex) {
        this._lastZIndex++;
        return layer.setZIndex(this._lastZIndex);
      }
    },
    _update: function() {
      var baseLayersPresent, i, obj, overlayersPresent, _results;
      if (!this._container) {
        return;
      }
      this._baseLayersList.innerHTML = "";
      this._overlayersList.innerHTML = "";
      baseLayersPresent = false;
      overlayersPresent = false;
      _results = [];
      for (i in this._layers) {
        obj = this._layers[i];
        this._addItem(obj);
        overlayersPresent = overlayersPresent || obj.overlayer;
        baseLayersPresent = baseLayersPresent || !obj.overlayer;
        if (typeof this._tabs === "undefined") {
          _results.push(this._separator.style.display = (overlayersPresent && baseLayersPresent ? "" : "none"));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    },
    _onLayerChange: function(e) {
      var obj, type;
      obj = this._layers[L.stamp(e.layer)];
      if (!obj) {
        return;
      }
      if (!this._handlingClick) {
        this._update();
      }
      type = (obj.overlayer ? (e.type === "layeradd" ? "overlayeradd" : "overlayerremove") : (e.type === "layeradd" ? "baselayerchange" : null));
      if (type) {
        return this._map.fire(type, obj);
      }
    },
    _addItem: function(obj) {
      var checked, container, control, controlgroup, input, label, name, slider, toggle,
        _this = this;
      if (obj.overlayer) {
        if (this._tabs) {
          if (obj.tab) {
            container = document.getElementById(obj.tab);
          } else if (this._hasTabOutros) {
            container = document.getElementById("outros");
          }
        } else {
          container = this._overlayersList;
        }
      } else {
        container = this._baseLayersList;
      }
      controlgroup = L.DomUtil.create("div", "control-group", container);
      checked = this._map.hasLayer(obj.layer);
      label = L.DomUtil.create("label", "control-label", controlgroup);
      if ((obj.name.length < 12 && !obj.overlayControl) || !obj.overlayControl) {
        if (obj.name.length > 21) {
          name = obj.name.substr(0, 21) + "…";
          label.innerHTML = "<abbr title=\"" + obj.name + "\">" + name + "</abbr>";
        } else {
          label.innerHTML = obj.name;
        }
      } else {
        name = obj.name.substr(0, 12) + "…";
        label.innerHTML = "<abbr title=\"" + obj.name + "\">" + name + "</abbr>";
      }
      control = L.DomUtil.create("div", "control", controlgroup);
      toggle = L.DomUtil.create("div", "switch-small", control);
      if (!obj.overlayer) {
        L.DomUtil.addClass(toggle, "baseLayers");
      }
      input = L.DomUtil.create("input", "", toggle);
      if (obj.overlayControl) {
        input.type = "checkbox";
        L.DomUtil.addClass(input, "switch-control-layers-selector");
        if (obj.overlayControl) {
          slider = L.DomUtil.create("div", "", controlgroup);
          L.DomUtil.addClass(slider, "switch-control-layers-slider");
          $(slider).slider({
            min: 0,
            max: 100,
            value: 100,
            range: "min",
            slide: function(event, ui) {
              var _this = this;
              return obj.layer.eachLayer(function(layer) {
                if (layer.setOpacity) {
                  return layer.Opacity(ui.value / 100);
                } else {
                  return layer.setStyle({
                    fillOpacity: ui.value / 100,
                    opacity: ui.value / 100
                  });
                }
              });
            }
          });
        }
      } else {
        input.type = "radio";
        $(input).attr("name", "leaflet-base-layers");
      }
      input.defaultChecked = checked;
      input.layerId = L.stamp(obj.layer);
      $(toggle).bootstrapSwitch();
      $(toggle).on("switch-change", function(e, data) {
        if (!obj.overlayer) {
          $('.baseLayers').bootstrapSwitch('toggleRadioState');
        }
        return _this._onInputClick(input, obj);
      });
      return controlgroup;
    },
    _onInputClick: function(input, obj) {
      this._handlingClick = true;
      if (input.checked && !this._map.hasLayer(obj.layer)) {
        this._map.addLayer(obj.layer);
      } else {
        if (!(input.checked && this._map.hasLayer(obj.layer))) {
          this._map.removeLayer(obj.layer);
        }
      }
      return this._handlingClick = false;
    },
    _expand: function() {
      return L.DomUtil.addClass(this._form, "switch-control-layers-expanded");
    },
    _collapse: function() {
      return this._form.className = this._form.className.replace(" switch-control-layers-expanded", "");
    }
  });

}).call(this);
