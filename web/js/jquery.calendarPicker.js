jQuery.fn.calendarPicker = function(options) {
  // --------------------------  start default option values --------------------------
  if (!options.date) {
    options.date = new Date();
  }

  if (typeof(options.years) == "undefined")
    options.years=1;

  if (typeof(options.enableYears) == "undefined")
    options.enableYears=false;
  
  if (typeof(options.disablePreviousDate) == "undefined")
    options.disablePreviousDate=true;

  if (typeof(options.months) == "undefined")
    options.months=3;

  if (typeof(options.eventDates) == "undefined")
    options.eventDates = new Array();

  if (typeof(options.days) == "undefined")
    options.days=4;

  if (typeof(options.showDayArrows) == "undefined")
    options.showDayArrows=true;

  if (typeof(options.useWheel) == "undefined")
    options.useWheel=true;

  if (typeof(options.callbackDelay) == "undefined")
    options.callbackDelay=500;
  
  if (typeof(options.monthNames) == "undefined")
    options.monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

  if (typeof(options.dayNames) == "undefined")
    options.dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];


  // --------------------------  end default option values --------------------------

  var calendar = {currentDate: options.date};
  calendar.options = options;

  //build the calendar on the first element in the set of matched elements.
  var theDiv = this.eq(0);//$(this);
  theDiv.addClass("calBox");

  //empty the div
  theDiv.empty();


  var divYears = $("<div>").addClass("calYear");
  var divMonths = $("<div>").addClass("calMonth");
  var divDays = $("<div>").addClass("calDay");

  if (options.enableYears) {
    theDiv.append(divYears).append(divMonths).append(divDays);
  } else {
    theDiv.append(divMonths).append(divDays);
  }

  calendar.changeDate = function(date) {

    // only allow the future date
    if (date < options.date) {
      calendar.changeDate(options.date);
      return;
    };

    calendar.currentDate = date;
   
    var fillYears = function(date) {
      var year = date.getFullYear();
      var t = new Date();
      divYears.empty();
      var nc = options.years*2+1;
      var w = parseInt((theDiv.width()-4-(nc)*4)/nc)+"px";
      for (var i = year - options.years; i <= year + options.years; i++) {
        var d = new Date(date);
        d.setFullYear(i);
        var span = $("<span>").addClass("calElement").attr("millis", d.getTime()).html(i).css("width",w);
        if (d.getYear() == t.getYear())
          span.addClass("today");
        if (d.getYear() == calendar.currentDate.getYear())
          span.addClass("selected");
        divYears.append(span);
      }
    }

    var fillMonths = function(date) {
      var month = date.getMonth();
      var t = new Date();
      divMonths.empty();
      var oldday = date.getDay();
      var nc = options.months*2+1;
      var w = parseInt((theDiv.width()-4-(nc)*4)/nc)+"px";
      for (var i = -options.months; i <= options.months; i++) {
        var d = new Date(date);
        var oldday = d.getDate();
        d.setMonth(month + i);

        if (d.getDate() != oldday) {
          d.setMonth(d.getMonth() - 1);
          d.setDate(28);
        }
        var span = $("<span>").addClass("calElement").attr("millis", d.getTime()).html(options.monthNames[d.getMonth()]).css("width",w);
        if (d.getYear() == t.getYear() && d.getMonth() == t.getMonth())
          span.addClass("today");
        if (d.getYear() == calendar.currentDate.getYear() && d.getMonth() == calendar.currentDate.getMonth())
          span.addClass("selected");
        divMonths.append(span);
        disableOldDays(d, span);
      }
    }

    var fillDays = function(date) {
      var day = date.getDate();
      var t = new Date();
      divDays.empty();
      var nc = options.days*2+1;
      var stop = options.days*2 - 1;//Stop the loop to show the days
      var w = parseInt((theDiv.width()-4-(options.showDayArrows?12:0)-(nc)*4)/(nc-(options.showDayArrows?2:0)))+"px";
      // for (var i = -options.days; i <= options.days; i++) {
      console.log(date);
      for (var i = -1; i <= stop; i++) {
        var d = new Date(date);
        d.setDate(day + i);
        var span = $("<span>").addClass("calElement").attr("millis", d.getTime())
        if (i == -1 && options.showDayArrows) {
          span.addClass("prev");
        } else if (i == stop && options.showDayArrows) {
          span.addClass("next");
        } else {
          span.html("<span class=dayNumber>" + d.getDate() + "</span><br>" + options.dayNames[d.getDay()]).css("width",w);
          if (d.getYear() == t.getYear() && d.getMonth() == t.getMonth() && d.getDate() == t.getDate())
            span.addClass("today");
          if (d.getYear() == calendar.currentDate.getYear() && d.getMonth() == calendar.currentDate.getMonth() && d.getDate() == calendar.currentDate.getDate())
            span.addClass("selected");
          disableOldDays(d, span);
          addEventIndicator(d, span);
        }
        //Disable all the out dated
        divDays.append(span);
      }
    }

    var deferredCallBack = function() {
      if (typeof(options.callback) == "function") {
        if (calendar.timer)
          clearTimeout(calendar.timer);

        calendar.timer = setTimeout(function() {
          options.callback(calendar);
        }, options.callbackDelay);
      }
    }


    // Disabled all the days before today.
    // date - all the visible date on the window
    // obj - all the visible span on the window
    var disableOldDays = function(date, obj) {
      if (date < options.date && !obj.hasClass("today")) {
        obj.addClass("disabled");
      };
    }

    // Add event indicator
    // if there is an event, adding a small red triangle at the bottom of the day
    var addEventIndicator = function(date, obj) {
      if (!obj.hasClass('hasEvent') && ($.inArray(date.toDateString(), options.eventDates) != -1)) {
        obj.addClass("hasEvent");
      };
    }

    if (options.enableYears) {
       fillYears(date);
    };
    fillMonths(date);
    fillDays(date);

    deferredCallBack();

  }

  theDiv.click(function(ev) {
    var el = $(ev.target).closest(".calElement");
    if (el.hasClass("calElement")) {
      calendar.changeDate(new Date(parseInt(el.attr("millis"))));
    }
  });

  calendar.changeDate(options.date);

  return calendar;
};
