###*
Created: vogdb Date: 5/4/13 Time: 1:54 PM
###

# L.Control.ActiveLayers = L.Control.Layers.extend({
L.Control.ActiveLayers = L.control.switch.extend(

  ###*
  Get currently active base layer on the map
  @return {Object} l where l.name - layer name on the control,
  l.layer is L.TileLayer, l.overlay is overlay layer.
  ###
  getActiveBaseLayer: ->
    @_activeBaseLayer = @_findActiveBaseLayer()
    # @_activeBaseLayer


  ###*
  Get currently active overlay layers on the map
  @return {{layerId: l}} where layerId is <code>L.stamp(l.layer)</code>
  and l @see #getActiveBaseLayer jsdoc.
  ###
  getActiveOverlayLayers: ->
    @_activeOverlayLayers = @_findActiveOverlayLayers()
    # @_activeOverlayLayers


  _findActiveBaseLayer: ->
    layers = @_layers
    for layerId of layers
      if @_layers.hasOwnProperty(layerId)
        layer = layers[layerId]
        return layer  if not layer.overlayer and @_map.hasLayer(layer.layer)
    throw new Error("Control doesn't have any active base layer!")
    return

  _findActiveOverlayLayers: ->
    result = {}
    layers = @_layers
    for layerId of layers
      if @_layers.hasOwnProperty(layerId)
        layer = layers[layerId]
        result[layerId] = layer  if layer.overlayer and @_map.hasLayer(layer.layer)
    result
)

L.control.activeLayers = (baseLayers, overlays, options) ->
  new L.Control.ActiveLayers(baseLayers, overlays, options)