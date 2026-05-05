(function() {
  function parseJSONAttribute(node, attrName) {
    try {
      return JSON.parse(node.getAttribute(attrName) || '{}');
    } catch (e) {
      return {};
    }
  }

  function log(enabled) {
    if (!enabled || !window.console) {
      return;
    }

    var args = Array.prototype.slice.call(arguments, 1);
    args.unshift('[JPN]');
    window.console.log.apply(window.console, args);
  }

  function applyStyleVariables(container, config) {
    container.style.left = '';
    container.style.right = '';

    if (config.position === 'right_bottom') {
      container.style.right = (config.side_offset || 20) + 'px';
    } else {
      container.style.left = (config.side_offset || 20) + 'px';
    }

    container.style.bottom = (config.bottom_offset || 20) + 'px';
  }

  function createCard(item, config) {
    var card = document.createElement('article');
    card.className = 'jpn-card';

    if (config.motion === 'slide') {
      card.classList.add('jpn-motion-slide');
    } else if (config.motion === 'fade') {
      card.classList.add('jpn-motion-fade');
    }

    if (config.click_anywhere) {
      card.classList.add('jpn-clickable');
      card.addEventListener('click', function() {
        window.location.href = item.product_href;
      });
    }

    card.style.backgroundColor = config.styles.background;
    card.style.border = '1px solid ' + config.styles.border;
    card.style.color = config.styles.text;

    var media = document.createElement('a');
    media.href = item.product_href;

    var image = document.createElement('img');
    image.src = item.image;
    image.alt = '';

    media.appendChild(image);

    var body = document.createElement('div');
    body.className = 'jpn-copy';
    body.innerHTML = item.message;
    body.style.color = config.styles.text;

    var links = body.querySelectorAll('a');
    links.forEach(function(link) {
      link.style.color = config.styles.link;
    });

    if (item.show_time_ago && item.time_ago) {
      var time = document.createElement('div');
      time.className = 'jpn-time';
      time.textContent = item.time_ago;
      body.appendChild(time);
    }

    card.appendChild(media);
    card.appendChild(body);

    return card;
  }

  function runQueue(root, config, notifications) {
    if (!notifications.length) {
      return;
    }

    var container = document.createElement('section');
    container.className = 'jpn-container ' + config.position;
    applyStyleVariables(container, config);
    document.body.appendChild(container);

    var index = 0;

    function showNext() {
      var item = notifications[index % notifications.length];
      var card = createCard(item, config);
      container.appendChild(card);

      log(config.debug, 'Showing notification', item);

      window.setTimeout(function() {
        card.remove();
      }, config.display_time || 4500);

      index += 1;
    }

    window.setTimeout(function() {
      showNext();
      window.setInterval(showNext, config.delay || 7000);
    }, config.start_delay || 0);
  }

  document.addEventListener('DOMContentLoaded', function() {
    var root = document.getElementById('jpn-root');

    if (!root) {
      return;
    }

    var config = parseJSONAttribute(root, 'data-config');
    var notifications = parseJSONAttribute(root, 'data-notifications');

    if (!Array.isArray(notifications)) {
      notifications = [];
    }

    log(config.debug, 'Config', config);
    log(config.debug, 'Notifications count', notifications.length);

    runQueue(root, config, notifications);
  });
})();
