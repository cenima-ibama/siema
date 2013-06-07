@H5 = {

  version: 0.6

  company: "Hexgis <www.hexgis.com>"

  author: "Helmuth Saatkamp <helmuthdu@gmail.com>"

  isMobile:
    Android: ->
      return navigator.userAgent.match(/Android/i)
    BlackBerry: ->
      return navigator.userAgent.match(/BlackBerry/i)
    iOS: ->
      return navigator.userAgent.match(/iPhone|iPad|iPod/i)
    Opera: ->
      return navigator.userAgent.match(/Opera Mini/i)
    Windows: ->
      return navigator.userAgent.match(/IEMobile/i)
    any: ->
      return (@Android() || @BlackBerry() || @iOS() || @Opera() || @Windows())
}
