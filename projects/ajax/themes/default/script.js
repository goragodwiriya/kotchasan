/*
 * ajax/themes/default/script.js
 * Calls the methods of Index\Ajax\Controller with fetch() and shows the answers.
 * The URL of the controller and the CSRF token come from <body data-...>.
 */
(function () {
  'use strict';

  var endpoint = document.body.getAttribute('data-endpoint');
  var token = document.body.getAttribute('data-token');

  function $(id) {
    return document.getElementById(id);
  }

  /**
   * Calls index.php/index/controller/ajax/{method} and prints the answer
   * (status, time taken and JSON) into the <pre> of the demo.
   * Resolves to {status, data}.
   */
  function call(method, options, output) {
    var started = performance.now();
    var init = options || {};
    init.headers = Object.assign({ 'Accept': 'application/json' }, init.headers || {});

    return fetch(endpoint + method, init)
      .then(function (response) {
        return response.json().then(function (data) {
          var took = Math.round(performance.now() - started);
          output.textContent = 'HTTP ' + response.status + ' · ' + took + ' ms\n' + JSON.stringify(data, null, 2);
          return { status: response.status, data: data };
        });
      })
      .catch(function (error) {
        output.textContent = 'เรียกไม่สำเร็จ: ' + error.message;
        throw error;
      });
  }

  // 1. The server clock

  var timer = null;

  function askTime() {
    $('clock-button').disabled = true;
    call('time', {}, $('clock-output'))
      .then(function (result) {
        $('clock').textContent = result.data.time;
        $('clock-date').textContent = result.data.date + ' (' + result.data.timezone + ')';
      })
      .catch(function () {})
      .then(function () {
        $('clock-button').disabled = false;
      });
  }

  $('clock-button').addEventListener('click', askTime);
  $('clock-auto').addEventListener('change', function () {
    clearInterval(timer);
    if (this.checked) {
      askTime();
      timer = setInterval(askTime, 5000);
    }
  });

  // 2. Province search, sent when typing stops for 250 ms (debounce)

  var wait = null;

  function searchProvinces() {
    var q = $('province-q').value.trim();
    call('provinces?q=' + encodeURIComponent(q), {}, $('province-output'))
      .then(function (result) {
        var list = $('province-list');
        list.textContent = '';
        result.data.items.forEach(function (item) {
          var li = document.createElement('li');
          li.className = 'chip';
          // textContent, never innerHTML: what the server sends is shown as text
          li.textContent = item.th + ' · ' + item.en;
          list.appendChild(li);
        });
        if (result.data.count > result.data.items.length) {
          var more = document.createElement('li');
          more.className = 'chip';
          more.textContent = 'และอีก ' + (result.data.count - result.data.items.length) + ' จังหวัด';
          list.appendChild(more);
        }
      })
      .catch(function () {});
  }

  $('province-q').addEventListener('input', function () {
    clearTimeout(wait);
    wait = setTimeout(searchProvinces, 250);
  });

  // 3. Comments: GET lists them, POST adds one

  function showComments(items) {
    var list = $('comment-list');
    list.textContent = '';
    if (!items.length) {
      var empty = document.createElement('li');
      empty.className = 'muted';
      empty.textContent = 'ยังไม่มีความคิดเห็น';
      list.appendChild(empty);
    }
    items.forEach(function (item) {
      var li = document.createElement('li');
      var name = document.createElement('b');
      var time = document.createElement('span');
      var text = document.createElement('p');
      name.textContent = item.name;
      time.className = 'muted small';
      time.textContent = ' · ' + item.time;
      text.textContent = item.message;
      li.append(name, time, text);
      list.appendChild(li);
    });
  }

  function showErrors(errors) {
    ['name', 'message'].forEach(function (field) {
      var input = $('comment-' + field);
      var error = $('comment-' + field + '-error');
      var message = errors && errors[field];
      error.hidden = !message;
      error.textContent = message || '';
      if (message) {
        input.setAttribute('aria-invalid', 'true');
      } else {
        input.removeAttribute('aria-invalid');
      }
    });
  }

  $('comment-form').addEventListener('submit', function (e) {
    e.preventDefault();
    var button = this.querySelector('button');
    button.disabled = true;
    call('comments', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: $('comment-name').value,
        message: $('comment-message').value,
        token: token
      })
    }, $('comment-output'))
      .then(function (result) {
        showErrors(result.data.errors);
        if (result.status === 201) {
          showComments(result.data.items);
          $('comment-message').value = '';
          $('comment-message').focus();
        }
      })
      .catch(function () {})
      .then(function () {
        button.disabled = false;
      });
  });

  // The comments kept in the session are loaded when the page opens
  call('comments', {}, $('comment-output'))
    .then(function (result) {
      showComments(result.data.items);
    })
    .catch(function () {});
})();
