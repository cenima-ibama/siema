# DATA {{{
H5.Data.restURL = "http://" + document.domain + "/siema/rest_v2"

H5.Data.changed = false

H5.Data.region = "Todos"
H5.Data.regions = ["NO", "NE", "CO", "SE", "SU"]
H5.Data.typesOfEvents = ["Derramamento de líquidos", "Desastre natural", "Explosão/incêndio", "Lançamento de sólidos", "Mortandade de peixes", "Produtos químicos/embalagens abandonadas", "Rompimento de barragem", "Vazamento de gases", "Outros", "Todos"]

H5.Data.thisDate = new Date()
H5.Data.thisYear = H5.Data.thisDate.getFullYear()
H5.Data.thisMonth = H5.Data.thisDate.getMonth()
H5.Data.thisDay = H5.Data.thisDate.getDate()
H5.Data.thisType = 0

console.log H5.Data.thisYear

#remove prodes years
# H5.Data.thisProdesYear = if H5.Data.thisMonth < 7 then H5.Data.thisYear else H5.Data.thisYear + 1
#remove


# H5.Data.totalPeriods = if H5.Data.thisMonth < 7 then (H5.Data.thisDate.getFullYear() - 2005) else (H5.Data.thisDate.getFullYear() - 2004)
# H5.Data.periods = new Array(H5.Data.totalPeriods)
# for i in [0..H5.Data.totalPeriods]
#   if H5.Data.thisMonth < 7
#     H5.Data.periods[i] = (H5.Data.thisDate.getFullYear() - i - 1) + "-" + (H5.Data.thisDate.getFullYear() - i)
#   else
#     H5.Data.periods[i] = (H5.Data.thisDate.getFullYear() - i) + "-" + (H5.Data.thisDate.getFullYear() - i + 1)

H5.Data.months =
  0: "Jan"
  1: "Fev"
  2: "Mar"
  3: "Abr"
  4: "Mai"
  5: "Jun"
  6: "Jul"
  7: "Ago"
  8: "Set"
  9: "Out"
  10: "Nov"
  11: "Dev"

# disable animation on mobile devices
unless H5.isMobile.any()
  H5.Data.animate = {
    duration: 500
    easing: "inAndOut"
  }
else
  H5.Data.animate = {}

#}}}
# DATABASES {{{
H5.DB.addDB({name:'occurence', table:'vw_ocorrencia'});


H5.DB.occurence.data =
  init: ->
    @regions = {}
    for region in H5.Data.regions
      @regions[region] = {}

  populate: (id_ocorrencia, region, date, state, type) ->
    # convert string into date
    convertDate = (dateStr) ->
      dateStr = String(dateStr)
      dArr = dateStr.split("-")
      return new Date(dArr[0], (dArr[1]) - 1, dArr[2])

    # populate object
    newType = (type.replace /[{}"]/g, "").split ","

    #recover the register belonging to the current region
    self = @regions[region]
    self[id_ocorrencia] = {}
    self[id_ocorrencia].type = newType #type of the event
    self[id_ocorrencia].state = state #state UF
    self[id_ocorrencia].date = convertDate(date) #date of ocurrence of the event
    self[id_ocorrencia].year = convertDate(date).getFullYear()
    self[id_ocorrencia].month = convertDate(date).getMonth()
    self[id_ocorrencia].day = convertDate(date).getDate()

    # set the value of the last value
    if @lastValue
      if @lastValue.date < self[id_ocorrencia].date
        @lastValue = self[id_ocorrencia]
    else
      @lastValue = self[id_ocorrencia]
    return


rest = new H5.Rest (
  url: H5.Data.restURL
  table: H5.DB.occurence.table
)

H5.DB.occurence.data.init()
$.each rest.data, (i, properties) ->
  H5.DB.occurence.data.populate(
    properties.id_ocorrencia, properties.regiao, properties.dt_registro, properties.sigla, properties.eventos
    )

# H5.DB.prodes.data =
#   init: ->
#     @regions = {}
#     for region in H5.Data.regions
#       @regions[region] = {}
#       for period in H5.Data.periods
#         @regions[region][period] = {}

#   populate: (period, ac, am, ap, ma, mt, pa, ro, rr, to) ->
#     self = @regions
#     self.AC[period].area = ac
#     self.AM[period].area = am
#     self.AP[period].area = ap
#     self.MA[period].area = ma
#     self.MT[period].area = mt
#     self.PA[period].area = pa
#     self.RO[period].area = ro
#     self.RR[period].area = rr
#     self.TO[period].area = to

# rest = new H5.Rest (
#   url: H5.Data.restURL
#   table: H5.DB.prodes.table
# )

# H5.DB.prodes.data.init()
# $.each rest.data, (i, properties) ->
#   H5.DB.prodes.data.populate(
#     properties.ano_prodes.replace('/','-'),
#     parseFloat(properties.ac), parseFloat(properties.am),
#     parseFloat(properties.ap), parseFloat(properties.ma),
#     parseFloat(properties.mt), parseFloat(properties.pa),
#     parseFloat(properties.ro), parseFloat(properties.rr),
#     parseFloat(properties.to)
#   )

# H5.DB.cloud.data =
#   init: ->
#     @nuvem = {}

#   populate: (date, value) ->
#     convertDate = (dateStr) ->
#       dateStr = String(dateStr)
#       dArr = dateStr.split("-")
#       new Date(dArr[0], (dArr[1]) - 1, dArr[2])
#     self = @nuvem
#     self[date] = {}
#     self[date].value = value
#     self[date].date = convertDate(date)
#     self[date].year = convertDate(date).getFullYear()
#     self[date].month = convertDate(date).getMonth()
#     self[date].day = convertDate(date).getDate()

# rest = new H5.Rest (
#   url: H5.Data.restURL
#   table: H5.DB.cloud.table
# )

# H5.DB.cloud.data.init()
# $.each rest.data, (i, properties) ->
#   H5.DB.cloud.data.populate(
#     properties.data, properties.percent,
#   )
#}}}
# RELOAD DATE {{{
# reload date based on database
H5.Data.thisDate = H5.DB.occurence.data.lastValue.date
H5.Data.thisDay = H5.DB.occurence.data.lastValue.day
H5.Data.thisMonth = H5.DB.occurence.data.lastValue.month
H5.Data.thisYear = H5.DB.occurence.data.lastValue.year
# H5.Data.thisProdesYear = if H5.Data.thisMonth < 7 then H5.Data.thisYear else H5.Data.thisYear + 1

H5.Data.selectedYear = H5.Data.thisYear
H5.Data.selectedMonth = H5.Data.thisMonth
H5.Data.selectedType = 0 #first item of the list

# H5.Data.totalPeriods = if H5.Data.thisMonth < 7 then (H5.Data.thisDate.getFullYear() - 2005) else (H5.Data.thisDate.getFullYear() - 2004)
# H5.Data.periods = new Array(H5.Data.totalPeriods)
# for i in [0..H5.Data.totalPeriods]
#   if H5.Data.thisMonth < 7
#     H5.Data.periods[i] = (H5.Data.thisDate.getFullYear() - i - 1) + "-" + (H5.Data.thisDate.getFullYear() - i)
#   else
#     H5.Data.periods[i] = (H5.Data.thisDate.getFullYear() - i) + "-" + (H5.Data.thisDate.getFullYear() - i + 1)
#}}}
# CHART1 {{{
chart1 = new H5.Charts.GoogleCharts (
  type: "Line"
  container: "chart1"
  title: "Alerta DETER: Índice Diário"
  buttons:
    export: true
    table: true
    minimize: true
    maximize: true
)

chart1._yearsSlct = document.getElementById('yearsSlct')
chart1._monthsSlct = document.getElementById('monthsSlct')
chart1._typesSlct = document.getElementById('typesSlct')

# console.log "Esse mês " , H5.Data.thisMonth
# console.log "Mês selecionado " , chart1._monthsSlct
# console.log "Chart1 messlct " , chart1._monthsSlct.options[H5.Data.thisMonth]

# make those options selected
# selectedYear = if H5.Data.thisMonth < 7 then H5.Data.totalPeriods + 1 else H5.Data.totalPeriods
chart1._yearsSlct.options[H5.Data.thisYear - 2004].selected = true
chart1._monthsSlct.options[H5.Data.thisMonth].selected = true

$(chart1._monthsSlct).on "change", (event) ->
  H5.Data.selectedMonth = parseInt chart1._monthsSlct.value
  # console.log "Reload do Mês"
  chart1.drawChart()
  chart3.drawChart()
  chart8.drawChart()
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()

$(chart1._yearsSlct).on "change", (event) ->
  H5.Data.selectedYear = parseInt chart1._yearsSlct.value
  # console.log "Reload do Year"
  chart1.drawChart()
  chart3.drawChart()
  chart8.drawChart()
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()
  H5.Data.changed = true

$(chart1._typesSlct).on "change", (event) ->
  H5.Data.selectedType = parseInt chart1._typesSlct.value
  # console.log "Reload do Type"
  chart1.drawChart()
  chart2.drawChart()
  chart3.drawChart()
  chart4.drawChart()
  chart7.drawChart()
  chart8.drawChart()
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()

chart1.drawChart = ->
  createTable = (region, type) =>
    sum = 0 #number of acidentes of H5.Data.selectedType
    for day in [1..daysInMonth]
      $.each H5.DB.occurence.data.regions[region], (key, reg) -> #keý is the name of the register, reg is de data
        #if the date of the register is between the first day of the month and last day of the month
        #verify if the day is the day we want
        #verify if the type of the event is of the type we want
        if type is "Todos"
          if firstPeriod <= reg.date <= secondPeriod and reg.day is day
            sum++
        else if firstPeriod <= reg.date <= secondPeriod and reg.day is day and (reg.type.indexOf(type) >= 0)
          sum++
      #setValue e getValue: apiGoogle - (row, collumn, value) sum with the value of the past day
      @data.setValue (day - 1), 1, @data.getValue((day - 1), 1) + sum #Math.round((@data.getValue((day - 1), 1) + sum) * 100) / 100

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  @data.addColumn "number", "Dia"
  @data.addColumn "number", "Número de Ocorrências"

  #pega o ultimo dia do mes
  daysInMonth = new Date(H5.Data.selectedYear, H5.Data.selectedMonth + 1, 0).getDate()
  firstPeriod = new Date(H5.Data.selectedYear, H5.Data.selectedMonth, 1)
  secondPeriod = new Date(H5.Data.selectedYear, H5.Data.selectedMonth, daysInMonth)
  data = []

  # populate table with 0
  for day in [1..daysInMonth]
    data[0] = day
    data[1] = 0
    @data.addRow data

  # console.log "Event Select Name ", H5.Data.typesOfEvents[H5.Data.selectedType]
  # populate table with real values
  if H5.Data.region is "Todos"
    $.each H5.DB.occurence.data.regions, (region, value) ->
      createTable region, H5.Data.typesOfEvents[H5.Data.selectedType]
  else
    createTable H5.Data.region, H5.Data.typesOfEvents[H5.Data.selectedType]

  months =
    0: "Janeiro"
    1: "Fevereiro"
    2: "Março"
    3: "Abril"
    4: "Maio"
    5: "Junho"
    6: "Julho"
    7: "Agosto"
    8: "Setembro"
    9: "Outubro"
    10: "Novembro"
    11: "Dezembro"

  @changeTitle "Alerta DETER: Índice Diário [" + months[H5.Data.selectedMonth] + "]"

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
      title: "Número de Ocorrências"
    hAxis:
      title: "Dias"
      gridlines:
        color: "#CCC"
        count: daysInMonth / 5
    animation: H5.Data.animate

  @chart.draw @data, options
#}}}
# CHART2 {{{
chart2 = new H5.Charts.GoogleCharts(
  type: "Area"
  container: "chart2"
  period: 2
  title: "Alerta DETER: Índice Mensal"
  buttons:
    minusplus: true
    export: true
    table: true
    minimize: true
    maximize: true
)

chart2._addBtn.onclick = ->
  chart2.options.period++
  chart2.drawChart()

chart2._delBtn.onclick = ->
  chart2.options.period--
  chart2.drawChart()

chart2.drawChart = ->
  # sum values
  sumValues = (year, month, type) ->
    sum = 0 #counter of occurences
    #first period = 1, Jan
    firstPeriod = new Date(year, 1, 1)
    #second period 31, Dez
    secondPeriod = new Date(year , 12, 31)
    if H5.Data.region is "Todos"
      $.each H5.DB.occurence.data.regions, (key, region) ->
        $.each region, (key, reg) ->
          #for each region, if the date belongs to the select year, the select month and the select type of event
          if type is "Todos"
            if firstPeriod <= reg.date <= secondPeriod and reg.month == month #and (reg.type.indexOf(type) >= 0)
              sum++
          else if firstPeriod <= reg.date <= secondPeriod and reg.month == month and (reg.type.indexOf(type) >= 0)
            #counter of occurences
            sum++
    else
      $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
        if type is "Todos"
          if firstPeriod <= reg.date <= secondPeriod and reg.month == month #and (reg.type.indexOf(type) >= 0)
            sum++
        else if firstPeriod <= reg.date <= secondPeriod and reg.month == month and (reg.type.indexOf(type) >= 0)
          #counter of occurences
          sum++

    return Math.round(sum * 100) / 100

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  # init table
  @data.addColumn "string", "Mês"
  for i in [0...@options.period] #from the current year to the last of the database
    @data.addColumn "number", H5.Data.thisYear - i + 1 #the year selected

  for month of H5.Data.months #increment in number
    data = [H5.Data.months[month]] #create the position with the name month ex:data[Ago]
    month = parseInt month #number of the month
    # if 7 <= (month + 7) <= 11 then month+= 7 else month-= 5
    #periods added to the chart, for every one of them, calculates the values related to the month
    for i in [1..@options.period]
      #for all the years, the selected type and all the months of the year
      #not the same data from above
      data[i] = sumValues(H5.Data.thisYear - i + 1, month, H5.Data.typesOfEvents[H5.Data.selectedType])
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
      title: "Número de Ocorrências"
    animation: H5.Data.animate

  # Disabling the buttons while the chart is drawing.
  @_addBtn.disabled = true
  @_delBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.     quantaty of available years
    @_addBtn.disabled = @options.period > H5.Data.thisYear - 2004
    @_delBtn.disabled = @options.period < 2

  @chart.draw @data, options
#}}}
# CHART3 {{{
chart3 = new H5.Charts.GoogleCharts(
  type: "Bar"
  container: "chart3"
  period: 1
  title: "Alerta DETER: Índice Períodos"
  buttons:
    minusplus: true
    export: true
    table: true
    minimize: true
    maximize: true
)

chart3._addBtn.onclick = ->
  chart3.options.period++
  chart3.drawChart()

chart3._delBtn.onclick = ->
  chart3.options.period--
  chart3.drawChart()

chart3.drawChart = ->
  # sum values
  sumValues = (firstPeriod, secondPeriod) ->
    sum = 0
    if H5.Data.region is "Todos"
      $.each H5.DB.occurence.data.regions, (key, region) ->
        $.each region, (key, reg) ->
          #in the year selected
          if H5.Data.selectedType == 9
            if firstPeriod <= reg.date <= secondPeriod
              sum++
          else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(H5.Data.typesOfEvents[H5.Data.selectedType]) >= 0)
            sum++
    else
      $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
        # console.log "Redraw chart3 form region ", H5.Data.region
        if H5.Data.selectedType == 9
          if firstPeriod <= reg.date <= secondPeriod
            sum++
        else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(H5.Data.typesOfEvents[H5.Data.selectedType]) >= 0)
          sum++
    return sum #return the number of occurences
    #return Math.round(sum * 100) / 100

  # sum total values
  sumTotalValues = (year) ->
    #beginin of the year
    firstPeriod = new Date(year, 1, 1)
    #31, Dez
    secondPeriod = new Date(year, 12, 31)
    sumValues firstPeriod, secondPeriod

  # sum average values
  sumAvgValues = (year) ->
    month = H5.Data.selectedMonth
    #beginin of the year
    firstPeriod = new Date(year, 1, 1)
    if month is H5.Data.thisMonth
      secondPeriod = new Date(year, month, H5.Data.thisDay) #goes until the current day
    else
      secondPeriod = new Date(year, month+1, 0) #get the last day of the selected month
    sumValues firstPeriod, secondPeriod

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  # init table
  @data.addColumn "string", "Ano"
  @data.addColumn "number", "Parcial"
  @data.addColumn "number", "Diferença"

  # populate table
  for i in [0..@options.period]
    #data = [H5.Data.periods[i]] #create the data for the period
    period = 2014 - i
    data = ["#{period}"]
    sumTotal = sumTotalValues(H5.Data.selectedYear - i) #for the selected year
    sumAvg = sumAvgValues(H5.Data.selectedYear - i)
    data[1] = sumAvg
    data[2] = sumTotal - sumAvg #not real number, no round
    # data[2] = Math.round((sumTotal - sumAvg) * 100) / 100
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
      title: "Anos"
    hAxis:
      title: "Número de Ocorrências"
    bar:
      groupWidth: "80%"
    isStacked: true
    animation: H5.Data.animate

  # Disabling the buttons while the chart is drawing.
  @_addBtn.disabled = true
  @_delBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.
    @_addBtn.disabled = @options.period > H5.Data.thisYear - 2004 - 1
    @_delBtn.disabled = @options.period < 2

  @chart.draw @data, options
#}}}
# CHART4 {{{
chart4 = new H5.Charts.GoogleCharts(
  type: "Column"
  container: "chart4"
  period: 2
  title: "Alerta DETER: UFs"
  buttons:
    minusplus: true
    export: true
    table: true
    minimize: true
    maximize: true
)

chart4._addBtn.onclick = ->
  chart4.options.period++
  chart4.drawChart()

chart4._delBtn.onclick = ->
  chart4.options.period--
  chart4.drawChart()

chart4.drawChart = ->
  # sum values
  sumValues = (region, year, type) ->
    sum = 0 #counter of occuresce
    firstPeriod = new Date(year, 1, 1)
    secondPeriod = new Date(year , 12, 31)
    $.each H5.DB.occurence.data.regions[region], (key, reg) ->
      if type is "Todos"
        if firstPeriod <= reg.date <= secondPeriod
          sum++
      else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0)
        #counter of the number of occurences
        sum++
    Math.round(sum * 100) / 100

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  # init table
  @data.addColumn "string", "Região"
  for i in [0...@options.period]
    @data.addColumn "number", H5.Data.thisYear - i

  # populate table with real values
  if H5.Data.region is "Todos"
    $.each H5.DB.occurence.data.regions, (region, reg) =>
      data = [region]
      for j in [1..@options.period] #gets the value of the years fo every region
        data[j] = sumValues(region, H5.Data.thisYear - j + 1, H5.Data.typesOfEvents[H5.Data.selectedType])
      @data.addRow data
  else
    data = [H5.Data.region] #gets the value of every period for only one region
    for j in [1..@options.period]
      data[j] = sumValues(H5.Data.region, H5.Data.thisYear - j + 1, H5.Data.typesOfEvents[H5.Data.selectedType])
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
      title: "Área km²"
    animation: H5.Data.animate

  # Disabling the buttons while the chart is drawing.
  @_addBtn.disabled = true
  @_delBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.
    @_addBtn.disabled = @options.period > H5.Data.thisYear - 2004
    @_delBtn.disabled = @options.period < 2

  @chart.draw @data, options
#}}}
# CHART5 {{{
# chart5 = new H5.Charts.GoogleCharts(
#   type: "Area"
#   container: "chart5"
#   title: "Taxa PRODES|Alerta DETER: Acumulado Períodos"
#   buttons:
#     export: true
#     table: true
#     minimize: true
#     maximize: true
# )

# chart5.drawChart = ->
#   # sum values
#   sumDeter = (year) ->
#     sum = 0
#     firstPeriod = new Date(year - 1, 7, 1)
#     secondPeriod = new Date(year , 7, 0)
#     if H5.Data.region is "Todos"
#       $.each H5.DB.occurence.data.regions, (key, region) ->
#         $.each region, (key, reg) ->
#           if firstPeriod <= reg.date <= secondPeriod
#             sum += reg.area
#     else
#       $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
#         if firstPeriod <= reg.date <= secondPeriod
#           sum += reg.area
#     return Math.round(sum * 100) / 100 if sum >= 0

#   sumProdes = (period) ->
#     sum = 0
#     if H5.Data.region is "Todos"
#       $.each H5.DB.prodes.data.regions, (key, region) ->
#         sum+= region[period].area
#     else
#       sum = H5.DB.prodes.data.regions[H5.Data.region][period].area

#     return sum if sum >= 0

#   # create new chart
#   @createChart()

#   # create an empty table
#   @createDataTable()

#   # init table
#   @data.addColumn "string", "Ano"
#   @data.addColumn "number", "Alerta DETER"
#   @data.addColumn "number", "Taxa PRODES"

#   # populate table
#   i = H5.Data.totalPeriods
#   while i >= 0
#     data = [H5.Data.periods[i]]
#     data[1] = sumDeter(H5.Data.thisProdesYear - i)
#     data[2] = sumProdes(H5.Data.periods[i])
#     @data.addRow data
#     i--

#   options =
#     title: ""
#     titleTextStyle:
#       color: "#333"
#       fontSize: 13
#     backgroundColor: "transparent"
#     focusTarget: "category"
#     chartArea:
#       width: "70%"
#       height: "80%"
#     colors: ['#3ABCFC', '#D0FC3F']
#     vAxis:
#       title: "Área km²"
#     hAxis:
#       title: "Período PRODES"
#     animation: H5.Data.animate

#   @chart.draw @data, options
#}}}
# CHART6 {{{
# chart6 = new H5.Charts.GoogleCharts(
#   type: "Column"
#   container: "chart6"
#   period: 1
#   title: "Taxa PRODES|Alerta DETER: UFs"
#   buttons:
#     export: true
#     table: true
#     minimize: true
#     maximize: true
#     arrows: true
# )

# chart6.changeTitle H5.Data.periods[chart6.options.period]

# chart6._leftBtn.onclick = ->
#   chart6.options.period++
#   chart6.drawChart()

# chart6._rightBtn.onclick = ->
#   chart6.options.period--
#   chart6.drawChart()

# chart6.drawChart = ->
#   # sum values
#   sumDeter = (region, year) ->
#     sum = 0
#     firstPeriod = new Date(year - 1, 7, 1)
#     secondPeriod = new Date(year , 7, 0)
#     $.each H5.DB.occurence.data.regions[region], (key, reg) ->
#       if firstPeriod <= reg.date <= secondPeriod
#         sum+= reg.area
#     return Math.round(sum * 100) / 100

#   sumProdes = (region, year) ->
#     sum = 0
#     period = (year - 1) + "-" + (year)
#     $.each H5.DB.prodes.data.regions[region], (key, reg) ->
#       if key is period
#         sum+= reg.area if reg.area?
#     return Math.round(sum * 100) / 100

#   # create new chart
#   @createChart()

#   # create an empty table
#   @createDataTable()

#   # init table
#   @data.addColumn "string", "Estado"
#   @data.addColumn "number", "Alerta DETER"
#   @data.addColumn "number", "Taxa PRODES"

#   # populate table with real values
#   if H5.Data.region is "Todos"
#     $.each H5.DB.occurence.data.regions, (region, reg) =>
#       data = [region]
#       data[1] = sumDeter(region, H5.Data.thisProdesYear - @options.period)
#       data[2] = sumProdes(region, H5.Data.thisProdesYear - @options.period)
#       @data.addRow data
#   else
#     data = [H5.Data.region]
#     data[1] = sumDeter(H5.Data.region, H5.Data.thisProdesYear - @options.period)
#     data[2] = sumProdes(H5.Data.region, H5.Data.thisProdesYear - @options.period)
#     @data.addRow data

#   options =
#     title: ""
#     titleTextStyle:
#       color: "#333"
#       fontSize: 13
#     backgroundColor: "transparent"
#     focusTarget: "category"
#     chartArea:
#       width: "70%"
#       height: "76%"
#     colors: ['#3ABCFC', '#D0FC3F']
#     bar:
#       groupWidth: "100%"
#     vAxis:
#       title: "Área km²"
#     animation: H5.Data.animate

#   @changeTitle "Taxa PRODES|Alerta DETER: UFs [" + H5.Data.periods[@options.period] + "]"

#   # Disabling the buttons while the chart is drawing.
#   @_rightBtn.disabled = true
#   @_leftBtn.disabled = true

#   google.visualization.events.addListener @chart, "ready", =>
#     # Enabling only relevant buttons.
#     @_rightBtn.disabled = @options.period < 2
#     @_leftBtn.disabled = @options.period >= H5.Data.totalPeriods

#   @chart.draw @data, options
#}}}
# CHART7 {{{
chart7 = new H5.Charts.GoogleCharts(
  type: "Pie"
  container: "chart7"
  period: 0
  buttons:
    arrows: true
    export: true
    table: true
    minimize: true
    maximize: true
)

# chart7.changeTitle H5.Data.periods[chart7.options.period]

chart7._leftBtn.onclick = ->
  chart7.options.period++
  chart7.drawChart()

chart7._rightBtn.onclick = ->
  chart7.options.period--
  chart7.drawChart()

chart7.drawChart = ->
  # sum values
  sumValues = (region, year, type) ->
    sum = 0
    firstPeriod = new Date(year, 1, 1)
    secondPeriod = new Date(year , 12, 31)
    $.each H5.DB.occurence.data.regions[region], (key, reg) ->
      if type is "Todos"
        if firstPeriod <= reg.date <= secondPeriod
          sum++
      else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0)
        sum++ #counter of ocurrences
    Math.round(sum * 100) / 100

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  # init table
  @data.addColumn "string", "Região"
  @data.addColumn "number", H5.Data.selectedYear

  # populate table
  for i in [0...H5.Data.regions.length] #for every region
    region = H5.Data.regions[i]
    data = [region]
    data[1] = sumValues(H5.Data.regions[i], H5.Data.thisYear - @options.period, H5.Data.typesOfEvents[H5.Data.selectedType])
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

  # @changeTitle H5.Data.periods[@options.period]
  @changeTitle H5.Data.thisYear - @options.period

  # Disabling the buttons while the chart is drawing.
  @_rightBtn.disabled = true
  @_leftBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.
    @_rightBtn.disabled = @options.period < 1
    @_leftBtn.disabled = @options.period >= H5.Data.thisYear - 2004

  @chart.draw @data, options
#}}}
# CHART8 {{{
chart8 = new H5.Charts.GoogleCharts(
  type: "Pie"
  container: "chart8"
  period: 1
  buttons:
    export: true
    table: true
    minimize: true
    maximize: true
)

chart8.drawChart = ->
  # sum values
  sumValues = (region, type) ->
    sum = 0
    $.each H5.DB.occurence.data.regions[region], (key, reg) ->
      if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0)
        #counter of ocurrences
        sum++
    if firstPeriod > H5.Data.thisDate
      return 1
    else
      Math.round(sum * 100) / 100

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  # init table
  @data.addColumn "string", "Região"
  @data.addColumn "number", "Número Total de Ocorrências"

  daysInMonth = new Date(H5.Data.selectedYear, H5.Data.selectedMonth + 1, 0).getDate()
  firstPeriod = new Date(H5.Data.selectedYear, H5.Data.selectedMonth, 1)
  secondPeriod = new Date(H5.Data.selectedYear, H5.Data.selectedMonth, daysInMonth)

  if firstPeriod > H5.Data.thisDate
    pieText = "none"
    pieTooltip = "none"
  else
    pieText = "percent"
    pieTooltip = "focus"

  # populate table
  for i in [0...H5.Data.regions.length]
    region = H5.Data.regions[i] #for every region
    data = [region]
    data[1] = sumValues(H5.Data.regions[i], H5.Data.typesOfEvents[H5.Data.selectedType])
    @data.addRow data

  if(H5.Data.selectedType == 9)
    @changeTitle chart1._monthsSlct.options[H5.Data.selectedMonth].label + ", " + H5.Data.selectedYear + ": Todos Tipos de Eventos"
  else
    @changeTitle chart1._monthsSlct.options[H5.Data.selectedMonth].label + ", " + H5.Data.selectedYear + ": " + H5.Data.typesOfEvents[H5.Data.selectedType]

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    focusTarget: "category"
    pieSliceText: pieText
    tooltip:
      trigger: pieTooltip
    chartArea:
      width: "90%"
      height: "80%"
    colors: ['#3ABCFC', '#FC2121', '#D0FC3F', '#FCAC0A',
             '#67C2EF', '#FF5454', '#CBE968', '#FABB3D',
             '#77A4BD', '#CC6C6C', '#A6B576', '#C7A258']
    bar:
      groupWidth: "100%"
    vAxis:
      title: "Área km²"
    animation: H5.Data.animate

  @chart.draw @data, options
#}}}
# CHART9 {{{
# chart9 = new H5.Charts.GoogleCharts(
#   type: "Line"
#   container: "chart9"
#   period: 2
#   title: "Alerta DETER: Taxa(%) de Nuvens"
#   buttons:
#     minusplus: true
#     export: true
#     table: true
#     minimize: true
#     maximize: true
# )

# chart9._addBtn.onclick = ->
#   chart9.options.period++
#   chart9.drawChart()

# chart9._delBtn.onclick = ->
#   chart9.options.period--
#   chart9.drawChart()

# chart9.drawChart = ->
#   # sum values
#   sumValues = (year, month) ->
#     percent = 0
#     firstPeriod = new Date(year - 1, 7, 1)
#     secondPeriod = new Date(year , 7, 0)
#     $.each H5.DB.cloud.data.nuvem, (key, nuvem) ->
#       if nuvem.date >= firstPeriod and nuvem.date <= secondPeriod and nuvem.month is month
#         percent = nuvem.value
#         return false

#     return Math.round(percent * 100)

#   # create new chart
#   @createChart()

#   # create an empty table
#   @createDataTable()

#   # init table
#   @data.addColumn "string", "Mês"
#   for i in [0...@options.period]
#     @data.addColumn "number", H5.Data.periods[i]

#   for month of H5.Data.months
#     data = [H5.Data.months[month]]
#     month = parseInt month
#     if 7 <= (month + 7) <= 11 then month+= 7 else month-= 5
#     for i in [1..@options.period]
#       data[i] = sumValues(H5.Data.thisProdesYear - i + 1, month)
#     @data.addRow data

#   options =
#     title: ""
#     titleTextStyle:
#       color: "#333"
#       fontSize: 13
#     backgroundColor: "transparent"
#     focusTarget: "category"
#     chartArea:
#       width: "70%"
#       height: "80%"
#     colors: ['#3ABCFC', '#FC2121', '#D0FC3F', '#FCAC0A',
#              '#67C2EF', '#FF5454', '#CBE968', '#FABB3D',
#              '#77A4BD', '#CC6C6C', '#A6B576', '#C7A258']
#     vAxis:
#       title: "Porcentagem"
#     animation: H5.Data.animate

#   # Disabling the buttons while the chart is drawing.
#   @_addBtn.disabled = true
#   @_delBtn.disabled = true

#   google.visualization.events.addListener @chart, "ready", =>
#     # Enabling only relevant buttons.
#     @_addBtn.disabled = @options.period > H5.Data.totalPeriods - 4
#     @_delBtn.disabled = @options.period < 2

#   @chart.draw @data, options
#}}}
# SPARK1 {{{
spark1 = new H5.Charts.Sparks(
  container: "spark1"
  title: "Total Mensal"
)

spark1.drawChart = ->
  #Create array with values
  createTable = (region, type) =>
    dayValue = 0
    for day in [1..daysInMonth]
      $.each H5.DB.occurence.data.regions[region], (key, reg) ->
        if type is "Todos"
          if firstPeriod <= reg.date <= secondPeriod and reg.day is day
            dayValue++ #counter of occurences per day
        else if firstPeriod <= reg.date <= secondPeriod and reg.day is day and (reg.type.indexOf(type) >= 0)
          dayValue++ #counter of occurences per day
      data[(day-1)] = Math.round((data[(day-1)] + dayValue) * 100)/100

  daysInMonth = new Date(H5.Data.selectedYear, H5.Data.selectedMonth + 1, 0).getDate()
  firstPeriod = new Date(H5.Data.selectedYear, H5.Data.selectedMonth, 1)
  secondPeriod = new Date(H5.Data.selectedYear, H5.Data.selectedMonth, daysInMonth)

  data = []

  # populate table with 0
  for day in [1..daysInMonth]
    data[(day-1)] = 0

  # populate table with real values
  if H5.Data.region is "Todos"
    $.each H5.DB.occurence.data.regions, (region, value) ->
      createTable region, H5.Data.typesOfEvents[H5.Data.selectedType]
  else
    createTable H5.Data.region, H5.Data.typesOfEvents[H5.Data.selectedType]

  value = data[daysInMonth-1]
  @updateInfo data, value
#}}}
# SPARK2 {{{
spark2 = new H5.Charts.Sparks(
  container: "spark2"
  title: "Total Período"
)

spark2.drawChart = ->
  #Create array with values
  # sum values
  sumValues = (year, month, type) ->
    sum = 0
    firstPeriod = new Date(year, 1, 1)
    if month != H5.Data.thisMonth
      secondPeriod = new Date(year, month+1, 0)
    else
      secondPeriod = new Date(year, month, H5.Data.thisDay)
    if H5.Data.region is "Todos"
      $.each H5.DB.occurence.data.regions, (key, region) ->
        $.each region, (key, reg) ->
          if type is "Todos"
            if firstPeriod <= reg.date <= secondPeriod and reg.month == month
              sum++ #counter of occurences
          else if firstPeriod <= reg.date <= secondPeriod and reg.month == month and (reg.type.indexOf(type) >= 0)
            sum++ #counter of occurences
    else
      $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
        if firstPeriod <= reg.date <= secondPeriod and reg.month == month and (reg.type.indexOf(type) >= 0)
          sum++

    return Math.round(sum * 100) / 100

  # init table
  data = []

  for month of H5.Data.months

    month = parseInt month
    year = H5.Data.selectedYear
    count = parseInt H5.Data.selectedMonth

# #perguntar HELMUTH
#     if count >= 7 then count-= 7 else count+= 5

#     if month <= count
#       if 7 <= (month + 7) <= 11 then month+= 7 else month-= 5
    if month <= H5.Data.selectedMonth
      data.push sumValues(year, month, H5.Data.typesOfEvents[H5.Data.selectedType])
    else
      data.push 0

  value = 0
  $.each data, ->
    value += this

  @updateInfo data, Math.round(value*100)/100
#}}}
# KNOB1 {{{
knob1 = new H5.Charts.Knobs(
  container: "knob1"
  title: "Taxa VAA"
  popover: "Taxa de variação em relação ao mesmo mês do ano anterior"
)

knob1.drawChart = ->
  # sum values
  periodDeforestationRate = (year, month, type) ->
    sumValues = (date) ->
      sum = 0
      if H5.Data.region is "Todos"
        for region of H5.DB.occurence.data.regions
          for reg of H5.DB.occurence.data.regions[region]
            reg = H5.DB.occurence.data.regions[region][reg]
            if type is "Todos"
              if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
                sum++
            else if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth() and (reg.type.indexOf(type) >= 0)
              sum++ #counter of occurrences
      else
        for reg of H5.DB.occurence.data.regions[H5.Data.region]
          reg = H5.DB.occurence.data.regions[H5.Data.region][reg]
          if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth() and (reg.type.indexOf(type) >= 0)
            sum += reg.area
      return sum

    # definir periodo atual
    curDate = new Date(year, month)
    # definir periodo anterior
    preDate = new Date(year - 1, month)

    # definir valores referentes ao periodo atual
    curValue = sumValues(curDate)
    preValue = sumValues(preDate)
    console.log "Current Value ", curValue
    console.log "Per Value ", preValue

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  value = periodDeforestationRate(
    H5.Data.selectedYear, H5.Data.selectedMonth, H5.Data.typesOfEvents[H5.Data.selectedType]
  )
  @updateInfo value
#}}}
# KNOB2 {{{
knob2 = new H5.Charts.Knobs(
  container: "knob2"
  title: "Taxa VMA"
  popover: "Taxa de variação em relação ao mês anterior"
)

knob2.drawChart = ->
  # sum values
  periodDeforestationRate = (year, month, type) ->
    sumValues = (date) ->
      sum = 0
      if H5.Data.region is "Todos"
        for region of H5.DB.occurence.data.regions
          for reg of H5.DB.occurence.data.regions[region]
            reg = H5.DB.occurence.data.regions[region][reg]
            if type is "Todos"
              if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
                sum++
            else if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth() and (reg.type.indexOf(type) >= 0)
              sum++ #counter of occurences
      else
        for reg of H5.DB.occurence.data.regions[H5.Data.region]
          reg = H5.DB.occurence.data.regions[H5.Data.region][reg]
          if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth() and (reg.type.indexOf(type) >= 0)
            sum++
      return sum

    # definir periodo atual
    curDate = new Date(year, month)
    # definir periodo anterior
    preDate = new Date(year, month - 1)

    # definir valores referentes ao periodo atual
    curValue = sumValues(curDate)
    preValue = sumValues(preDate)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  value = periodDeforestationRate(
    H5.Data.selectedYear, H5.Data.selectedMonth, H5.Data.typesOfEvents[H5.Data.selectedType]
  )
  @updateInfo value
#}}}
# KNOB3 {{{
knob3 = new H5.Charts.Knobs(
  container: "knob3"
  title: "Taxa VPA"
  popover: "Taxa de variação em relação ao período PRODES anterior"
)

knob3.drawChart = ->
  # sum values
  periodDeforestationAvgRate = (year, month, type) ->
    sumValues = (firstPeriod, secondPeriod) ->
      sum = 0
      if H5.Data.region is "Todos"
        $.each H5.DB.occurence.data.regions, (key, region) ->
          $.each region, (key, reg) ->
            if type is "Todos"
              if firstPeriod <= reg.date <= secondPeriod
                sum++
            else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0)
              sum++
      else
        $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
          if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0)
            sum++
      return Math.round(sum * 100) / 100

    # if month > 6 then year++ else year

    sumPeriods = (year, month) ->
      firstPeriod = new Date(year-1, 7, 1)
      if month > 6
        if month is H5.Data.thisMonth
          secondPeriod = new Date(year-1, month, H5.Data.thisDay)
        else
          secondPeriod = new Date(year-1, month+1, 0)
      else
        if month is H5.Data.thisMonth
          secondPeriod = new Date(year, month, H5.Data.thisDay)
        else
          secondPeriod = new Date(year, month+1, 0)
      sumValues firstPeriod, secondPeriod

    curValue = sumPeriods(year, month)
    preValue = sumPeriods(year-1, month)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round (curValue - preValue) / preValue * 100

  value = periodDeforestationAvgRate(
    H5.Data.selectedYear, H5.Data.selectedMonth
  )
  @updateInfo value
#}}}
# CONTROLS {{{
H5.Charts.reloadCharts = ->
  # console.log "Reload dos Charts!"
  chart1.drawChart()
  chart2.drawChart()
  chart3.drawChart()
  chart4.drawChart()
  # chart5.drawChart()
  # chart6.drawChart()
  chart7.drawChart()
  chart8.drawChart()
  # chart9.drawChart()
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()
#}}}
# MENUS {{{
$(document).ready ->
  # BOOTSTRAP
  $("[rel=tooltip]").tooltip placement: "bottom"

  $(".alert").alert()

  $("select").selectpicker(
    width: '80px'
    size: 'auto'
  )

  # QUICK BTNS
  $(".quick-btn a").on "click", (event) ->
    event.preventDefault()

    # clean all selection
    $(@).each ->
      $("a").removeClass "active"
    # mark selected option
    $(@).addClass "active"

    # save the selected option
    H5.Data.region = $(@).prop("id")


    # reload charts
    H5.Charts.reloadCharts()

    H5.Data.changed = true

  # MISC
  # enable masonry plugin
  $("#charts-content").masonry
    # options
    itemSelector: ".chart"
    animationOptions:
      duration: 1000

  H5.Charts.reloadCharts()
# }}}
