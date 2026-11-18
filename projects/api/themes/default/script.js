/*
 * api/themes/default/script.js
 * The console of index.php: sends a request to api.php with fetch()
 * and shows the answer, plus the same request as a curl command.
 */
(function () {
  'use strict';

  var api = document.body.getAttribute('data-api');

  function $(id) {
    return document.getElementById(id);
  }

  // The request described by the form
  function request() {
    var method = $('method').value;
    var headers = { 'Accept': 'application/json' };
    var body = $('body').value.trim();
    if ($('use-token').checked && $('token').value.trim() !== '') {
      headers['Authorization'] = 'Bearer ' + $('token').value.trim();
    }
    var init = { method: method, headers: headers };
    if ((method === 'POST' || method === 'PUT') && body !== '') {
      headers['Content-Type'] = 'application/json';
      init.body = body;
    }
    return { url: $('url').value.trim(), init: init };
  }

  // Puts text in single quotes for the shell
  function quote(text) {
    return "'" + text.replace(/'/g, "'\\''") + "'";
  }

  function showCurl() {
    var r = request();
    var lines = ['curl -X ' + r.init.method + ' ' + quote(r.url)];
    Object.keys(r.init.headers).forEach(function (name) {
      lines.push('  -H ' + quote(name + ': ' + r.init.headers[name]));
    });
    if (r.init.body) {
      lines.push('  -d ' + quote(r.init.body));
    }
    $('curl').textContent = lines.join(' \\\n');
  }

  function showStatus(code, text) {
    var status = $('status');
    status.className = 'badge ' + (code >= 200 && code < 300 ? 'badge-success' : code >= 400 ? 'badge-danger' : 'badge-warning');
    status.textContent = text;
  }

  $('console').addEventListener('submit', function (e) {
    e.preventDefault();
    var r = request();
    var button = this.querySelector('button[type=submit]');
    var started = performance.now();

    if (r.init.body) {
      try {
        JSON.parse(r.init.body);
      } catch (error) {
        showStatus(0, 'JSON ไม่ถูกต้อง');
        $('response').textContent = error.message;
        return;
      }
    }

    button.disabled = true;
    fetch(r.url, r.init)
      .then(function (response) {
        return response.text().then(function (text) {
          var took = Math.round(performance.now() - started);
          showStatus(response.status, 'HTTP ' + response.status + ' · ' + took + ' ms');
          try {
            // Pretty JSON; Thai text that the server sent as \uXXXX becomes readable
            $('response').textContent = JSON.stringify(JSON.parse(text), null, 2);
          } catch (error) {
            $('response').textContent = text;
          }
        });
      })
      .catch(function (error) {
        showStatus(0, 'เรียกไม่สำเร็จ');
        $('response').textContent = error.message;
      })
      .then(function () {
        button.disabled = false;
      });
  });

  // "ลองเรียก" fills the form with one endpoint
  document.querySelectorAll('[data-try]').forEach(function (button) {
    button.addEventListener('click', function () {
      $('method').value = button.getAttribute('data-try');
      $('url').value = api + button.getAttribute('data-path');
      $('body').value = button.getAttribute('data-body') || '';
      $('use-token').checked = $('method').value !== 'GET';
      showCurl();
      $('console').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Reading needs no token, writing does
  $('method').addEventListener('change', function () {
    $('use-token').checked = this.value !== 'GET';
  });

  ['method', 'url', 'use-token', 'token', 'body'].forEach(function (id) {
    $(id).addEventListener('input', showCurl);
    $(id).addEventListener('change', showCurl);
  });
  showCurl();
})();
