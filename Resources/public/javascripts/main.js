/*
# =============================================================================
#   Sparkline Linechart JS
# =============================================================================
*/


(function() {
  var linechartResize;

  linechartResize = function() {
    $("#linechart-1").sparkline([160, 240, 120, 200, 180, 350, 230, 200, 280, 380, 400, 360, 300, 220, 200, 150, 40, 70, 180, 110, 200, 160, 200, 220], {
      type: "line",
      width: "100%",
      height: "226",
      lineColor: "#a5e1ff",
      fillColor: "rgba(241, 251, 255, 0.9)",
      lineWidth: 2,
      spotColor: "#a5e1ff",
      minSpotColor: "#bee3f6",
      maxSpotColor: "#a5e1ff",
      highlightSpotColor: "#80cff4",
      highlightLineColor: "#cccccc",
      spotRadius: 6,
      chartRangeMin: 0
    });
    $("#linechart-1").sparkline([100, 280, 150, 180, 220, 180, 130, 180, 180, 280, 260, 260, 200, 120, 200, 150, 100, 100, 180, 180, 200, 160, 180, 120], {
      type: "line",
      width: "100%",
      height: "226",
      lineColor: "#cfee74",
      fillColor: "rgba(244, 252, 225, 0.5)",
      lineWidth: 2,
      spotColor: "#b9e72a",
      minSpotColor: "#bfe646",
      maxSpotColor: "#b9e72a",
      highlightSpotColor: "#b9e72a",
      highlightLineColor: "#cccccc",
      spotRadius: 6,
      chartRangeMin: 0,
      composite: true
    });
    $("#linechart-2").sparkline([160, 240, 250, 280, 300, 250, 230, 200, 280, 380, 400, 360, 300, 220, 200, 150, 100, 100, 180, 180, 200, 160, 220, 140], {
      type: "line",
      width: "100%",
      height: "226",
      lineColor: "#a5e1ff",
      fillColor: "rgba(241, 251, 255, 0.9)",
      lineWidth: 2,
      spotColor: "#a5e1ff",
      minSpotColor: "#bee3f6",
      maxSpotColor: "#a5e1ff",
      highlightSpotColor: "#80cff4",
      highlightLineColor: "#cccccc",
      spotRadius: 6,
      chartRangeMin: 0
    });
    $("#linechart-3").sparkline([100, 280, 150, 180, 220, 180, 130, 180, 180, 280, 260, 260, 200, 120, 200, 150, 100, 100, 180, 180, 200, 160, 220, 140], {
      type: "line",
      width: "100%",
      height: "226",
      lineColor: "#cfee74",
      fillColor: "rgba(244, 252, 225, 0.5)",
      lineWidth: 2,
      spotColor: "#b9e72a",
      minSpotColor: "#bfe646",
      maxSpotColor: "#b9e72a",
      highlightSpotColor: "#b9e72a",
      highlightLineColor: "#cccccc",
      spotRadius: 6,
      chartRangeMin: 0
    });
    $("#linechart-4").sparkline([100, 220, 150, 140, 200, 180, 130, 180, 180, 210, 240, 200, 170, 120, 200, 150, 100, 100], {
      type: "line",
      width: "100",
      height: "30",
      lineColor: "#adadad",
      fillColor: "rgba(244, 252, 225, 0.0)",
      lineWidth: 2,
      spotColor: "#909090",
      minSpotColor: "#909090",
      maxSpotColor: "#909090",
      highlightSpotColor: "#666",
      highlightLineColor: "#666",
      spotRadius: 0,
      chartRangeMin: 0
    });
    $("#linechart-5").sparkline([100, 220, 150, 140, 200, 180, 130, 180, 180, 210, 240, 200, 170, 120, 200, 150, 100, 100], {
      type: "line",
      width: "100",
      height: "30",
      lineColor: "#adadad",
      fillColor: "rgba(244, 252, 225, 0.0)",
      lineWidth: 2,
      spotColor: "#909090",
      minSpotColor: "#909090",
      maxSpotColor: "#909090",
      highlightSpotColor: "#666",
      highlightLineColor: "#666",
      spotRadius: 0,
      chartRangeMin: 0
    });
    $("#barchart-2").sparkline([160, 220, 260, 120, 320, 260, 300, 160, 240, 100, 240, 120], {
      type: "bar",
      height: "226",
      barSpacing: 8,
      barWidth: 18,
      barColor: "#8fdbda"
    });
    $("#composite-chart-1").sparkline([160, 220, 260, 120, 320, 260, 300, 160, 240, 100, 240, 120], {
      type: "bar",
      height: "226",
      barSpacing: 8,
      barWidth: 18,
      barColor: "#8fdbda"
    });
    return $("#composite-chart-1").sparkline([100, 280, 150, 180, 220, 180, 130, 180, 180, 280, 260, 260], {
      type: "line",
      width: "100%",
      height: "226",
      lineColor: "#cfee74",
      fillColor: "rgba(244, 252, 225, 0.5)",
      lineWidth: 2,
      spotColor: "#b9e72a",
      minSpotColor: "#bfe646",
      maxSpotColor: "#b9e72a",
      highlightSpotColor: "#b9e72a",
      highlightLineColor: "#cccccc",
      spotRadius: 6,
      chartRangeMin: 0,
      composite: true
    });
  };

  $(document).ready(function() {
    /*
    # =============================================================================
    #   Sparkline Linechart JS
    # =============================================================================
    */

    var $container, addEvent, buildMorris, d, date, handleDropdown, initDrag, m, y;
    $("#barcharts").sparkline([190, 220, 210, 220, 220, 260, 300, 220, 240, 240, 220, 200, 240, 260, 210], {
      type: "bar",
      height: "100",
      barSpacing: 4,
      barWidth: 13,
      barColor: "#cbcbcb",
      highlightColor: "#89D1E6"
    });
    $("#pie-chart").sparkline([2, 8, 6, 10], {
      type: "pie",
      height: "220",
      width: "220",
      offset: "+90",
      sliceColors: ["#a0eeed", "#81e970", "#f5af50", "#f46f50"]
    });
    $(".sparkslim").sparkline('html', {
      type: "line",
      width: "100",
      height: "30",
      lineColor: "#adadad",
      fillColor: "rgba(244, 252, 225, 0.0)",
      lineWidth: 2,
      spotColor: "#909090",
      minSpotColor: "#909090",
      maxSpotColor: "#909090",
      highlightSpotColor: "#666",
      highlightLineColor: "#666",
      spotRadius: 0,
      chartRangeMin: 0
    });
    /*
    # =============================================================================
    #   Easy Pie Chart
    # =============================================================================
    */

    $(".pie-chart1").easyPieChart({
      size: 200,
      lineWidth: 12,
      lineCap: "square",
      barColor: "#81e970",
      animate: 800,
      scaleColor: false
    });
    $(".pie-chart2").easyPieChart({
      size: 200,
      lineWidth: 12,
      lineCap: "square",
      barColor: "#f46f50",
      animate: 800,
      scaleColor: false
    });
    $(".pie-chart3").easyPieChart({
      size: 200,
      lineWidth: 12,
      lineCap: "square",
      barColor: "#fab43b",
      animate: 800,
      scaleColor: false
    });
    /*
    # =============================================================================
    #   Navbar scroll animation
    # =============================================================================
    */

    /*$(".navbar.scroll-hide").mouseover(function() {
      $(".navbar.scroll-hide").removeClass("closed");
      return setTimeout((function() {
        return $(".navbar.scroll-hide").css({
          overflow: "visible"
        });
      }), 150);
    });
    $(function() {
      var delta, lastScrollTop;
      lastScrollTop = 0;
      delta = 50;
      return $(window).scroll(function(event) {
        var st;
        st = $(this).scrollTop();
        if (Math.abs(lastScrollTop - st) <= delta) {
          return;
        }
        if (st > lastScrollTop) {
          $('.navbar.scroll-hide').addClass("closed");
        } else {
          $('.navbar.scroll-hide').removeClass("closed");
        }
        return lastScrollTop = st;
      });
    });*/
    /*
    # =============================================================================
    #   Mobile Nav
    # =============================================================================
    */

    $('.navbar-toggle').click(function() {
      return $('body, html').toggleClass("nav-open");
    });
    /*
    # =============================================================================
    #   Sparkline Resize Script
    # =============================================================================
    */

    linechartResize();
    $(window).resize(function() {
      return linechartResize();
    });
    /*
    # =============================================================================
    #   Form wizard
    # =============================================================================
    */

    $("#wizard").bootstrapWizard({
      nextSelector: ".btn-next",
      previousSelector: ".btn-previous",
      onNext: function(tab, navigation, index) {
        var $current, $percent, $total;
        if (index === 1) {
          if (!$("#name").val()) {
            $("#name").focus();
            $("#name").addClass("has-error");
            return false;
          }
        }
        $total = navigation.find("li").length;
        $current = index + 1;
        $percent = ($current / $total) * 100;
        return $("#wizard").find(".progress-bar").css("width", $percent + "%");
      },
      onPrevious: function(tab, navigation, index) {
        var $current, $percent, $total;
        $total = navigation.find("li").length;
        $current = index + 1;
        $percent = ($current / $total) * 100;
        return $("#wizard").find(".progress-bar").css("width", $percent + "%");
      },
      onTabShow: function(tab, navigation, index) {
        var $current, $percent, $total;
        $total = navigation.find("li").length;
        $current = index + 1;
        $percent = ($current / $total) * 100;
        $("#wizard").find(".progress-bar").css("width", $percent + "%");
        $(document).ready(function() {});
        return $("#rootwizard").bootstrapWizard({
          onNext: function(tab, navigation, index) {}
        });
      }
    });
    /*
    # =============================================================================
    #   DataTables
    # =============================================================================
    */

    $(".dataTable:not(.ajaxTable)").dataTable({
      "sPaginationType": "full_numbers",
      aoColumnDefs: [
        {
          bSortable: false,
          aTargets: [0, -1, "sorting_disabled"]
        }
      ]
    });

    $(".dataTable.ajaxTable").dataTable({
      "sPaginationType": "full_numbers",
      aoColumnDefs: [
        {
          bSortable: false,
          aTargets: [0, -1, "sorting_disabled"]
        }
      ],
      "processing": true,
      "serverSide": true,
      "ajax": $(this).data('url')
    });
    /*
    # =============================================================================
    #   jQuery UI Sliders
    # =============================================================================
    */

    $(".slider-basic").slider({
      range: "min",
      value: 50,
      slide: function(event, ui) {
        return $(".slider-basic-amount").html("$" + ui.value);
      }
    });
    $(".slider-basic-amount").html("$" + $(".slider-basic").slider("value"));
    $(".slider-increments").slider({
      range: "min",
      value: 30,
      step: 10,
      slide: function(event, ui) {
        return $(".slider-increments-amount").html("$" + ui.value);
      }
    });
    $(".slider-increments-amount").html("$" + $(".slider-increments").slider("value"));
    $(".slider-range").slider({
      range: true,
      values: [15, 70],
      slide: function(event, ui) {
        return $(".slider-range-amount").html("$" + ui.values[0] + " - $" + ui.values[1]);
      }
    });
    $(".slider-range-amount").html("$" + $(".slider-range").slider("values", 0) + " - $" + $(".slider-range").slider("values", 1));
    /*
    # =============================================================================
    #   Bootstrap Tabs
    # =============================================================================
    */

    $("#myTab a:last").tab("show");
    /*
    # =============================================================================
    #   Bootstrap Popover
    # =============================================================================
    */

    $("#popover").popover();
    $("#popover-left").popover({
      placement: "left"
    });
    $("#popover-top").popover({
      placement: "top"
    });
    $("#popover-right").popover({
      placement: "right"
    });
    $("#popover-bottom").popover({
      placement: "bottom"
    });
    /*
    # =============================================================================
    #   Bootstrap Tooltip
    # =============================================================================
    */

    $(".tooltip").tooltip();
    /*
    # =============================================================================
    #   jQuery VMap
    # =============================================================================
    */

    if ($("#vmap").length) {
      $("#vmap").vectorMap({
        map: "world_en",
        backgroundColor: null,
        color: "#fff",
        hoverOpacity: 0.2,
        selectedColor: "#fff",
        enableZoom: true,
        showTooltip: true,
        values: sample_data,
        scaleColors: ["#59cdfe", "#0079fe"],
        normalizeFunction: "polynomial"
      });
    }
    /*
    # =============================================================================
    #   Full Calendar
    # =============================================================================
    */

    date = new Date();
    d = date.getDate();
    m = date.getMonth();
    y = date.getFullYear();
    initDrag = function(el) {
      /*
      # create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
      # it doesn't need to have a start or end
      */

      var eventObject;
      eventObject = {
        title: $.trim(el.text())
      };
      /*
      # store the Event Object in the DOM element so we can get to it later
      */

      el.data("eventObject", eventObject);
      /*
      # make the event draggable using jQuery UI
      */

      return el.draggable({
        zIndex: 999,
        revert: true,
        revertDuration: 0
      });
    };
    addEvent = function(title, priority) {
      var html;
      title = (title.length === 0 ? "Untitled Event" : title);
      priority = (priority.length === 0 ? "default" : priority);
      html = $("<div data-class=\"label label-" + priority + "\" class=\"external-event label label-" + priority + "\">" + title + "</div>");
      jQuery("#event_box").append(html);
      return initDrag(html);
    };
    $("#external-events div.external-event").each(function() {
      return initDrag($(this));
    });
    $("#event_add").click(function() {
      var priority, title;
      title = $("#event_title").val();
      priority = $("#event_priority").val();
      return addEvent(title, priority);
    });
    /*
    # modify chosen options
    */

    handleDropdown = function() {
      $("#event_priority_chzn .chzn-search").hide();
      $("#event_priority_chzn_o_1").html("<span class=\"label label-default\">" + $("#event_priority_chzn_o_1").text() + "</span>");
      $("#event_priority_chzn_o_2").html("<span class=\"label label-success\">" + $("#event_priority_chzn_o_2").text() + "</span>");
      $("#event_priority_chzn_o_3").html("<span class=\"label label-info\">" + $("#event_priority_chzn_o_3").text() + "</span>");
      $("#event_priority_chzn_o_4").html("<span class=\"label label-warning\">" + $("#event_priority_chzn_o_4").text() + "</span>");
      return $("#event_priority_chzn_o_5").html("<span class=\"label label-important\">" + $("#event_priority_chzn_o_5").text() + "</span>");
    };
    $("#event_priority_chzn").click(handleDropdown);
    /*
    # predefined events
    */

    addEvent("My Event 1", "primary");
    addEvent("My Event 2", "success");
    addEvent("My Event 3", "info");
    addEvent("My Event 4", "warning");
    addEvent("My Event 5", "danger");
    addEvent("My Event 6", "default");
    $("#calendar").fullCalendar({
      header: {
        left: "prev,next today",
        center: "title",
        right: "month,agendaWeek,agendaDay"
      },
      editable: true,
      droppable: true,
      drop: function(date, allDay) {
        /*
        # retrieve the dropped element's stored Event Object
        */

        var copiedEventObject, originalEventObject;
        originalEventObject = $(this).data("eventObject");
        /*
        # we need to copy it, so that multiple events don't have a reference to the same object
        */

        copiedEventObject = $.extend({}, originalEventObject);
        /*
        # assign it the date that was reported
        */

        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;
        copiedEventObject.className = $(this).attr("data-class");
        /*
        # render the event on the calendar
        # the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        */

        $("#calendar").fullCalendar("renderEvent", copiedEventObject, true);
        /*
        # is the "remove after drop" checkbox checked?
        # if so, remove the element from the "Draggable Events" list
        */

        if ($("#drop-remove").is(":checked")) {
          return $(this).remove();
        }
      },
      events: [
        {
          title: "All Day Event",
          start: new Date(y, m, 1),
          className: "label label-default"
        }, {
          title: "Long Event",
          start: new Date(y, m, d - 5),
          end: new Date(y, m, d - 2),
          className: "label label-success"
        }, {
          id: 999,
          title: "Repeating Event",
          start: new Date(y, m, d - 3, 16, 0),
          allDay: false,
          className: "label label-default"
        }, {
          id: 999,
          title: "Repeating Event",
          start: new Date(y, m, d + 4, 16, 0),
          allDay: false,
          className: "label label-important"
        }, {
          title: "Meeting",
          start: new Date(y, m, d, 10, 30),
          allDay: false,
          className: "label label-info"
        }, {
          title: "Lunch",
          start: new Date(y, m, d, 12, 0),
          end: new Date(y, m, d, 14, 0),
          allDay: false,
          className: "label label-warning"
        }, {
          title: "Birthday Party",
          start: new Date(y, m, d + 1, 19, 0),
          end: new Date(y, m, d + 1, 22, 30),
          allDay: false,
          className: "label label-success"
        }, {
          title: "Click for Google",
          start: new Date(y, m, 28),
          end: new Date(y, m, 29),
          url: "http://google.com/",
          className: "label label-warning"
        }
      ]
    });
    /*
    # =============================================================================
    #   Isotope
    # =============================================================================
    */

    $container = $(".gallery-container");
    $container.isotope({});
    $(".gallery-filters a").click(function() {
      var selector;
      selector = $(this).attr("data-filter");
      $(".gallery-filters a.selected").removeClass("selected");
      $(this).addClass("selected");
      $container.isotope({
        filter: selector
      });
      return false;
    });
    /*
    # =============================================================================
    #   Popover JS
    # =============================================================================
    */

    $('#popover').popover();
    /*
    # =============================================================================
    #   Fancybox Modal
    # =============================================================================
    */

    $(".fancybox").fancybox({
      maxWidth: 700,
      height: 'auto',
      fitToView: false,
      autoSize: true,
      padding: 15,
      nextEffect: 'fade',
      prevEffect: 'fade',
      helpers: {
        title: {
          type: "outside"
        }
      }
    });
    /*
    # =============================================================================
    #   Morris Chart JS
    # =============================================================================
    */

    $(window).resize(function(e) {
      var morrisResize;
      clearTimeout(morrisResize);
      return morrisResize = setTimeout(function() {
        return buildMorris(true);
      }, 500);
    });
    $(function() {
      return buildMorris();
    });
    buildMorris = function($re) {
      var tax_data;
      if ($re) {
        $(".graph").html("");
      }
      tax_data = [
        {
          period: "2011 Q3",
          licensed: 3407,
          sorned: 660
        }, {
          period: "2011 Q2",
          licensed: 3351,
          sorned: 629
        }, {
          period: "2011 Q1",
          licensed: 3269,
          sorned: 618
        }, {
          period: "2010 Q4",
          licensed: 3246,
          sorned: 661
        }, {
          period: "2009 Q4",
          licensed: 3171,
          sorned: 676
        }, {
          period: "2008 Q4",
          licensed: 3155,
          sorned: 681
        }, {
          period: "2007 Q4",
          licensed: 3226,
          sorned: 620
        }, {
          period: "2006 Q4",
          licensed: 3245,
          sorned: null
        }, {
          period: "2005 Q4",
          licensed: 3289,
          sorned: null
        }
      ];
      if ($('#hero-graph').length) {
        Morris.Line({
          element: "hero-graph",
          data: tax_data,
          xkey: "period",
          ykeys: ["licensed", "sorned"],
          labels: ["Licensed", "Off the road"],
          lineColors: ["#5bc0de", "#60c560"]
        });
      }
      if ($('#hero-donut').length) {
        Morris.Donut({
          element: "hero-donut",
          data: [
            {
              label: "Development",
              value: 25
            }, {
              label: "Sales & Marketing",
              value: 40
            }, {
              label: "User Experience",
              value: 25
            }, {
              label: "Human Resources",
              value: 10
            }
          ],
          colors: ["#f0ad4e"],
          formatter: function(y) {
            return y + "%";
          }
        });
      }
      
      if ($('#hero-bar').length) {
        return Morris.Bar({
          element: "hero-bar",
          data: [
            {
              device: "iPhone",
              geekbench: 136
            }, {
              device: "iPhone 3G",
              geekbench: 137
            }, {
              device: "iPhone 3GS",
              geekbench: 275
            }, {
              device: "iPhone 4",
              geekbench: 380
            }, {
              device: "iPhone 4S",
              geekbench: 655
            }, {
              device: "iPhone 5",
              geekbench: 1571
            }
          ],
          xkey: "device",
          ykeys: ["geekbench"],
          labels: ["Geekbench"],
          barRatio: 0.4,
          xLabelAngle: 35,
          hideHover: "auto",
          barColors: ["#5bc0de"]
        });
      }
    };
    /*
    # =============================================================================
    #   Select2
    # =============================================================================
    */

    $('.select2able').select2({ placeholder: "Select an option", allowClear: true});
    
    /*
    # =============================================================================
    #   Masonry
    # =============================================================================
    */

    $container = $("#social-container").masonry();
    $container.imagesLoaded(function() {
      return $container.masonry({
        "isFitWidth": true,
        gutter: 20,
        isFitWidth: true,
        itemSelector: ".item"
      });
    });
    /*
    # =============================================================================
    #   WYSIWYG Editor
    # =============================================================================
    */

    $('#editor').wysiwyg();
    /*
    # =============================================================================
    #   Skycons
    # =============================================================================
    */

    return $('.skycons-element').each(function() {
      var canvasId, skycons, weatherSetting;
      skycons = new Skycons({
        color: "white"
      });
      canvasId = $(this).attr('id');
      weatherSetting = $(this).data('skycons');
      skycons.add(canvasId, Skycons[weatherSetting]);
      return skycons.play();
    });

    /*
    # =============================================================================
    #   Drag and drop files
    # =============================================================================
    */

    $(".single-file-drop").each(function() {
      var $dropbox;
      $dropbox = $(this);
      if (typeof window.FileReader === "undefined") {
        $("small", this).html("File API & FileReader API not supported").addClass("text-danger");
        return;
      }
      this.ondragover = function() {
        $dropbox.addClass("hover");
        return false;
      };
      this.ondragend = function() {
        $dropbox.removeClass("hover");
        return false;
      };
      return this.ondrop = function(e) {
        var file, reader;
        e.preventDefault();
        $dropbox.removeClass("hover").html("");
        file = e.dataTransfer.files[0];
        reader = new FileReader();
        reader.onload = function(event) {
          return $dropbox.append($("<img>").attr("src", event.target.result));
        };
        reader.readAsDataURL(file);
        return false;
      };
    });
    /*
    # =============================================================================
    #   File upload buttons
    # =============================================================================
    */

    $('.fileupload').fileupload();
  });

}).call(this);
