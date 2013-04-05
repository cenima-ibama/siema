# PERIODOS E VARIAVEIS {{{
selectedState = "Todos"
today = new Date()
totalPeriodos = today.getFullYear() - 2005
periodos = new Array(totalPeriodos)

curYear = if today.getMonth() < 6 then today.getFullYear() else today.getFullYear() + 1
curMonth = new Date().getMonth()
curDay = new Date().getDate()

for i in [0..totalPeriodos]
  periodos[i] = (today.getFullYear() - i - 1) + "-" + (today.getFullYear() - i)

months =
  0: "Ago"
  1: "Set"
  2: "Out"
  3: "Nov"
  4: "Dez"
  5: "Jan"
  6: "Fev"
  7: "Mar"
  8: "Abr"
  9: "Mai"
  10: "Jun"
  11: "Jul"

estados = ["AC", "AM", "AP", "MA", "MT", "PA", "RO", "RR", "TO"]
tableAlerta =
  init: ->
    @states = {}
    for estado in estados
      @states[estado] = {}

  populate: (state, date, value) ->
    convertDate = (dateStr) ->
      dateStr = String(dateStr)
      dArr = dateStr.split("-")
      new Date(dArr[0], (dArr[1]) - 1, dArr[2])
    self = @states[state]
    self[date] = {}
    self[date].area = value
    self[date].date = convertDate(date)
    self[date].year = convertDate(date).getFullYear()
    self[date].month = convertDate(date).getMonth()
    self[date].day = convertDate(date).getDate()

$.ajax
  type: "GET"
  url: "../siscom/rest/v1/ws_geo_attributequery.php"
  data:
    table: "alerta_acumulado_diario"

  dataType: "jsonp"
  success: (data) ->
    tableAlerta.init()
    $.each data, (i, properties) ->
      tableAlerta.populate properties.estado, properties.data, parseFloat(properties.total)
  error: (error, status, desc) ->
    console.log status, desc
#}}}
# CHART1 {{{
chart1 = new Hash5GoogleCharts (
  type: "Line"
  container: "chart1"
  title: "Alerta DETER: Índice Diário"
  buttons:
    minimize: true
    maximize: true
  selects:
    months:
      0: 'Jan'
      1: 'Fev'
      2: 'Mar'
      3: 'Abr'
      4: 'Mai'
      5: 'Jun'
      6: 'Jul'
      7: 'Ago'
      8: 'Set'
      9: 'Out'
      10: 'Nov'
      11: 'Dez'
    years:
      2004: '2004'
      2005: '2005'
      2006: '2006'
      2007: '2007'
      2008: '2008'
      2009: '2009'
      2010: '2010'
      2011: '2011'
      2012: '2012'
      2013: '2013'
)
chart1.createContainer()

# make those options selected
chart1.yearsSlct.options[totalPeriodos+1].selected = true
chart1.monthsSlct.options[curMonth].selected = true

$("#slct-years").on "change", (event) ->
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()

$("#slct-months").on "change", (event) ->
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()

chart1.drawChart = ->
  createTable = (state) =>
    dayValue = 0
    for day in [1..monthDays]
      $.each tableAlerta.states[state], (key, reg) ->
        if dateStart <= reg.date <= dateEnd and reg.day is day
          dayValue += reg.area
          return false
      @data.setValue (day - 1), 1, Math.round((@data.getValue((day - 1), 1) + dayValue) * 100) / 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  @data.addColumn "number", "Dia"
  @data.addColumn "number", "Área"

  monthDays = new Date(@yearsSlct.value, @monthsSlct.value + 1, 0).getDate()
  dateStart = new Date(@yearsSlct.value, @monthsSlct.value, 1)
  dateEnd = new Date(@yearsSlct.value, @monthsSlct.value, monthDays)
  data = []

  # populate table with 0
  for day in [1..monthDays]
    data[0] = day
    data[1] = 0
    @data.addRow data

  # populate table with real values
  if selectedState is "Todos"
    $.each tableAlerta.states, (state, value) ->
      createTable state
  else
    createTable selectedState

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    legend: "none"
    chartArea:
      width: "70%"
      height: "70%"
    colors: ['#3ABCFC']
    vAxis:
      title: "Área Km2"
    hAxis:
      title: "Dias"
      gridlines:
        color: "#CCC"
        count: monthDays / 5
    animation:
      duration: 500
      easing: "inAndOut"

  @chart.draw @data, options
#}}}
# CHART2 {{{
chart2 = new Hash5GoogleCharts(
  type: "Area"
  container: "chart2"
  period: 2
  title: "Alerta DETER: Índice Mensal"
  buttons:
    minusplus: true
    minimize: true
    maximize: true
)
chart2.createContainer()

chart2.addBtn.onclick = ->
  chart2.options.period++
  chart2.drawChart()

chart2.delBtn.onclick = ->
  chart2.options.period--
  chart2.drawChart()

chart2.drawChart = ->
  # sum values
  sumValues = (year, month) ->
    sum = 0
    firstPeriod = new Date(year - 1, 7, 1)
    secondPeriod = new Date(year , 7, 0)
    if selectedState is "Todos"
      $.each tableAlerta.states, (key, state) ->
        $.each state, (key, reg) ->
          if reg.date >= firstPeriod and reg.date <= secondPeriod and reg.month is month
            sum += reg.area
    else
      $.each tableAlerta.states[selectedState], (key, reg) ->
        if reg.date >= firstPeriod and reg.date <= secondPeriod and reg.month is month
          sum += reg.area
    Math.round(sum * 100) / 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "mes"
  for i in [0...@options.period]
    @data.addColumn "number", periodos[i]

  for month of months
    data = [months[month]]
    month = parseInt month
    moth = if 7 <= (month + 7) <= 11 then month+= 7 else month-= 5
    for i in [1..@options.period]
      data[i] = sumValues(curYear - i + 1, month)
    @data.addRow data

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    focusTarget: "category"
    chartArea:
      width: "70%"
      height: "80%"
    colors: ['#3ABCFC', '#FC2121', '#D0FC3F', '#FCAC0A',
             '#67C2EF', '#FF5454', '#CBE968', '#FABB3D',
             '#77A4BD', '#CC6C6C', '#A6B576', '#C7A258']
    vAxis:
      title: "Área Km2"
    animation:
      duration: 500
      easing: "inAndOut"



  # Disabling the buttons while the chart is drawing.
  @addBtn.disabled = true
  @delBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.
    @addBtn.disabled = @options.period > totalPeriodos
    @delBtn.disabled = @options.period < 2

  @chart.draw @data, options
#}}}
# CHART3 {{{
chart3 = new Hash5GoogleCharts(
  type: "Bar"
  container: "chart3"
  period: 1
  title: "Alerta DETER: Período Atual"
  buttons:
    minusplus: true
    minimize: true
    maximize: true
)
chart3.createContainer()

chart3.addBtn.onclick = ->
  chart3.options.period++
  chart3.drawChart()

chart3.delBtn.onclick = ->
  chart3.options.period--
  chart3.drawChart()

chart3.drawChart = ->
  # sum values
  sumValues = (firstPeriod, secondPeriod) ->
    sum = 0
    if selectedState is "Todos"
      $.each tableAlerta.states, (key, state) ->
        $.each state, (key, reg) ->
          if firstPeriod <= reg.date <= secondPeriod
            sum += reg.area
            lastDay = reg.day
    else
      $.each tableAlerta.states[selectedState], (key, reg) ->
        if firstPeriod <= reg.date <= secondPeriod
          sum += reg.area
          lastDay = reg.day
    Math.round(sum * 100) / 100

  # sum total values
  sumTotalValues = (year) ->
    firstPeriod = new Date(year - 1, 7, 1)
    secondPeriod = new Date(year, 7, 0)
    sumValues firstPeriod, secondPeriod

  # sum average values
  sumAvgValues = (year) ->
    firstPeriod = new Date(year - 1, 7, 1)
    secondPeriod = new Date(year, curMonth, curDay)
    sumValues firstPeriod, secondPeriod

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "Ano"
  @data.addColumn "number", "Parcial"
  @data.addColumn "number", "Diferença"

  # populate table
  for i in [0..@options.period]
    data = [periodos[i]]
    sumTotal = sumTotalValues(curYear - i)
    sumAvg = sumAvgValues(curYear - i)
    data[1] = sumAvg
    data[2] = Math.round((sumTotal - sumAvg) * 100) / 100
    @data.addRow data

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    focusTarget: "category"
    chartArea:
      width: "68%"
      height: "76%"
    colors: ['#3ABCFC', '#FC2121']
    vAxis:
      title: "Periodos"
    hAxis:
      title: "Área Km2"
    bar:
      groupWidth: "80%"
    isStacked: true
    animation:
      duration: 500
      easing: "inAndOut"

  # Disabling the buttons while the chart is drawing.
  @addBtn.disabled = true
  @delBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.
    @addBtn.disabled = @options.period > totalPeriodos - 1
    @delBtn.disabled = @options.period < 2

  @chart.draw @data, options
#}}}
# CHART4 {{{
chart4 = new Hash5GoogleCharts(
  type: "Area"
  container: "chart4"
  title: "Alerta DETER: Todos os Períodos"
  buttons:
    minimize: true
    maximize: true
)
chart4.createContainer()

chart4.drawChart = ->
  # sum values
  sumValues = (year) ->
    sum = 0
    firstPeriod = new Date(year - 1, 7, 1)
    secondPeriod = new Date(year , 7, 0)
    if selectedState is "Todos"
      $.each tableAlerta.states, (key, state) ->
        $.each state, (key, reg) ->
          if reg.date >= firstPeriod and reg.date <= secondPeriod
            sum += reg.area
    else
      $.each tableAlerta.states[selectedState], (key, reg) ->
        if reg.date >= firstPeriod and reg.date <= secondPeriod
          sum += reg.area
    return Math.round(sum * 100) / 100


  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "Ano"
  @data.addColumn "number", "Total"

  # populate table
  i = totalPeriodos
  while i >= 0
    data = [periodos[i]]
    data[1] = sumValues(curYear - i)
    @data.addRow data
    i--

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    focusTarget: "category"
    chartArea:
      width: "68%"
      height: "76%"
    colors: ['#3ABCFC']
    vAxis:
      title: "Periodos"
    hAxis:
      title: "Área Km2"
    bar:
      groupWidth: "80%"
    isStacked: true
    animation:
      duration: 500
      easing: "inAndOut"

  @chart.draw @data, options
#}}}
# CHART5 {{{
chart5 = new Hash5GoogleCharts(
  type: "Column"
  container: "chart5"
  period: 2
  title: "Alerta DETER: UFs"
  buttons:
    minusplus: true
    minimize: true
    maximize: true
)
chart5.createContainer()

chart5.addBtn.onclick = ->
  chart5.options.period++
  chart5.drawChart()

chart5.delBtn.onclick = ->
  chart5.options.period--
  chart5.drawChart()

chart5.drawChart = ->
  # sum values
  sumValues = (state, year) ->
    sum = 0
    firstPeriod = new Date(year - 1, 7, 1)
    secondPeriod = new Date(year , 7, 0)
    $.each tableAlerta.states[state], (key, reg) ->
      if firstPeriod <= reg.date <= secondPeriod
        sum += reg.area
    Math.round(sum * 100) / 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "mes"
  for i in [0...@options.period]
    @data.addColumn "number", periodos[i]

  # populate table
  for i in [0...estados.length]
    estado = estados[i]
    data = [estado]
    for j in [1..@options.period]
      data[j] = sumValues(estados[i], curYear - j + 1)
    @data.addRow data

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    focusTarget: "category"
    chartArea:
      width: "70%"
      height: "76%"
    colors: ['#3ABCFC', '#FC2121', '#D0FC3F', '#FCAC0A',
             '#67C2EF', '#FF5454', '#CBE968', '#FABB3D',
             '#77A4BD', '#CC6C6C', '#A6B576', '#C7A258']
    bar:
      groupWidth: "100%"
    vAxis:
      title: "Área Km2"
    animation:
      duration: 500
      easing: "inAndOut"

  # Disabling the buttons while the chart is drawing.
  @addBtn.disabled = true
  @delBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.
    @addBtn.disabled = @options.period > totalPeriodos
    @delBtn.disabled = @options.period < 2

  @chart.draw @data, options
#}}}
# CHART6 {{{
chart6 = new Hash5GoogleCharts(
  type: "Column"
  container: "chart6"
  period: 1
  title: "Alerta DETER: Acumulado UFs"
  buttons:
    minimize: true
    maximize: true
)
chart6.createContainer()

chart6.drawChart = ->
  # sum values
  sumValues = (state) ->
    sum = 0
    $.each tableAlerta.states[state], (key, reg) ->
      sum += reg.area
    Math.round(sum * 100) / 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "Estado"
  @data.addColumn "number", "Área Total"

  # populate table
  for i in [0...estados.length]
    estado = estados[i]
    data = [estado]
    data[1] = sumValues(estados[i])
    @data.addRow data

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    focusTarget: "category"
    chartArea:
      width: "70%"
      height: "76%"
    colors: ['#3ABCFC']
    bar:
      groupWidth: "100%"
    vAxis:
      title: "Área Km2"
    animation:
      duration: 500
      easing: "inAndOut"

  @chart.draw @data, options
#}}}
# CHART7 {{{
chart7 = new Hash5GoogleCharts(
  type: "Pie"
  container: "chart7"
  period: 0
  buttons:
    arrows: true
    minimize: true
    maximize: true
)
chart7.createContainer()

chart7.changeTitle periodos[chart7.options.period]

chart7.leftBtn.onclick = ->
  chart7.options.period++
  chart7.drawChart()

chart7.rightBtn.onclick = ->
  chart7.options.period--
  chart7.drawChart()

chart7.drawChart = ->
  # sum values
  sumValues = (state, year) ->
    sum = 0
    firstPeriod = new Date(year - 1, 7, 1)
    secondPeriod = new Date(year , 7, 0)
    $.each tableAlerta.states[state], (key, reg) ->
      if firstPeriod <= reg.date <= secondPeriod
        sum += reg.area
    Math.round(sum * 100) / 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "mes"
  @data.addColumn "number", periodos[totalPeriodos]

  # populate table
  for i in [0...estados.length]
    estado = estados[i]
    data = [estado]
    data[1] = sumValues(estados[i], curYear - @options.period)
    @data.addRow data

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    chartArea:
      width: "90%"
      height: "80%"
    colors: ['#3ABCFC', '#FC2121', '#D0FC3F', '#FCAC0A',
             '#67C2EF', '#FF5454', '#CBE968', '#FABB3D',
             '#77A4BD', '#CC6C6C', '#A6B576', '#C7A258']
    backgroundColor: "transparent"

  @changeTitle periodos[@options.period]

  # Disabling the buttons while the chart is drawing.
  @rightBtn.disabled = true
  @leftBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.
    @rightBtn.disabled = @options.period < 1
    @leftBtn.disabled = @options.period >= totalPeriodos

  @chart.draw @data, options
#}}}
# CHART8 {{{
chart8 = new Hash5GoogleCharts(
  type: "Pie"
  container: "chart8"
  period: 1
  title: "2004-Atual"
  buttons:
    minimize: true
    maximize: true
)
chart8.createContainer()

chart8.drawChart = ->
  # sum values
  sumValues = (state) ->
    sum = 0
    $.each tableAlerta.states[state], (key, reg) ->
      sum += reg.area
    Math.round(sum * 100) / 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "Estado"
  @data.addColumn "number", "Área Total"

  # populate table
  for i in [0...estados.length]
    estado = estados[i]
    data = [estado]
    data[1] = sumValues(estados[i])
    @data.addRow data

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    focusTarget: "category"
    chartArea:
      width: "70%"
      height: "76%"
    colors: ['#3ABCFC', '#FC2121', '#D0FC3F', '#FCAC0A',
             '#67C2EF', '#FF5454', '#CBE968', '#FABB3D',
             '#77A4BD', '#CC6C6C', '#A6B576', '#C7A258']
    bar:
      groupWidth: "100%"
    vAxis:
      title: "Área Km2"
    animation:
      duration: 500
      easing: "inAndOut"

  @chart.draw @data, options
#}}}
# SPARK1 {{{
spark1 = new Hash5Sparks(
  container: "spark1"
  title: "Total Mensal"
)

spark1.createSpark()

spark1.drawChart = ->
  #Create array with values
  createTable = (state) =>
    dayValue = 0
    for day in [1..monthDays]
      $.each tableAlerta.states[state], (key, reg) ->
        if dateStart <= reg.date <= dateEnd and reg.day is day
          dayValue += reg.area
          return false
      data[(day-1)] = Math.round((data[(day-1)] + dayValue) * 100)/100

  monthDays = new Date(chart1.yearsSlct.value, chart1.monthsSlct.value + 1, 0).getDate()
  dateStart = new Date(chart1.yearsSlct.value, chart1.monthsSlct.value, 1)
  dateEnd = new Date(chart1.yearsSlct.value, chart1.monthsSlct.value, monthDays)
  data = []

  # populate table with 0
  for day in [1..monthDays]
    data[(day-1)] = 0

  # populate table with real values
  if selectedState is "Todos"
    $.each tableAlerta.states, (state, value) ->
      createTable state
  else
    createTable selectedState

  value = data[monthDays-1]
  @updateSparkInfo value
  @updateSparkChart data

#}}}
# SPARK2 {{{
spark2 = new Hash5Sparks(
  container: "spark2"
  title: "Total Período"
)

spark2.createSpark()

spark2.drawChart = ->
  #Create array with values
  # sum values
  sumValues = (year, month) ->
    sum = 0
    firstPeriod = new Date(year - 1, 7, 1)
    secondPeriod = new Date(year , 7, 0)
    if selectedState is "Todos"
      $.each tableAlerta.states, (key, state) ->
        $.each state, (key, reg) ->
          if reg.date >= firstPeriod and reg.date <= secondPeriod and reg.month is month
            sum += reg.area
    else
      $.each tableAlerta.states[selectedState], (key, reg) ->
        if reg.date >= firstPeriod and reg.date <= secondPeriod and reg.month is month
          sum += reg.area
    return Math.round(sum * 100) / 100

  # init table
  data = []

  # populate table
  # list months
  $.each months, (number, month) =>
    i = number
    number = parseInt number
    number = if 7 <= (number + 7) <= 11 then number+= 7 else number-= 5
    data[i] = sumValues(chart1.yearsSlct.value, number)

  value = 0
  $.each data, ->
    value += this

  @updateSparkInfo Math.round(value*100)/100
  @updateSparkChart data

#}}}
# GAUGE1 {{{
gauge1 = new Hash5GoogleCharts(
  type: "Gauge"
  container: "gauge1"
  title: "Demo"
)
gauge1.createMinimalContainer()

gauge1.drawChart = ->
  # sum values
  periodDeforestationRate = (year, month) ->
    sumValues = (date) ->
      sum = 0
      if selectedState is "Todos"
        for state of tableAlerta.states
          for reg of tableAlerta.states[state]
            reg = tableAlerta.states[state][reg]
            if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
              sum += reg.area
      else
        for reg of tableAlerta.states[selectedState]
          reg = tableAlerta.states[selectedState][reg]
          if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
            sum += reg.area
      sum

    year = (if month > 5 then year++ else year)
    # definir periodo atual
    curDate = new Date(year, month)
    # definir periodo anterior
    preDate = new Date(year - 1, month)

    # definir valores referentes ao periodo atual
    curValue = 0
    curValue = sumValues(curDate)
    preValue = 0
    preValue = sumValues(preDate)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "Label"
  @data.addColumn "number", "Valor"

  # populate table
  title = 'TVAA'
  data = [title]
  data[1] = periodDeforestationRate(chart1.yearsSlct.value, chart1.monthsSlct.value)
  @data.addRow data

  options =
    min: -100
    max: 100
    greenFrom: -100
    greenTo: 0
    redFrom: 50
    redTo: 100
    yellowFrom: 0
    yellowTo: 50
    minorTicks: 5
    greenColor: '#4D9652',
    yellowColor: '#FCC065',
    redColor: '#DB5D3D',
    animation:
      duration: 700
      easing: "inAndOut"
  @chart.draw @data, options
#}}}
# GAUGE2 {{{
gauge2 = new Hash5GoogleCharts(
  type: "Gauge"
  container: "gauge2"
  title: "Demo"
)
gauge2.createMinimalContainer()

gauge2.drawChart = ->
  # sum values
  periodDeforestationRate = (year, month) ->
    sumValues = (date) ->
      sum = 0
      if selectedState is "Todos"
        for state of tableAlerta.states
          for reg of tableAlerta.states[state]
            reg = tableAlerta.states[state][reg]
            if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
              sum += reg.area
      else
        for reg of tableAlerta.states[selectedState]
          reg = tableAlerta.states[selectedState][reg]
          if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
            sum += reg.area
      sum

    year = (if month > 5 then year++ else year)
    # definir periodo atual
    curDate = new Date(year, month)
    # definir periodo anterior
    preDate = new Date(year, month - 1)

    # definir valores referentes ao periodo atual
    curValue = 0
    curValue = sumValues(curDate)
    preValue = 0
    preValue = sumValues(preDate)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "Label"
  @data.addColumn "number", "Valor"

  # populate table
  title = 'TVMA'
  data = [title]
  data[1] = periodDeforestationRate(chart1.yearsSlct.value, chart1.monthsSlct.value)
  @data.addRow data

  options =
    min: -100
    max: 100
    greenFrom: -100
    greenTo: 0
    redFrom: 50
    redTo: 100
    yellowFrom: 0
    yellowTo: 50
    minorTicks: 5
    greenColor: '#4D9652',
    yellowColor: '#FCC065',
    redColor: '#DB5D3D',
    animation:
      duration: 700
      easing: "inAndOut"
  @chart.draw @data, options
#}}}
# GAUGE3 {{{
gauge3 = new Hash5GoogleCharts(
  type: "Gauge"
  container: "gauge3"
  title: "Demo"
)
gauge3.createMinimalContainer()

gauge3.drawChart = ->
  # sum values
  periodDeforestationAvgRate = (year, month) ->
    sumValues = (fp, sp) ->
      sum = 0
      if selectedState is "Todos"
        for state of tableAlerta.states
          for reg of tableAlerta.states[state]
            reg = tableAlerta.states[state][reg]
            sum += reg.area if fp <= reg.date <= sp
      else
        for reg of tableAlerta.states[selectedState]
          reg = tableAlerta.states[selectedState][reg]
          sum += reg.area if fp <= reg.date <= sp
      sum

    curValue = 0
    preValue = 0

    year = (if month > 5 then year++ else year)

    prePeriod = new Date(year - 1, 7, 1)
    curPeriod = new Date(year, month + 1, 0)
    curValue = sumValues(prePeriod, curPeriod)

    prePeriod = new Date(year - 2, 7, 1)
    curPeriod = new Date(year - 1, month + 1, 0)
    preValue = sumValues(prePeriod, curPeriod)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  # create new chart
  if @options.started
    @createChart()

  # create an empty table
  @dataTable()

  # init table
  @data.addColumn "string", "Label"
  @data.addColumn "number", "Valor"

  # populate table
  title = 'TVPA'
  data = [title]
  data[1] = periodDeforestationAvgRate(chart1.yearsSlct.value, chart1.monthsSlct.value)
  @data.addRow data

  options =
    min: -100
    max: 100
    greenFrom: -100
    greenTo: 0
    redFrom: 50
    redTo: 100
    yellowFrom: 0
    yellowTo: 50
    minorTicks: 5
    greenColor: '#4D9652',
    yellowColor: '#FCC065',
    redColor: '#DB5D3D',
    animation:
      duration: 700
      easing: "inAndOut"
  @chart.draw @data, options
#}}}
# KNOB1 {{{
knob1 = new Hash5Knobs(
  container: "knob1"
  title: "Taxa VAA"
  popover: "Taxa de variação em relação ao mesmo mês do ano anterior"
)

knob1.createKnob()

knob1.drawChart = ->
  # sum values
  periodDeforestationRate = (year, month) ->
    sumValues = (date) ->
      sum = 0
      if selectedState is "Todos"
        for state of tableAlerta.states
          for reg of tableAlerta.states[state]
            reg = tableAlerta.states[state][reg]
            if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
              sum += reg.area
      else
        for reg of tableAlerta.states[selectedState]
          reg = tableAlerta.states[selectedState][reg]
          if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
            sum += reg.area
      return sum

    year = (if month > 5 then year++ else year)
    # definir periodo atual
    curDate = new Date(year, month)
    # definir periodo anterior
    preDate = new Date(year - 1, month)

    # definir valores referentes ao periodo atual
    curValue = 0
    curValue = sumValues(curDate)
    preValue = 0
    preValue = sumValues(preDate)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  value = periodDeforestationRate(
    chart1.yearsSlct.value, chart1.monthsSlct.value
  )
  @updateKnob value
  return

#}}}
# KNOB2 {{{
knob2 = new Hash5Knobs(
  container: "knob2"
  title: "Taxa VMA"
  popover: "Taxa de variação em relação ao mês anterior"
)

knob2.createKnob()

knob2.drawChart = ->
  # sum values
  periodDeforestationRate = (year, month) ->
    sumValues = (date) ->
      sum = 0
      if selectedState is "Todos"
        for state of tableAlerta.states
          for reg of tableAlerta.states[state]
            reg = tableAlerta.states[state][reg]
            if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
              sum += reg.area
      else
        for reg of tableAlerta.states[selectedState]
          reg = tableAlerta.states[selectedState][reg]
          if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
            sum += reg.area
      return sum

    year = (if month > 5 then year++ else year)
    # definir periodo atual
    curDate = new Date(year, month)
    # definir periodo anterior
    preDate = new Date(year, month - 1)

    # definir valores referentes ao periodo atual
    curValue = 0
    curValue = sumValues(curDate)
    preValue = 0
    preValue = sumValues(preDate)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  value = periodDeforestationRate(
    chart1.yearsSlct.value, chart1.monthsSlct.value
  )
  @updateKnob value
  return

#}}}
# KNOB3 {{{
knob3 = new Hash5Knobs(
  container: "knob3"
  title: "Taxa VPA"
  popover: "Taxa de variação em relação ao periodo anterior"
)

knob3.createKnob()

knob3.drawChart = ->
  # sum values
  periodDeforestationAvgRate = (year, month) ->
    sumValues = (fp, sp) ->
      sum = 0
      if selectedState is "Todos"
        for state of tableAlerta.states
          for reg of tableAlerta.states[state]
            reg = tableAlerta.states[state][reg]
            sum += reg.area if fp <= reg.date <= sp
      else
        for reg of tableAlerta.states[selectedState]
          reg = tableAlerta.states[selectedState][reg]
          sum += reg.area if fp <= reg.date <= sp
      return sum

    curValue = 0
    preValue = 0

    year = (if month > 5 then year++ else year)

    prePeriod = new Date(year - 1, 7, 1)
    curPeriod = new Date(year, month + 1, 0)
    curValue = sumValues(prePeriod, curPeriod)

    prePeriod = new Date(year - 2, 7, 1)
    curPeriod = new Date(year - 1, month + 1, 0)
    preValue = sumValues(prePeriod, curPeriod)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  value = periodDeforestationAvgRate(
    chart1.yearsSlct.value, chart1.monthsSlct.value
  )
  @updateKnob value
  return

#}}}
# CONTROLS {{{
reloadCharts = ->
  chart1.drawChart()
  chart2.drawChart()
  chart3.drawChart()
  chart4.drawChart()
  chart5.drawChart()
  chart6.drawChart()
  chart7.drawChart()
  chart8.drawChart()
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()

$(".quick-btn a").on "click", (event) ->
  event.preventDefault()
  selectedState = $(@).prop("id")
  $(@).each ->
    $("a").removeClass "active"
  $(@).addClass "active"
  reloadCharts()
# }}}
# CALLBACK {{{
google.setOnLoadCallback -> reloadCharts()
#}}}
