// admin.js - behaviour for the redesigned admin panel
(function () {
  'use strict';
  var body = document.body;

  // Slide-in menu (phones)
  document.querySelectorAll('[data-side-open]').forEach(function (b) {
    b.addEventListener('click', function () { body.classList.add('side-open'); });
  });
  document.querySelectorAll('[data-side-close]').forEach(function (b) {
    b.addEventListener('click', function () { body.classList.remove('side-open'); });
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') body.classList.remove('side-open'); });

  // Toast message
  var toastTimer;
  window.adminToast = function (message, type) {
    var t = document.getElementById('toast');
    if (!t) return;
    type = type || 'success';
    var icon = type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check';
    t.className = 'toast ' + type;
    t.innerHTML = '<i class="fa-solid ' + icon + '"></i><span></span>';
    t.querySelector('span').textContent = message;
    requestAnimationFrame(function () { t.classList.add('show'); });
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { t.classList.remove('show'); }, type === 'error' ? 5000 : 3000);
  };

  // Confirm before dangerous actions: <form data-confirm="Delete this?">
  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (form.dataset.confirmed) return;
      e.preventDefault();
      var text = form.getAttribute('data-confirm');
      var done = function (ok) { if (ok) { form.dataset.confirmed = '1'; form.submit(); } };
      if (window.swal) {
        swal({ title: text, text: form.getAttribute('data-confirm-text') || '', icon: 'warning',
          buttons: ['Cancel', form.getAttribute('data-confirm-btn') || 'Yes, continue'], dangerMode: true }).then(done);
      } else {
        done(window.confirm(text));
      }
    });
  });

  // Live search + filter chips for lists: [data-list] holds items with data-search / data-filter
  var lists = {};
  function apply(id) {
    var st = lists[id];
    var q = (st.q || '').toLowerCase().trim();
    var shown = 0;
    st.items.forEach(function (it) {
      var okQ = !q || (it.getAttribute('data-search') || it.textContent).toLowerCase().indexOf(q) !== -1;
      var okF = !st.f || st.f === 'all' || (' ' + (it.getAttribute('data-filter') || '') + ' ').indexOf(' ' + st.f + ' ') !== -1;
      var show = okQ && okF;
      it.style.display = show ? '' : 'none';
      if (show) shown++;
    });
    if (st.empty) st.empty.style.display = (st.items.length && !shown) ? 'block' : 'none';
  }
  document.querySelectorAll('[data-list]').forEach(function (list) {
    var id = list.getAttribute('data-list');
    lists[id] = {
      items: Array.prototype.slice.call(list.querySelectorAll('[data-search]')),
      empty: document.querySelector('[data-list-empty="' + id + '"]'),
      q: '', f: ''
    };
  });
  document.querySelectorAll('[data-list-search]').forEach(function (input) {
    var id = input.getAttribute('data-list-search');
    if (!lists[id]) return;
    input.addEventListener('input', function () { lists[id].q = input.value; apply(id); });
  });
  document.querySelectorAll('[data-list-filter]').forEach(function (bar) {
    var id = bar.getAttribute('data-list-filter');
    if (!lists[id]) return;
    bar.querySelectorAll('[data-f]').forEach(function (chip) {
      chip.addEventListener('click', function (e) {
        e.preventDefault();
        bar.querySelectorAll('[data-f]').forEach(function (c) { c.classList.remove('is-active'); });
        chip.classList.add('is-active');
        lists[id].f = chip.getAttribute('data-f');
        apply(id);
      });
    });
    var start = bar.querySelector('[data-f].is-active');
    if (start) { lists[id].f = start.getAttribute('data-f'); apply(id); }
  });
})();
