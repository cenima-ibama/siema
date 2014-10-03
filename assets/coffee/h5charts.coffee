# DATA {{{
#REST PARA OS SERVIÇOS.
H5.Data.restURL = "http://" + document.domain + "/siema/rest"

H5.Data.changed = false

H5.Data.region = "Todos"
H5.Data.regions = ["NO", "NE", "CO", "SE", "SU"]
H5.Data.regionsLabels = ["Norte", "Nordeste", "Centro-Oeste", "Sudeste", "Sul"]

H5.Data.typesOfEvents = ["Derramamento de líquido", "Desastre natural", "Explosão/incêndio", "Lançamento de sólidos", "Mortandade de peixes", "Produtos químicos/embalagens abandonadas", "Rompimento de barragem", "Vazamento de gases", "Outros", "Todos"]
H5.Data.originOfAccident = ["Rodovia", "Ferrovia", "Terminal/portos/ancoradouros/etc", "Embarcação", "Refinaria", "Plataforma", "Indústria", "Duto", "Barragem", "Armazenamento/depósito", "Posto de combustível", "Outros", "Todos"]
H5.Data.damageIdentified = ["Óbitos/feridos","População afetada/evacuada", "Suspensão de abastecimento de água", "Rio/córrego", "Lago","Mar","Praia","Solo","Águas subterrâneas","Atmosfera","Flora","Fauna","Unidade de Conservação Federal","Unidade de Conservação Estadual/Municipal", "Outros", "Todos"]
H5.Data.institutionLocal = ["IBAMA","Órgão Estadual ou Municipal de Meio Ambiente","Defesa Civil","Corpo de Bombeiros","Polícia Rodoviária","Polícia Militar","Polícia Civil","Marinha do Brasil","Empresa especializada em atendimento", "Outra(s)", "Todos"]
H5.Data.sourceType = ["Comunicado da empresa/responsável", "Órgão Estadual ou Municipal de Meio Ambiente", "Mídia", "Denúncia", "Outra(s) fonte(s)"]
H5.Data.periodDay = ["Matutino", "Vespertino", "Noturno", "Madrugada"]
H5.Data.periodDayAbbrv = ["M", "V", "N", "S"]
# Órgão Estadual ou Municipal de Meio Ambiente


H5.Data.thisDate = new Date()
H5.Data.thisYear = H5.Data.thisDate.getFullYear()
H5.Data.thisMonth = H5.Data.thisDate.getMonth()
H5.Data.thisDay = H5.Data.thisDate.getDate()
H5.Data.thisType = 0
H5.Data.thisOrigin = 0

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
  11: "Dez"


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
H5.DB.addDB({name:'occurence', table:'vw_ocorrencia'})


H5.DB.occurence.data =
  init: ->
    @regions = {}
    for region in H5.Data.regions
      @regions[region] = {}
    @regions["Todos"] = {}

  populate: (id_ocorrencia, region, date, state, type, origin, damageIdentified, institutionLocal, sourceType, periodDay) ->
    # convert string into date
    convertDate = (dateStr) ->
      dateStr = String(dateStr)
      dArr = dateStr.split("-")
      return new Date(dArr[0], (dArr[1]) - 1, dArr[2])

    # populate object
    newType = (type.replace /[{}"]/g, "".split ",")

    # populate object
    newOrigin = (origin.replace /[{}"]/g, "".split ",")

    # populate object
    if damageIdentified isnt undefined
      newDamage = (damageIdentified.replace /[{}"]/g, "".split ",")
    else
      newDamage = null

    # populate object
    if institutionLocal isnt undefined
      newInstitution = (institutionLocal.replace /[{}"]/g, "".split ",")
    else
      newInstitution = null

    # populate object
    if sourceType isnt undefined
      newSource = (sourceType.replace /[{}"]/g, "".split ",")
    else
      newSource = null

    #recover the register belonging to the current region
    if region not in H5.Data.regions
      region = "Todos"
    self = @regions[region]
    self[id_ocorrencia] = {}
    self[id_ocorrencia].type = newType #type of the event
    self[id_ocorrencia].origin = newOrigin #type of the event
    self[id_ocorrencia].state = state #state UF
    self[id_ocorrencia].damage = newDamage #type of identified damages
    self[id_ocorrencia].institution = newInstitution #institutions acting on the scene
    self[id_ocorrencia].source = newSource #type of infomation source
    self[id_ocorrencia].period = periodDay #period of the day that occured
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
  # Verifies if the occurrence has a accident date. In case it doesnt, sends the date that the form was sent

  date = properties.dt_registro

  if typeof properties.data_acidente isnt 'undefined'
    date = properties.data_acidente

  H5.DB.occurence.data.populate(
    properties.id_ocorrencia, properties.regiao, date, properties.sigla, properties.eventos, properties.origem, properties.tipos_danos_identificados, properties.institiuicoes_atuando_local, properties.tipos_fontes_informacoes, properties.periodo_ocorrencia
    )

#}}}
# RELOAD DATE {{{
# reload date based on database
H5.Data.thisDate = H5.DB.occurence.data.lastValue.date
H5.Data.thisDay = H5.DB.occurence.data.lastValue.day
H5.Data.thisMonth = H5.DB.occurence.data.lastValue.month
H5.Data.thisYear = H5.DB.occurence.data.lastValue.year

H5.Data.selectedYear = H5.Data.thisYear
H5.Data.selectedMonth = H5.Data.thisMonth
H5.Data.selectedType = 9 #last item of the list
H5.Data.selectedOrigin = 12 #last item of the list

#}}}
## FOI SOLICITADO A REMOÇÃO DO CHART1 [Acidentes: Índice Diário] - 21/08/14
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
chart1._originsSlct = document.getElementById('originsSlct')

# make those options selected
# chart1._yearsSlct.options[H5.Data.thisYear - 2004].selected = true
chart1._monthsSlct.options[H5.Data.thisMonth].selected = true
chart1._typesSlct.options[9].selected = true
chart1._originsSlct.options[12].selected = true

$(chart1._monthsSlct).on "change", (event) ->
  H5.Data.selectedMonth = parseInt chart1._monthsSlct.value
  #SelectedIndex = 12 -> Todos.
  if chart1._monthsSlct.value isnt "12"
    $("#knob1").show()
    $("#knob2").show()
    $("#spark1").show()
    $("#chart8").show()

    knob1.drawChart()
    knob2.drawChart()
    spark1.drawChart()
    chart8.drawChart()

  else
    $("#knob1").hide()
    $("#knob2").hide()
    $("#spark1").hide()
    $("#chart8").hide()

  chart1.drawChart()
  #chart3.drawChart()
  knob3.drawChart()
  spark2.drawChart()

$(chart1._yearsSlct).on "change", (event) ->
  H5.Data.selectedYear = parseInt chart1._yearsSlct.value
  #chart1.drawChart()
  chart2.drawChart()
  #chart3.drawChart()
  #chart4.drawChart()
  chart5.drawChart()
  #chart6.drawChart()
  chart7.drawChart()
  chart8.drawChart()
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()
  H5.Data.changed = true

$(chart1._typesSlct).on "change", (event) ->
  H5.Data.selectedType = parseInt chart1._typesSlct.value
  #chart1.drawChart()
  chart2.drawChart()
  #chart3.drawChart()
  #chart4.drawChart()
  chart5.drawChart()
  #chart6.drawChart()
  chart7.drawChart()
  chart8.drawChart()
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()

$(chart1._originsSlct).on "change", (event) ->
  H5.Data.selectedOrigin = parseInt chart1._originsSlct.value
  #chart1.drawChart()
  chart2.drawChart()
  #chart3.drawChart()
  #chart4.drawChart()
  chart5.drawChart()
  #chart6.drawChart()
  chart7.drawChart()
  chart8.drawChart()
  knob1.drawChart()
  knob2.drawChart()
  knob3.drawChart()
  spark1.drawChart()
  spark2.drawChart()

chart1.drawChart = ->

  createTable = (region, type, origin, indexMonth) =>

    sum = 0 #number of acidentes of H5.Data.selectedType
    #column = 0;

    daysInMonth = new Date(H5.Data.selectedYear, indexMonth + 1, 0).getDate();
    firstPeriod = new Date(H5.Data.selectedYear, indexMonth, 1);
    secondPeriod = new Date(H5.Data.selectedYear,indexMonth, daysInMonth);

    daysInMonth = 31 if todosMeses;

    for day in [1..daysInMonth]

      $.each H5.DB.occurence.data.regions[region], (key, reg) -> #keý is the name of the register, reg is de data
        #if the date of the register is between the first day of the month and last day of the month
        #verify if the day is the day we want
        #verify if the type of the event is of the type we want
        #verify if the origin of the event is of the origin we want
        if type is "Todos" and origin is "Todos"
          if firstPeriod <= reg.date <= secondPeriod and reg.day is day
            sum++
        else if firstPeriod <= reg.date <= secondPeriod and reg.day is day and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
          sum++

      #column = if todosMeses then (indexMonth + 1) else 1;

      #setValue e getValue: apiGoogle - (row, collumn, value) sum with the value of the past day
      #@data.setValue (day - 1), column , @data.getValue((day - 1), column) + sum #Math.round((@data.getValue((day - 1), 1) + sum) * 100) / 100
      #Set for Day.
      if (not todosMeses)
        @data.setValue (day - 1), 1 , @data.getValue((day - 1), 1) + sum #Math.round((@data.getValue((day - 1), 1) + sum) * 100) / 100

    #Set for Month
    if todosMeses
        @data.setValue indexMes, 1 , @data.getValue(indexMes, 1) + sum


  todosMeses = (H5.Data.selectedMonth is 12);

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

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  #column x
  if(todosMeses)
    @data.addColumn "string", "Mês"
  else
    @data.addColumn "number", "Dia"

  #pega o ultimo dia do mes
  daysInMonth = new Date(H5.Data.selectedYear, H5.Data.selectedMonth + 1, 0).getDate()
  firstPeriod = new Date(H5.Data.selectedYear, H5.Data.selectedMonth, 1)
  secondPeriod = new Date(H5.Data.selectedYear, H5.Data.selectedMonth, daysInMonth)
  data = []

  monthLoop = new Array();

  # populate table with 0. Start table data base.
   #All Months, create base table.
  if (todosMeses)

    for mes in [0..11]
      monthLoop[mes] = H5.Data.months[mes];

    @data.addColumn "number", H5.Data.selectedYear;

    titleChart  = "Todos os meses";

  else
    monthLoop[0] = H5.Data.selectedMonth;
    titleChart  = months[H5.Data.selectedMonth];
    @data.addColumn "number", titleChart;

  data = [];
  contMes = 1;
  indexMes = 0;

  for mesIndex in monthLoop

    indexMes = if todosMeses then monthLoop.indexOf(mesIndex) else mesIndex;

    daysInMonth = new Date(H5.Data.selectedYear, indexMes + 1, 0).getDate();
    firstPeriod = new Date(H5.Data.selectedYear, indexMes, 1);
    secondPeriod = new Date(H5.Data.selectedYear,indexMes, daysInMonth);

    if todosMeses

      ##if monthLoop.length <= 0

        ###
        for day in [1..31]
          #Day (x);
          data[0] = day;

          #Qtde Ocorrências by month(y)
          for m in [0..11]
            data[m+1] = 0;
        ###
        data[0] = monthLoop[indexMes];
        data[1] = 0;
        @data.addRow data;

    else

      #Mês selecionado.
      for day in [1..daysInMonth]
        data[0] = day;
        data[1] = 0;
        @data.addRow data;

    if H5.Data.region is "Todos"

      $.each H5.DB.occurence.data.regions, (region, value) ->
        createTable region, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin],indexMes;

    else
      createTable H5.Data.region, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin],indexMes;

  @changeTitle "Acidentes: Índice Diário [" + titleChart + "] - ["+H5.Data.selectedYear+"]"

  titleX = if todosMeses then "Meses" else "Dias";

  options =
    title: ""
    titleTextStyle:
      color: "#333"
      fontSize: 13
    backgroundColor: "transparent"
    legend: {position: 'right'},
    chartArea:
      width: "70%"
      height: "70%"
    vAxis:
      title: "Número de Ocorrências"
    hAxis:
      title: titleX
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
  title: "Acidentes: Índice Mensal"
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
  sumValues = (year, month, type, origin) ->
    sum = 0 #counter of occurences

    #first period = 1, Jan
    firstPeriod = new Date(year, 0, 1)

    #second period 31, Dez
    secondPeriod = new Date(year , 11, 31)

    if H5.Data.region is "Todos"
      $.each H5.DB.occurence.data.regions, (key, region) ->
        $.each region, (key, reg) ->
          #for each region, if the date belongs to the select year, the select month and the select type of event
          if type is "Todos" and origin is "Todos"
            if (firstPeriod <= reg.date <= secondPeriod) and (reg.month is month) #and (reg.type.indexOf(type) >= 0)
              sum++
          else if firstPeriod <= reg.date <= secondPeriod and reg.month == month  and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
            #counter of occurences
            sum++
    else
      $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
        if type is "Todos" and origin is "Todos"
          if firstPeriod <= reg.date <= secondPeriod and reg.month == month #and (reg.type.indexOf(type) >= 0)
            sum++
        else if firstPeriod <= reg.date <= secondPeriod and reg.month == month  and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
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
    #@data.addColumn "number", H5.Data.thisYear - i #the year selected
    @data.addColumn "number", H5.Data.selectedYear - i #the year selected

  for month of H5.Data.months #increment in number
    data = [H5.Data.months[month]] #create the position with the name month ex:data[Ago]
    month = parseInt month #number of the month
    # if 7 <= (month + 7) <= 11 then month+= 7 else month-= 5
    #periods added to the chart, for every one of them, calculates the values related to the month
    for i in [1..@options.period]
      #for all the years, the selected type and all the months of the year
      #not the same data from above
      #data[i] = sumValues(H5.Data.thisYear - i + 1, month, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
      data[i] = sumValues(H5.Data.selectedYear - i + 1, month, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])

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

## FOI SOLICITADO A REMOÇÃO DO CHART3 [Acidentes: Índice Períodos] - 21/08/14
## CHART3 {{{
#chart3 = new H5.Charts.GoogleCharts(
#  type: "Bar"
#  container: "chart3"
#  period: 1
#  title: "Acidentes: Índice Períodos"
#  buttons:
#    minusplus: true
#    export: true
#    table: true
#    minimize: true
#    maximize: true
#)
#
#chart3._addBtn.onclick = ->
#  chart3.options.period++
#  chart3.drawChart()
#
#chart3._delBtn.onclick = ->
#  chart3.options.period--
#  chart3.drawChart()
#
#chart3.drawChart = ->
#  # sum values
#  sumValues = (firstPeriod, secondPeriod,type,origin) ->
#    sum = 0
#
#    if H5.Data.region is "Todos"
#      $.each H5.DB.occurence.data.regions, (key, region) ->
#        $.each region, (key, reg) ->
#          #in the year selected
#          if type is "Todos" and origin is "Todos"
#            if firstPeriod <= reg.date <= secondPeriod
#              sum++
#          else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
#            sum++
#    else
#      $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
#        # console.log "Redraw chart3 form region ", H5.Data.region
#        if type is "Todos" and origin is "Todos"
#          if firstPeriod <= reg.date <= secondPeriod
#            sum++
#        else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0 or type is "Todos") and (origin >= 0 or origin is "Todos")
#          sum++
#    return sum #return the number of occurences
#    #return Math.round(sum * 100) / 100
#
#  # sum total values
#  sumTotalValues = (year) ->
#    #beginin of the year
#    firstPeriod = new Date(year, 0, 1)
#    #31, Dez
#    secondPeriod = new Date(year, 11, 31)
#    sumValues firstPeriod, secondPeriod, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin]
#
#  # sum average values
#  sumAvgValues = (year) ->
#    month = H5.Data.selectedMonth
#
#    #Para todos os meses: mes para secondPeriod é dezembro.
#    month = 11 if month is 12;
#
#    #beginin of the year
#    firstPeriod = new Date(year, 0, 1)
#    secondPeriod = new Date(year, month+1, 0)
#    ###
#    if month is H5.Data.thisMonth
#      secondPeriod = new Date(year, month, H5.Data.thisDay) #goes until the current day
#    else
#      secondPeriod = new Date(year, month+1, 0) #get the last day of the selected month
#    ###
#
#    sumValues firstPeriod, secondPeriod, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin]
#
#  # create new chart
#  @createChart()
#
#  # create an empty table
#  @createDataTable()
#
#  # init table
#  @data.addColumn "string", "Ano"
#  @data.addColumn "number", "Parcial"
#  @data.addColumn "number", "Diferença"
#
#  # populate table
#  for i in [0..@options.period]
#    #data = [H5.Data.periods[i]] #create the data for the period
#    period = H5.Data.selectedYear - i
#    data = ["#{period}"]
#    sumTotal = sumTotalValues(H5.Data.selectedYear - i) #for the selected year
#    sumAvg = sumAvgValues(H5.Data.selectedYear - i)
#    data[1] = sumAvg
#    data[2] = sumTotal - sumAvg #not real number, no round
#    # data[2] = Math.round((sumTotal - sumAvg) * 100) / 100
#    @data.addRow data
#
#  options =
#    title: ""
#    titleTextStyle:
#      color: "#333"
#      fontSize: 13
#    backgroundColor: "transparent"
#    focusTarget: "category"
#    chartArea:
#      width: "68%"
#      height: "76%"
#    colors: ['#3ABCFC', '#FC2121']
#    vAxis:
#      title: "Anos"
#    hAxis:
#      title: "Número de Ocorrências"
#    bar:
#      groupWidth: "80%"
#    isStacked: true
#    animation: H5.Data.animate
#
#  # Disabling the buttons while the chart is drawing.
#  @_addBtn.disabled = true
#  @_delBtn.disabled = true
#
#  google.visualization.events.addListener @chart, "ready", =>
#    # Enabling only relevant buttons.
#    @_addBtn.disabled = @options.period > H5.Data.selectedYear - 2004 - 1
#    @_delBtn.disabled = @options.period < 2
#
#  @chart.draw @data, options
##}}}

## FOI SOLICITADO A REMOÇÃO DO CHART4 [Acidentes por período do dia] - 21/08/14
## CHART4 {{{
#chart4 = new H5.Charts.GoogleCharts(
#  type: "Column"
#  container: "chart4"
#  period: 2
#  title: "Acidentes por período do dia"
#  buttons:
#    minusplus: true
#    export: true
#    table: true
#    minimize: true
#    maximize: true
#)
#
#chart4._addBtn.onclick = ->
#  chart4.options.period++
#  chart4.drawChart()
#
#chart4._delBtn.onclick = ->
#  chart4.options.period--
#  chart4.drawChart()
#
#chart4.drawChart = ->
#  # sum values
#  sumValues = (period, region, year, type, origin) ->
#    sum = 0 #counter of occuresce
#    firstPeriod = new Date(year, 0, 1)
#    secondPeriod = new Date(year , 11, 31)
#
#    $.each H5.DB.occurence.data.regions[region], (key, reg) ->
#      if type is "Todos" and origin is "Todos"
#        if firstPeriod <= reg.date <= secondPeriod and (reg.period is period)
#          sum++
#          # console.log "somando " + key
#      else if firstPeriod <= reg.date <= secondPeriod  and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos") and (reg.period is period)
#        #counter of the number of occurences
#        sum++
#        # console.log "somando " + key
#    Math.round(sum * 100) / 100
#
#  # create new chart
#  @createChart()
#
#  # create an empty table
#  @createDataTable()
#
#  # init table
#  @data.addColumn "string", "Período"
#  #create the columns with the years
#  for i in [0...@options.period]
#    @data.addColumn "number", H5.Data.selectedYear - i
#
#  # populate table with real values
#
#  countPeriod = 0
#  data = []
#
#  for period in H5.Data.periodDayAbbrv
#
#    data[0] = H5.Data.periodDay[countPeriod]
#
#    #sum for period.
#    totalReg = 0;
#
#    if H5.Data.region is "Todos"
#
#      $.each H5.DB.occurence.data.regions, (region, reg) =>
#
#        for j in [1..@options.period] #gets the value of the years fo every region
#          totalReg = sumValues(period, region, H5.Data.selectedYear - j + 1, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin]);
#          data[j] = if data[j] >= 0 then (data[j] + totalReg) else totalReg;
#
#    else
#      allData = [H5.Data.region] #gets the value of every period for only one region
#      for j in [1..@options.period]
#        data[j] = sumValues(period, H5.Data.region, H5.Data.selectedYear - j + 1, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
#    @data.addRow data
#
#     #Clear registrers.
#    data = [];
#
#    countPeriod++
#
#  options =
#    title: ""
#    titleTextStyle:
#      color: "#333"
#      fontSize: 13
#    backgroundColor: "transparent"
#    focusTarget: "category"
#    chartArea:
#      width: "70%"
#      height: "76%"
#    colors: ['#3ABCFC', '#FC2121', '#D0FC3F', '#FCAC0A',
#             '#67C2EF', '#FF5454', '#CBE968', '#FABB3D',
#             '#77A4BD', '#CC6C6C', '#A6B576', '#C7A258']
#    bar:
#      groupWidth: "100%"
#    vAxis:
#      title: "Número de Acidentes"
#    animation: H5.Data.animate
#
#  # Disabling the buttons while the chart is drawing.
#  @_addBtn.disabled = true
#  @_delBtn.disabled = true
#
#  google.visualization.events.addListener @chart, "ready", =>
#    # Enabling only relevant buttons.
#    @_addBtn.disabled = @options.period > H5.Data.selectedYear - 2004
#    @_delBtn.disabled = @options.period < 2
#
#  @chart.draw @data, options
##}}}

# CHART5 {{{
chart5 = new H5.Charts.GoogleCharts(
  type: "Column"
  container: "chart5"
  period: 1
  title: "Número de Acidentes Atendidos por Instituições"
  buttons:
    minusplus: true
    export: true
    table: true
    minimize: true
    maximize: true
)

chart5._addBtn.onclick = ->
  chart5.options.period++
  chart5.drawChart()

chart5._delBtn.onclick = ->
  chart5.options.period--
  chart5.drawChart()

chart5.drawChart = ->
  sumValues = (institution, region, year, type, origin) ->
  # sum values
    sum = 0 #counter of occuresce
    firstPeriod = new Date(year, 0, 1)
    secondPeriod = new Date(year , 11, 31)
    $.each H5.DB.occurence.data.regions[region], (key, reg) ->
      # console.log reg.institution
      if reg.institution isnt null
        if type is "Todos" and origin is "Todos"
          if firstPeriod <= reg.date <= secondPeriod

            for t in reg.institution.split(",")
              if t is institution
                sum++

            # console.log "somando " + key
        else if firstPeriod <= reg.date <= secondPeriod  and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
          #counter of the number of occurences
          for t in reg.institution.split(",")
              if t is institution
                sum++
          # console.log "somando " + key
    #Math.round(sum * 100) / 100
    Math.round ((sum * 100) / 100)

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  # init table
  @data.addColumn "string", "Instituição Ambiental"
  #create the columns with the years
  for i in [0...@options.period]
    @data.addColumn "number", H5.Data.selectedYear - i

  # populate table
  data = []
  for institution in H5.Data.institutionLocal
    # console.log institution
    data[0] = institution

    #sum for institution.
    totalReg = 0;

    if H5.Data.region is "Todos"
      $.each H5.DB.occurence.data.regions, (region, reg) =>
        for j in [1..@options.period] #gets the value of the years fo every region
          totalReg = sumValues(institution, region, H5.Data.selectedYear - j + 1, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
          data[j] = if data[j] >= 0 then (data[j] + totalReg) else totalReg
    else
      allData = [H5.Data.region] #gets the value of every period for only one region
      for j in [1..@options.period]
        data[j] = sumValues(institution, H5.Data.region, H5.Data.selectedYear - j + 1, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
    @data.addRow data

     #Clear array for next interaction.
    data = []

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
      title: "Número de Acidentes"
    animation: H5.Data.animate

  @chart.draw @data, options
# }}}

## FOI SOLICITADO A REMOÇÃO DO CHART6 [Fonte de Informação] - 21/08/14
## CHART6 {{{
#chart6 = new H5.Charts.GoogleCharts(
#  type: "Pie"
#  container: "chart6"
#  period: 0
#  buttons:
#    arrows: true
#    export: true
#    table: true
#    minimize: true
#    maximize: true
#)
#chart6._leftBtn.onclick = ->
#  chart6.options.period++
#  chart6.drawChart()
#
#chart6._rightBtn.onclick = ->
#  chart6.options.period--
#  chart6.drawChart()
#
#chart6.drawChart = ->
#  # sum values
#  sumValues = (source, region, year, type, origin) ->
#    sum = 0
#    firstPeriod = new Date(year, 0, 1)
#    secondPeriod = new Date(year , 11, 31)
#    $.each H5.DB.occurence.data.regions[region], (key, reg) ->
#      if type is "Todos" and origin is "Todos"
#        if firstPeriod <= reg.date <= secondPeriod and (reg.source.indexOf(source) >= 0)
#          # console.log key + source + " -> " + reg.source + " :" + reg.source.indexOf(source)
#          sum++
#      else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos") and (reg.source.indexOf(source) >= 0)
#        sum++ #counter of ocurrences
#    Math.round ((sum * 100) / 100)
#
#  # create new chart
#  @createChart()
#
#  # create an empty table
#  @createDataTable()
#
#  # init table
#  @data.addColumn "string", "Fonte de Informação"
#  @data.addColumn "number", H5.Data.selectedYear
#
#  # populate table
#  for source in H5.Data.sourceType #for every source of information
#    data = []
#    data[0] = source
#    data[1] = 0
#    # console.log source
#    if H5.Data.region is "Todos"
#      $.each H5.DB.occurence.data.regions, (region, reg) =>
#        data[1] = data[1] + sumValues(source, region, H5.Data.selectedYear - @options.period, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
#    else
#      data[1] = data[1] + sumValues(source, H5.Data.region, H5.Data.selectedYear - @options.period, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
#    @data.addRow data
#
#  # #Handles the registers without a region defined
#  # region = H5.Data.regions[H5.Data.regions.length + 1] #for the data that doesnt have a region
#  # data = ["Sem Região Cadastrada"]
#  # data[1] = sumValues("Todos", H5.Data.thisYear - @options.period, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
#  # @data.addRow data
#
#  options =
#    title: ""
#    titleTextStyle:
#      color: "#333"
#      fontSize: 13
#    chartArea:
#      width: "90%"
#      height: "80%"
#    colors: ['#3ABCFC', '#FC2121', '#D0FC3F', '#FCAC0A',
#             '#67C2EF', '#FF5454', '#CBE968', '#FABB3D',
#             '#77A4BD', '#CC6C6C', '#A6B576', '#C7A258']
#    backgroundColor: "transparent"
#
#  # @changeTitle H5.Data.periods[@options.period]
#  originTitle = if H5.Data.selectedOrigin is 12 then "Todos Tipos de Origens" else H5.Data.originOfAccident[H5.Data.selectedOrigin]
#
#  if (H5.Data.selectedType == 9)
#    @changeTitle "Fonte de Informação [" + (H5.Data.selectedYear - @options.period) + "] - Todos Tipos de Eventos" + " - " + originTitle
#  else
#    @changeTitle "Fonte de Informação [" + (H5.Data.selectedYear - @options.period) + "]" +  H5.Data.typesOfEvents[H5.Data.selectedType] + " : " + originTitle
#
#  # Disabling the buttons while the chart is drawing.
#  @_rightBtn.disabled = true
#  @_leftBtn.disabled = true
#
#  google.visualization.events.addListener @chart, "ready", =>
#    # Enabling only relevant buttons.
#    @_rightBtn.disabled = @options.period < 1
#    @_leftBtn.disabled = @options.period >= H5.Data.selectedYear - 2004
#
#  @chart.draw @data, options
##}}}

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
  sumValues = (region, year, type, origin) ->

    sum = 0

    firstPeriod = new Date(year, 0, 1)
    secondPeriod = new Date(year , 11, 31)

    $.each H5.DB.occurence.data.regions[region], (key, reg) ->

      if type is "Todos" and origin is "Todos"
        if firstPeriod <= reg.date <= secondPeriod
          sum++
      else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
        sum++ #counter of ocurrences
    Math.round((sum * 100) / 100)

  # create new chart
  @createChart()

  # create an empty table
  @createDataTable()

  # init table
  @data.addColumn "string", "Região"
  @data.addColumn "number", H5.Data.selectedYear

  # populate table
  for i in [0...H5.Data.regions.length] #for every region
    region = H5.Data.regionsLabels[i]
    data = [region]
    data[1] = sumValues(H5.Data.regions[i], H5.Data.selectedYear - @options.period, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
    @data.addRow data

  #Handles the registers without a region defined
  region = H5.Data.regions[H5.Data.regions.length + 1] #for the data that doesnt have a region
  data = ["Sem Região Cadastrada"]
  data[1] = sumValues("Todos", H5.Data.selectedYear - @options.period, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
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
  originTitle = if H5.Data.selectedOrigin is 12 then "Todos Tipos de Origens" else H5.Data.originOfAccident[H5.Data.selectedOrigin]

  if (H5.Data.selectedType == 9)
    @changeTitle H5.Data.selectedYear - @options.period + " : Todos Tipos de Eventos" + " : " + originTitle
  else
    @changeTitle H5.Data.selectedYear - @options.period + " : " +  H5.Data.typesOfEvents[H5.Data.selectedType] + " : " + originTitle

  # Disabling the buttons while the chart is drawing.
  @_rightBtn.disabled = true
  @_leftBtn.disabled = true

  google.visualization.events.addListener @chart, "ready", =>
    # Enabling only relevant buttons.
    @_rightBtn.disabled = @options.period < 1
    @_leftBtn.disabled = @options.period >= H5.Data.selectedYear - 2004

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
  sumValues = (region, type, origin) ->
    sum = 0
    $.each H5.DB.occurence.data.regions[region], (key, reg) ->
      if type is "Todos" and origin is "Todos"
        if firstPeriod <= reg.date <= secondPeriod
          sum++
      else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
        #counter of ocurrences
        sum++

    ###
    if firstPeriod > H5.Data.thisDate
      return 1
    else
    ###
    Math.round((sum * 100) / 100)

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
    region = H5.Data.regionsLabels[i] #for every region
    data = [region]
    data[1] = sumValues(H5.Data.regions[i], H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
    @data.addRow data

  #Handles the registers without a region defined
  region = H5.Data.regions[H5.Data.regions.length + 1] #for the data that doesnt have a region
  data = ["Sem Região Cadastrada"]
  data[1] = sumValues("Todos", H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
  @data.addRow data

  originTitle = if H5.Data.selectedOrigin is 12 then "Todos Tipos de Origens" else H5.Data.originOfAccident[H5.Data.selectedOrigin]

  if(H5.Data.selectedType == 9)
    @changeTitle chart1._monthsSlct.options[H5.Data.selectedMonth].label + ", " + H5.Data.selectedYear + ": Todos Tipos de Eventos" + " : " + originTitle
  else
    @changeTitle chart1._monthsSlct.options[H5.Data.selectedMonth].label + ", " + H5.Data.selectedYear + ": " + H5.Data.typesOfEvents[H5.Data.selectedType] + " : " + originTitle

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
      title: "Número de Ocorrências"
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
  createTable = (region, type, origin) =>
    dayValue = 0
    for day in [1..daysInMonth]
      $.each H5.DB.occurence.data.regions[region], (key, reg) ->
        if type is "Todos" and origin is "Todos"
          if firstPeriod <= reg.date <= secondPeriod and reg.day is day
            dayValue++ #counter of occurences per day
        else if firstPeriod <= reg.date <= secondPeriod and reg.day is day and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
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
      createTable region, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin]
  else
    createTable H5.Data.region, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin]

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
  sumValues = (year, month, type, origin) ->
    sum = 0
    firstPeriod = new Date(year, 0, 1)
    secondPeriod = new Date(year, month+1, 0)

    ###
    if month != H5.Data.thisMonth
      secondPeriod = new Date(year, month+1, 0)
    else
      secondPeriod = new Date(year, month, H5.Data.thisDay)
    ###


    if H5.Data.region is "Todos"
      $.each H5.DB.occurence.data.regions, (key, region) ->
        $.each region, (key, reg) ->
          if type is "Todos" and origin is "Todos"
            if firstPeriod <= reg.date <= secondPeriod and reg.month == month
              sum++ #counter of occurences
          else if firstPeriod <= reg.date <= secondPeriod and reg.month == month and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
            sum++ #counter of occurences
    else
      $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
        if firstPeriod <= reg.date <= secondPeriod and reg.month == month and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
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
      data.push sumValues(year, month, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin])
    else
      data.push 0

  value = 0
  $.each data, ->
    value += this
    if value == 600
      alert value
      # ...



    # ...


  @updateInfo data, Math.round(value*100)/100
#}}}
# KNOB1 {{{
knob1 = new H5.Charts.Knobs(
  container: "knob1"
  title: "Taxa VMAA"
  popover: "Taxa de variação em relação ao mesmo mês do ano anterior"
  color: "coldtohot"
)

knob1.drawChart = ->
  # sum values
  periodDeforestationRate = (year, month, type, origin) ->
    sumValues = (date) ->
      sum = 0
      if H5.Data.region is "Todos"
        for region of H5.DB.occurence.data.regions
          for reg of H5.DB.occurence.data.regions[region]
            reg = H5.DB.occurence.data.regions[region][reg]
            if type is "Todos" and origin is "Todos"
              if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
                sum++
            else if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth() and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
              sum++ #counter of occurrences
      else
        for reg of H5.DB.occurence.data.regions[H5.Data.region]
          reg = H5.DB.occurence.data.regions[H5.Data.region][reg]
          if type is "Todos" and origin is "Todos"
            if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
              sum++
          else if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth() and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
            sum++ #counter of occurrences
      return sum

    # definir periodo atual
    curDate = new Date(year, month)
    # definir periodo anterior
    preDate = new Date(year - 1, month)

    # definir valores referentes ao periodo atual
    curValue = sumValues(curDate)
    preValue = sumValues(preDate)

    # caso o valor do periodo anterior seja 0, retorna 0
    # para evitar uma divisão por 0
    if preValue is 0
      return 0
    else
      return Math.round((curValue - preValue) / preValue * 100)

  value = periodDeforestationRate(
    H5.Data.selectedYear, H5.Data.selectedMonth, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin]
  )
  @updateInfo value
#}}}
# KNOB2 {{{
knob2 = new H5.Charts.Knobs(
  container: "knob2"
  title: "Taxa VMA"
  popover: "Taxa de variação em relação ao mês anterior"
  color: "coldtohot"
)

knob2.drawChart = ->
  # sum values
  periodDeforestationRate = (year, month, type, origin) ->
    sumValues = (date) ->
      sum = 0
      if H5.Data.region is "Todos"
        for region of H5.DB.occurence.data.regions
          for reg of H5.DB.occurence.data.regions[region]
            reg = H5.DB.occurence.data.regions[region][reg]
            if type is "Todos" and origin is "Todos"
              if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth()
                sum++
            else if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth() and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
              sum++ #counter of occurences
      else
        for reg of H5.DB.occurence.data.regions[H5.Data.region]
          reg = H5.DB.occurence.data.regions[H5.Data.region][reg]
          if date.getFullYear() <= reg.year <= date.getFullYear() and reg.month is date.getMonth() and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
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
    H5.Data.selectedYear, H5.Data.selectedMonth, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin]
  )
  @updateInfo value
#}}}
# KNOB3 {{{
knob3 = new H5.Charts.Knobs(
  container: "knob3"
  title: "Taxa VAA"
  popover: "Taxa de variação em relação ao ano anterior"
  color: "coldtohot"
)

knob3.drawChart = ->
  # sum values
  periodDeforestationAvgRate = (year, month, type, origin) ->
    sumValues = (firstPeriod, secondPeriod) ->
      sum = 0
      if H5.Data.region is "Todos"
        $.each H5.DB.occurence.data.regions, (key, region) ->
          $.each region, (key, reg) ->
            if type is "Todos" and origin is "Todos"
              if firstPeriod <= reg.date <= secondPeriod
                sum++
            else if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
              sum++
      else
        $.each H5.DB.occurence.data.regions[H5.Data.region], (key, reg) ->
          if firstPeriod <= reg.date <= secondPeriod and (reg.type.indexOf(type) >= 0 or type is "Todos") and (reg.origin.indexOf(origin) >= 0 or origin is "Todos")
            sum++
      return Math.round(sum * 100) / 100

    # if month > 6 then year++ else year

    sumPeriods = (year, month) ->
      firstPeriod = new Date(year, 0, 1)

      #Para todos os meses: mes para secondPeriod é dezembro.
      month = 11 if month is 12;

      secondPeriod = new Date(year, month+1, 0)

      ###
      if month is H5.Data.thisMonth
        secondPeriod = new Date(year, month, H5.Data.thisDay)
      else
        secondPeriod = new Date(year, month+1, 0)
      ###

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
    H5.Data.selectedYear, H5.Data.selectedMonth, H5.Data.typesOfEvents[H5.Data.selectedType], H5.Data.originOfAccident[H5.Data.selectedOrigin]
  )
  @updateInfo value
#}}}
# CONTROLS {{{
H5.Charts.reloadCharts = ->
  #chart1.drawChart()
  chart2.drawChart()
  #chart3.drawChart()
  #chart4.drawChart()
  chart5.drawChart()
  #chart6.drawChart()
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
  #makes charts visible
  $("#dash").show()

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

  H5.Charts.reloadCharts()

  #makes charts invisible after loading
  $("#dash").hide()
# }}}
