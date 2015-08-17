// Generated by CoffeeScript 1.9.3
(function() {
  var appendEvent, appendPost, getEvents, getPosts, limit, nextPage;

  nextPage = 0;

  limit = 5;

  getPosts = function(page, limit) {
    limit = limit || 5;
    return FB.api({
      method: 'fql.query',
      query: 'SELECT cover_pid, description, name, link, backdated_time FROM album WHERE owner=292891094182467 AND backdated_time ORDER BY backdated_time DESC LIMIT ' + limit + ' OFFSET ' + (page * limit)
    }, function(response) {
      var i, len, post;
      for (i = 0, len = response.length; i < len; i++) {
        post = response[i];
        appendPost(post);
      }
      if (response.length >= limit) {
        return $('.main-content').append($('<div/>').addClass('load-more').on('click', function() {
          $(this).remove();
          return getPosts(++nextPage);
        }));
      }
    });
  };

  appendPost = function(data) {
    var $date, $elem, $photo, $title, $top, $words, anchor, nextRef, pts, ref;
    if (!isNaN(new Date(data.name.split(' - ')[0]).valueOf())) {
      data.name = data.name.substring(data.name.indexOf(' - ') + 3);
    }
    if (data.hasOwnProperty('description')) {
      while ((nextRef = data.description.indexOf('@[')) >= 0) {
        ref = data.description.substring(nextRef, data.description.indexOf(']', nextRef) + 1);
        pts = ref.substring(2, ref.length - 1).split(':');
        anchor = '<a href="//facebook.com/' + pts[0] + '">' + pts[2] + '</a>';
        data.description = data.description.replace(ref, anchor);
      }
    }
    $elem = $('<div/>').addClass('post').append($top = $('<div/>').addClass('top').append($title = $('<div/>').addClass('title').attr('title', data.name).text(data.name)).append($date = $('<div/>').addClass('date').text(new Date(data.backdated_time * 1000).toString('MMMM d, yyyy')))).append($photo = $('<a/>').attr('target', '_blank').addClass('photo').addClass('loading')).append($words = $('<div/>').addClass('words').html(data.description));
    FB.api({
      method: 'fql.query',
      query: 'SELECT src_big FROM photo WHERE pid="' + data.cover_pid + '"'
    }, function(response) {
      return $('<img/>').attr('src', response[0].src_big).load(function() {
        return $photo.attr('href', data.link).css('background-image', 'url(' + response[0].src_big + ')').animate({
          height: '332px'
        }, 1000, function() {
          return $photo.css('height', '');
        }).removeClass('loading');
      });
    });
    return $('.main-content').append($elem);
  };

  getEvents = function() {
    var today;
    if (gapi.client === void 0) {
      window.setTimeout(getEvents, 100);
      return;
    }
    today = new Date().clearTime();
    gapi.client.setApiKey('AIzaSyCvuJzS-Q7uGdliRFqySq0mYar0YOBQEGE');
    return gapi.client.load('calendar', 'v3', function() {
      return gapi.client.calendar.events.list({
        calendarId: 'pccrovers.com_pojeic2sd1ojijt7ohop7gt338@group.calendar.google.com',
        orderBy: 'startTime',
        singleEvents: true,
        timeMin: today.toISOString(),
        timeMax: today.moveToDayOfWeek(0).addWeeks(8).toISOString(),
        timeZone: 'America/Vancouver',
        fields: 'items(summary,description,start,end,endTimeUnspecified,location,htmlLink,updated)'
      }).execute(function(response) {
        var date, dates, events, fn, i, item, len, ref1;
        if (response.hasOwnProperty('error')) {
          return;
        }
        dates = {};
        ref1 = response.items;
        fn = function(item) {
          var start;
          if (!item.hasOwnProperty('summary')) {
            return;
          }
          start = Date.parse(item.start.dateTime || item.start.date);
          if (!dates.hasOwnProperty(start.toString('MMM d'))) {
            dates[start.toString('MMM d')] = [];
          }
          return dates[start.toString('MMM d')].push(item);
        };
        for (i = 0, len = ref1.length; i < len; i++) {
          item = ref1[i];
          fn(item);
        }
        for (date in dates) {
          events = dates[date];
          appendEvent(date, events);
        }
      });
    });
  };

  appendEvent = function(date, events) {
    var $container, $date, $events, $label, event, fn, i, len;
    events.sort(function(a, b) {
      var rowA, rowB;
      rowA = a.summary.toLowerCase();
      rowB = b.summary.toLowerCase();
      if (rowA < rowB) {
        return -1;
      } else if (rowA > rowB) {
        return 1;
      } else {
        return 0;
      }
    });
    $date = $('<li/>').addClass('item').append($label = $('<a/>').attr('href', 'javascript: void(0);').addClass('summary').addClass('day').text(date).on('click', function() {
      $(this).next().slideToggle(200);
      return $(this).toggleClass('open');
    })).append($container = $('<div/>').css('display', 'none').append($events = $('<ul/>').addClass('events')));
    fn = function(event) {
      var $event, $eventLabel, $props, end, isAllDay, isMultiDay, key, prefix, props, start, value;
      if ((prefix = event.summary.indexOf('GC')) >= 0) {
        $label.text(date + event.summary.substring(prefix + 2));
        return;
      }
      props = {};
      start = Date.parse(event.start.dateTime || event.start.date);
      end = Date.parse(event.end.dateTime || event.end.date);
      isAllDay = !event.start.hasOwnProperty('dateTime');
      isMultiDay = start.getDate() !== end.getDate();
      props['Date'] = start.toString('dddd MMMM d, yyyy');
      props['Time'] = isAllDay ? 'All Day' : start.toString((isMultiDay ? 'ddd ' : '') + 'h:mmtt') + ' &ndash; ' + end.toString((isMultiDay ? 'ddd ' : '') + 'h:mmtt');
      if (event.location) {
        props['Location'] = '<a href="//maps.google.ca/maps?hl=en&q=' + event.location + '&source=calendar">' + event.location + '</a>';
      }
      $event = $('<li/>').addClass('item').append($eventLabel = $('<a/>').attr('href', 'javascript: void(0);').addClass('summary').text(event.summary).on('click', function() {
        $(this).next().slideToggle(200);
        return $(this).toggleClass('open');
      })).append($props = $('<div/>').css('display', 'none'));
      for (key in props) {
        value = props[key];
        $props.append('<b>' + key + ':</b> ' + value + '<br>');
      }
      return $events.append($event);
    };
    for (i = 0, len = events.length; i < len; i++) {
      event = events[i];
      fn(event);
    }
    $('ul.events.top').append($date);
  };

  getPosts(nextPage, limit);

  getEvents();

}).call(this);

//# sourceMappingURL=home.js.map