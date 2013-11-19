###
# =============================================================================
#   Sparkline Linechart JS
# =============================================================================
###
linechartResize = ->
  $("#linechart-1").sparkline [160, 240, 120, 200, 180, 350, 230, 200, 280, 380, 400, 360, 300, 220, 200, 150, 40, 70, 180, 110, 200, 160, 200, 220],
    type: "line"
    width: "100%"
    height: "226"
    lineColor: "#a5e1ff"
    fillColor: "rgba(241, 251, 255, 0.9)"
    lineWidth: 2
    spotColor: "#a5e1ff"
    minSpotColor: "#bee3f6"
    maxSpotColor: "#a5e1ff"
    highlightSpotColor: "#80cff4"
    highlightLineColor: "#cccccc"
    spotRadius: 6
    chartRangeMin: 0

  $("#linechart-1").sparkline [100, 280, 150, 180, 220, 180, 130, 180, 180, 280, 260, 260, 200, 120, 200, 150, 100, 100, 180, 180, 200, 160, 180, 120],
    type: "line"
    width: "100%"
    height: "226"
    lineColor: "#cfee74"
    fillColor: "rgba(244, 252, 225, 0.5)"
    lineWidth: 2
    spotColor: "#b9e72a"
    minSpotColor: "#bfe646"
    maxSpotColor: "#b9e72a"
    highlightSpotColor: "#b9e72a"
    highlightLineColor: "#cccccc"
    spotRadius: 6
    chartRangeMin: 0
    composite: true

  $("#linechart-2").sparkline [160, 240, 250, 280, 300, 250, 230, 200, 280, 380, 400, 360, 300, 220, 200, 150, 100, 100, 180, 180, 200, 160, 220, 140],
    type: "line"
    width: "100%"
    height: "226"
    lineColor: "#a5e1ff"
    fillColor: "rgba(241, 251, 255, 0.9)"
    lineWidth: 2
    spotColor: "#a5e1ff"
    minSpotColor: "#bee3f6"
    maxSpotColor: "#a5e1ff"
    highlightSpotColor: "#80cff4"
    highlightLineColor: "#cccccc"
    spotRadius: 6
    chartRangeMin: 0

  $("#linechart-3").sparkline [100, 280, 150, 180, 220, 180, 130, 180, 180, 280, 260, 260, 200, 120, 200, 150, 100, 100, 180, 180, 200, 160, 220, 140],
    type: "line"
    width: "100%"
    height: "226"
    lineColor: "#cfee74"
    fillColor: "rgba(244, 252, 225, 0.5)"
    lineWidth: 2
    spotColor: "#b9e72a"
    minSpotColor: "#bfe646"
    maxSpotColor: "#b9e72a"
    highlightSpotColor: "#b9e72a"
    highlightLineColor: "#cccccc"
    spotRadius: 6
    chartRangeMin: 0

  $("#linechart-4").sparkline [100, 220, 150, 140, 200, 180, 130, 180, 180, 210, 240, 200, 170, 120, 200, 150, 100, 100],
    type: "line"
    width: "100"
    height: "30"
    lineColor: "#adadad"
    fillColor: "rgba(244, 252, 225, 0.0)"
    lineWidth: 2
    spotColor: "#909090"
    minSpotColor: "#909090"
    maxSpotColor: "#909090"
    highlightSpotColor: "#666"
    highlightLineColor: "#666"
    spotRadius: 0
    chartRangeMin: 0

  $("#linechart-5").sparkline [100, 220, 150, 140, 200, 180, 130, 180, 180, 210, 240, 200, 170, 120, 200, 150, 100, 100],
    type: "line"
    width: "100"
    height: "30"
    lineColor: "#adadad"
    fillColor: "rgba(244, 252, 225, 0.0)"
    lineWidth: 2
    spotColor: "#909090"
    minSpotColor: "#909090"
    maxSpotColor: "#909090"
    highlightSpotColor: "#666"
    highlightLineColor: "#666"
    spotRadius: 0
    chartRangeMin: 0

  $("#barchart-2").sparkline [160, 220, 260, 120, 320, 260, 300, 160, 240, 100, 240, 120],
    type: "bar"
    height: "226"
    barSpacing: 8
    barWidth: 18
    barColor: "#8fdbda"

  $("#composite-chart-1").sparkline [160, 220, 260, 120, 320, 260, 300, 160, 240, 100, 240, 120],
    type: "bar"
    height: "226"
    barSpacing: 8
    barWidth: 18
    barColor: "#8fdbda"

  $("#composite-chart-1").sparkline [100, 280, 150, 180, 220, 180, 130, 180, 180, 280, 260, 260],
    type: "line"
    width: "100%"
    height: "226"
    lineColor: "#cfee74"
    fillColor: "rgba(244, 252, 225, 0.5)"
    lineWidth: 2
    spotColor: "#b9e72a"
    minSpotColor: "#bfe646"
    maxSpotColor: "#b9e72a"
    highlightSpotColor: "#b9e72a"
    highlightLineColor: "#cccccc"
    spotRadius: 6
    chartRangeMin: 0
    composite: true


$(document).ready ->

  ###
  # =============================================================================
  #   Sparkline Linechart JS
  # =============================================================================
  ###
  $("#barcharts").sparkline [190, 220, 210, 220, 220, 260, 300, 220, 240, 240, 220, 200, 240, 260, 210],
    type: "bar"
    height: "100"
    barSpacing: 4
    barWidth: 13
    barColor: "#cbcbcb"
    highlightColor: "#89D1E6"

  $("#pie-chart").sparkline [2,8,6,10],
    type: "pie"
    height: "220"
    width: "220"
    offset: "+90"
    sliceColors: ["#a0eeed","#81e970","#f5af50","#f46f50"]

  $(".sparkslim").sparkline 'html',
     type: "line"
     width: "100"
     height: "30"
     lineColor: "#adadad"
     fillColor: "rgba(244, 252, 225, 0.0)"
     lineWidth: 2
     spotColor: "#909090"
     minSpotColor: "#909090"
     maxSpotColor: "#909090"
     highlightSpotColor: "#666"
     highlightLineColor: "#666"
     spotRadius: 0
     chartRangeMin: 0


  ###
  # =============================================================================
  #   Easy Pie Chart
  # =============================================================================
  ###
  $(".pie-chart1").easyPieChart
    size: 200
    lineWidth: 12
    lineCap: "square"
    barColor: "#81e970"
    animate: 800
    scaleColor: false

  $(".pie-chart2").easyPieChart
    size: 200
    lineWidth: 12
    lineCap: "square"
    barColor: "#f46f50"
    animate: 800
    scaleColor: false

  $(".pie-chart3").easyPieChart
    size: 200
    lineWidth: 12
    lineCap: "square"
    barColor: "#fab43b"
    animate: 800
    scaleColor: false


  ###
  # =============================================================================
  #   Navbar scroll animation
  # =============================================================================
  ###
  $(".navbar.scroll-hide").mouseover ->
    $(".navbar.scroll-hide").removeClass "closed"
    setTimeout (->
      $(".navbar.scroll-hide").css overflow: "visible"
    ), 150

  $ ->
    lastScrollTop = 0
    delta = 50
    $(window).scroll (event) ->
      st = $(this).scrollTop()
      return  if Math.abs(lastScrollTop - st) <= delta
      if st > lastScrollTop
        # downscroll code
        $('.navbar.scroll-hide').addClass "closed"
      else
        # upscroll code
        $('.navbar.scroll-hide').removeClass "closed"
      lastScrollTop = st


  ###
  # =============================================================================
  #   Mobile Nav
  # =============================================================================
  ###
  $('.navbar-toggle').click ->
    $('body, html').toggleClass "nav-open"


  ###
  # =============================================================================
  #   Sparkline Resize Script
  # =============================================================================
  ###
  linechartResize()
  $(window).resize ->
    linechartResize()


  ###
  # =============================================================================
  #   Form wizard
  # =============================================================================
  ###
  $("#wizard").bootstrapWizard
    nextSelector: ".btn-next"
    previousSelector: ".btn-previous"
    onNext: (tab, navigation, index) ->
      if index is 1

        # Make sure we entered the name
        unless $("#name").val()
          $("#name").focus()
          $("#name").addClass("has-error");
          return false
      $total = navigation.find("li").length
      $current = index + 1
      $percent = ($current / $total) * 100
      $("#wizard").find(".progress-bar").css "width", $percent + "%"


    onPrevious: (tab, navigation, index) ->
      $total = navigation.find("li").length
      $current = index + 1
      $percent = ($current / $total) * 100
      $("#wizard").find(".progress-bar").css "width", $percent + "%"

    onTabShow: (tab, navigation, index) ->
      $total = navigation.find("li").length
      $current = index + 1
      $percent = ($current / $total) * 100
      $("#wizard").find(".progress-bar").css "width", $percent + "%"

      $(document).ready ->
      $("#rootwizard").bootstrapWizard
        onNext: (tab, navigation, index) ->

  ###
  # =============================================================================
  #   DataTables
  # =============================================================================
  ###
  $("#dataTable1").dataTable
    "sPaginationType": "full_numbers"
    aoColumnDefs: [
      bSortable: false
      aTargets: [0,-1] # <-- gets last column and turns off sorting
    ]


  ###
  # =============================================================================
  #   jQuery UI Sliders
  # =============================================================================
  ###
  $(".slider-basic").slider
    range: "min"
    value: 50
    slide: (event, ui) ->
      $(".slider-basic-amount").html "$" + ui.value
  $(".slider-basic-amount").html "$" + $(".slider-basic").slider("value")

  $(".slider-increments").slider
    range: "min"
    value: 30
    step: 10
    slide: (event, ui) ->
      $(".slider-increments-amount").html "$" + ui.value
  $(".slider-increments-amount").html "$" + $(".slider-increments").slider("value")

  $(".slider-range").slider
    range: true
    values: [ 15, 70 ]
    slide: (event, ui) ->
      $(".slider-range-amount").html "$" + ui.values[0] + " - $" + ui.values[1]
  $(".slider-range-amount").html "$" + $(".slider-range").slider("values", 0) + " - $" + $(".slider-range").slider("values", 1)


  ###
  # =============================================================================
  #   Bootstrap Tabs
  # =============================================================================
  ###
  $("#myTab a:last").tab "show"


  ###
  # =============================================================================
  #   Bootstrap Popover
  # =============================================================================
  ###
  $("#popover").popover()
  $("#popover-left").popover
    placement: "left"
  $("#popover-top").popover
    placement: "top"
  $("#popover-right").popover
    placement: "right"
  $("#popover-bottom").popover
    placement: "bottom"


  ###
  # =============================================================================
  #   Bootstrap Tooltip
  # =============================================================================
  ###
  $("#tooltip").tooltip()
  $("#tooltip-left").tooltip
    placement: "left"
  $("#tooltip-top").tooltip
    placement: "top"
  $("#tooltip-right").tooltip
    placement: "right"
  $("#tooltip-bottom").tooltip
    placement: "bottom"


  ###
  # =============================================================================
  #   jQuery VMap
  # =============================================================================
  ###
  if $("#vmap").length
    $("#vmap").vectorMap
      map: "world_en"
      backgroundColor: null
      color: "#fff"
      hoverOpacity: 0.2
      selectedColor: "#fff"
      enableZoom: true
      showTooltip: true
      values: sample_data
      scaleColors: ["#59cdfe", "#0079fe"]
      normalizeFunction: "polynomial"


  ###
  # =============================================================================
  #   Full Calendar
  # =============================================================================
  ###
  date = new Date()
  d = date.getDate()
  m = date.getMonth()
  y = date.getFullYear()

  initDrag = (el) ->

    ###
    # create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
    # it doesn't need to have a start or end
    ###
    eventObject = title: $.trim(el.text()) # use the element's text as the event title

    ###
    # store the Event Object in the DOM element so we can get to it later
    ###
    el.data "eventObject", eventObject

    ###
    # make the event draggable using jQuery UI
    ###
    el.draggable
      zIndex: 999
      revert: true # will cause the event to go back to its
      revertDuration: 0 #  original position after the drag

  addEvent = (title, priority) ->
    title = (if title.length is 0 then "Untitled Event" else title)
    priority = (if priority.length is 0 then "default" else priority)
    html = $("<div data-class=\"label label-" + priority + "\" class=\"external-event label label-" + priority + "\">" + title + "</div>")
    jQuery("#event_box").append html
    initDrag html

  $("#external-events div.external-event").each ->
    initDrag $(this)

  $("#event_add").click ->
    title = $("#event_title").val()
    priority = $("#event_priority").val()
    addEvent title, priority

  ###
  # modify chosen options
  ###
  handleDropdown = ->
    $("#event_priority_chzn .chzn-search").hide() #hide search box
    $("#event_priority_chzn_o_1").html "<span class=\"label label-default\">" + $("#event_priority_chzn_o_1").text() + "</span>"
    $("#event_priority_chzn_o_2").html "<span class=\"label label-success\">" + $("#event_priority_chzn_o_2").text() + "</span>"
    $("#event_priority_chzn_o_3").html "<span class=\"label label-info\">" + $("#event_priority_chzn_o_3").text() + "</span>"
    $("#event_priority_chzn_o_4").html "<span class=\"label label-warning\">" + $("#event_priority_chzn_o_4").text() + "</span>"
    $("#event_priority_chzn_o_5").html "<span class=\"label label-important\">" + $("#event_priority_chzn_o_5").text() + "</span>"

  $("#event_priority_chzn").click handleDropdown

  ###
  # predefined events
  ###
  addEvent "My Event 1", "primary"
  addEvent "My Event 2", "success"
  addEvent "My Event 3", "info"
  addEvent "My Event 4", "warning"
  addEvent "My Event 5", "danger"
  addEvent "My Event 6", "default"

  $("#calendar").fullCalendar
    header:
      left: "prev,next today"
      center: "title"
      right: "month,agendaWeek,agendaDay"
    editable: true
    droppable: true
    drop: (date, allDay) -> # this function is called when something is dropped
      ###
      # retrieve the dropped element's stored Event Object
      ###
      originalEventObject = $(this).data("eventObject")

      ###
      # we need to copy it, so that multiple events don't have a reference to the same object
      ###
      copiedEventObject = $.extend({}, originalEventObject)

      ###
      # assign it the date that was reported
      ###
      copiedEventObject.start = date
      copiedEventObject.allDay = allDay
      copiedEventObject.className = $(this).attr("data-class")

      ###
      # render the event on the calendar
      # the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
      ###
      $("#calendar").fullCalendar "renderEvent", copiedEventObject, true

      ###
      # is the "remove after drop" checkbox checked?
      # if so, remove the element from the "Draggable Events" list
      ###
      $(this).remove()  if $("#drop-remove").is(":checked")

    events: [
      title: "All Day Event"
      start: new Date(y, m, 1)
      className: "label label-default"
    ,
      title: "Long Event"
      start: new Date(y, m, d - 5)
      end: new Date(y, m, d - 2)
      className: "label label-success"
    ,
      id: 999
      title: "Repeating Event"
      start: new Date(y, m, d - 3, 16, 0)
      allDay: false
      className: "label label-default"
    ,
      id: 999
      title: "Repeating Event"
      start: new Date(y, m, d + 4, 16, 0)
      allDay: false
      className: "label label-important"
    ,
      title: "Meeting"
      start: new Date(y, m, d, 10, 30)
      allDay: false
      className: "label label-info"
    ,
      title: "Lunch"
      start: new Date(y, m, d, 12, 0)
      end: new Date(y, m, d, 14, 0)
      allDay: false
      className: "label label-warning"
    ,
      title: "Birthday Party"
      start: new Date(y, m, d + 1, 19, 0)
      end: new Date(y, m, d + 1, 22, 30)
      allDay: false
      className: "label label-success"
    ,
      title: "Click for Google"
      start: new Date(y, m, 28)
      end: new Date(y, m, 29)
      url: "http://google.com/"
      className: "label label-warning"
    ]


  ###
  # =============================================================================
  #   Isotope
  # =============================================================================
  ###
  $container = $(".gallery-container")
  $container.isotope {}
  $(".gallery-filters a").click ->
    selector = $(this).attr("data-filter")
    $(".gallery-filters a.selected").removeClass "selected"
    $(this).addClass "selected"
    $container.isotope
      filter: selector
    false


  ###
  # =============================================================================
  #   Popover JS
  # =============================================================================
  ###
  $('#popover').popover()


  ###
  # =============================================================================
  #   Fancybox Modal
  # =============================================================================
  ###
  $(".fancybox").fancybox
    maxWidth: 700
    height: 'auto'
    fitToView: false
    autoSize: true
    padding: 15
    nextEffect: 'fade'
    prevEffect: 'fade'
    helpers:
      title:
        type: "outside"


  ###
  # =============================================================================
  #   Morris Chart JS
  # =============================================================================
  ###
  $(window).resize (e) ->
    clearTimeout morrisResize
    morrisResize = setTimeout(->
      buildMorris true
    , 500)

  $ ->
    buildMorris()

  buildMorris = ($re) ->
    $(".graph").html "" if $re
    tax_data = [
      period: "2011 Q3"
      licensed: 3407
      sorned: 660
    ,
      period: "2011 Q2"
      licensed: 3351
      sorned: 629
    ,
      period: "2011 Q1"
      licensed: 3269
      sorned: 618
    ,
      period: "2010 Q4"
      licensed: 3246
      sorned: 661
    ,
      period: "2009 Q4"
      licensed: 3171
      sorned: 676
    ,
      period: "2008 Q4"
      licensed: 3155
      sorned: 681
    ,
      period: "2007 Q4"
      licensed: 3226
      sorned: 620
    ,
      period: "2006 Q4"
      licensed: 3245
      sorned: null
    ,
      period: "2005 Q4"
      licensed: 3289
      sorned: null
    ]

    if $('#hero-graph').length
      Morris.Line
        element: "hero-graph"
        data: tax_data
        xkey: "period"
        ykeys: ["licensed", "sorned"]
        labels: ["Licensed", "Off the road"]
        lineColors: ["#5bc0de", "#60c560"]

    if $('#hero-donut').length
      Morris.Donut
        element: "hero-donut"
        data: [
          label: "Development"
          value: 25
        ,
          label: "Sales & Marketing"
          value: 40
        ,
          label: "User Experience"
          value: 25
        ,
          label: "Human Resources"
          value: 10
        ]
        colors: ["#f0ad4e"]
        formatter: (y) ->
          y + "%"

    if $('#hero-area').length
      Morris.Area
        element: "hero-area"
        data: [
          period: "2010 Q1"
          iphone: 2666
          ipad: null
          itouch: 2647
        ,
          period: "2010 Q2"
          iphone: 2778
          ipad: 2294
          itouch: 2441
        ,
          period: "2010 Q3"
          iphone: 4912
          ipad: 1969
          itouch: 2501
        ,
          period: "2010 Q4"
          iphone: 3767
          ipad: 3597
          itouch: 5689
        ,
          period: "2011 Q1"
          iphone: 6810
          ipad: 1914
          itouch: 2293
        ,
          period: "2011 Q2"
          iphone: 5670
          ipad: 4293
          itouch: 1881
        ,
          period: "2011 Q3"
          iphone: 4820
          ipad: 3795
          itouch: 1588
        ,
          period: "2011 Q4"
          iphone: 15073
          ipad: 5967
          itouch: 5175
        ,
          period: "2012 Q1"
          iphone: 10687
          ipad: 4460
          itouch: 2028
        ,
          period: "2012 Q2"
          iphone: 8432
          ipad: 5713
          itouch: 1791
        ]
        xkey: "period"
        ykeys: ["iphone", "ipad", "itouch"]
        labels: ["iPhone", "iPad", "iPod Touch"]
        hideHover: "auto"
        lineWidth: 2
        pointSize: 4
        lineColors: ["#a0dcee", "#f1c88e", "#a0e2a0"]
        fillOpacity: 0.5
        smooth: true

    if $('#hero-bar').length
      Morris.Bar
        element: "hero-bar"
        data: [
          device: "iPhone"
          geekbench: 136
        ,
          device: "iPhone 3G"
          geekbench: 137
        ,
          device: "iPhone 3GS"
          geekbench: 275
        ,
          device: "iPhone 4"
          geekbench: 380
        ,
          device: "iPhone 4S"
          geekbench: 655
        ,
          device: "iPhone 5"
          geekbench: 1571
        ]
        xkey: "device"
        ykeys: ["geekbench"]
        labels: ["Geekbench"]
        barRatio: 0.4
        xLabelAngle: 35
        hideHover: "auto"
        barColors: ["#5bc0de"]

  ###
  # =============================================================================
  #   Select2
  # =============================================================================
  ###
  $('.select2able').select2()


  ###
  # =============================================================================
  #   Masonry
  # =============================================================================
  ###
  $container = $("#social-container").masonry()
  $container.imagesLoaded ->
    $container.masonry
      "isFitWidth": true
      gutter: 20
      isFitWidth: true
      itemSelector: ".item"


  ###
  # =============================================================================
  #   WYSIWYG Editor
  # =============================================================================
  ###
  $('#editor').wysiwyg();


  ###
  # =============================================================================
  #   Skycons
  # =============================================================================
  ###
  $('.skycons-element').each ->
    skycons = new Skycons(color: "white")
    canvasId = $(@).attr('id')
    weatherSetting = $(@).data('skycons')
    skycons.add canvasId, Skycons[weatherSetting]
    skycons.play()
