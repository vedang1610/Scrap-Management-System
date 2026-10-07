// sms.js - small helpers for the redesigned pages (no jQuery needed)
(function () {
  'use strict';

  // Mobile menu sheet
  var body = document.body;
  document.querySelectorAll('[data-sheet-open]').forEach(function (btn) {
    btn.addEventListener('click', function () { body.classList.add('sheet-open'); });
  });
  document.querySelectorAll('[data-sheet-close]').forEach(function (el) {
    el.addEventListener('click', function () { body.classList.remove('sheet-open'); });
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') body.classList.remove('sheet-open');
  });

  // Show / hide password
  document.querySelectorAll('.toggle-pass').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = btn.parentNode.querySelector('input');
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.innerHTML = show ? '<i class="fa-regular fa-eye-slash"></i>' : '<i class="fa-regular fa-eye"></i>';
      btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
  });

  // Image picker with preview
  document.querySelectorAll('.dropzone').forEach(function (zone) {
    var input = zone.querySelector('input[type=file]');
    var thumb = zone.querySelector('.dz-thumb');
    var title = zone.querySelector('.dz-text strong');
    var note = zone.querySelector('.dz-text span');
    function show(file) {
      if (!file) return;
      title.textContent = file.name;
      note.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB' + (file.size > 5242880 ? ' - too big, max 5 MB' : '');
      var reader = new FileReader();
      reader.onload = function (e) { thumb.innerHTML = '<img alt="" src="' + e.target.result + '">'; };
      reader.readAsDataURL(file);
      zone.closest('.field').classList.toggle('is-invalid', file.size > 5242880);
    }
    input.addEventListener('change', function () { show(input.files[0]); });
    ['dragenter', 'dragover'].forEach(function (ev) {
      zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('is-drag'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
      zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.remove('is-drag'); });
    });
    zone.addEventListener('drop', function (e) {
      if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; show(input.files[0]); }
    });
  });

  // Form validation: mark bad fields, check matching passwords, show a loading button
  document.querySelectorAll('form[data-validate]').forEach(function (form) {
    var pass = form.querySelector('[data-pass]');
    var confirm = form.querySelector('[data-confirm]');
    function checkMatch() {
      if (!pass || !confirm) return;
      confirm.setCustomValidity(confirm.value && confirm.value !== pass.value ? 'Passwords do not match' : '');
    }
    if (pass && confirm) {
      pass.addEventListener('input', checkMatch);
      confirm.addEventListener('input', checkMatch);
    }
    form.querySelectorAll('input, select, textarea').forEach(function (el) {
      el.addEventListener('invalid', function () {
        var f = el.closest('.field'); if (f) f.classList.add('is-invalid');
      });
      el.addEventListener('input', function () {
        var f = el.closest('.field'); if (f && el.checkValidity()) f.classList.remove('is-invalid');
      });
    });
    form.addEventListener('submit', function (e) {
      checkMatch();
      if (!form.checkValidity()) {
        e.preventDefault();
        var bad = form.querySelector(':invalid');
        if (bad) { bad.focus(); bad.scrollIntoView({ block: 'center', behavior: 'smooth' }); }
        return;
      }
      var btn = form.querySelector('button[type=submit]');
      if (btn) { btn.setAttribute('data-label', btn.innerHTML); btn.classList.add('is-loading'); btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Please wait'; }
    });
  });

  // Quantity steppers: [-] [input] [+]
  document.querySelectorAll('.stepper').forEach(function (st) {
    var input = st.querySelector('input');
    var min = Number(input.min) || 1;
    var max = Number(input.max) || 50;
    function set(v) {
      v = Math.max(min, Math.min(max, Math.round(Number(v) || min)));
      if (String(v) !== input.value) {
        input.value = v;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    }
    st.querySelector('[data-step="-1"]').addEventListener('click', function () { set(Number(input.value) - 1); });
    st.querySelector('[data-step="1"]').addEventListener('click', function () { set(Number(input.value) + 1); });
    input.addEventListener('blur', function () { set(input.value); });
  });

  // Product gallery: tap a thumbnail to show it big
  document.querySelectorAll('.pd-gallery').forEach(function (g) {
    var main = g.querySelector('.pd-main');
    var img = main.querySelector('img');
    g.querySelectorAll('.pd-thumbs button').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var src = btn.getAttribute('data-src');
        img.src = src; main.href = src;
        g.querySelectorAll('.pd-thumbs button').forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
      });
    });
  });

  // Lightbox for links marked data-lightbox
  var boxLinks = document.querySelectorAll('[data-lightbox]');
  if (boxLinks.length) {
    var box = document.createElement('div');
    box.className = 'lightbox';
    box.innerHTML = '<img alt=""><button type="button" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>';
    document.body.appendChild(box);
    var boxImg = box.querySelector('img');
    box.addEventListener('click', function (e) { if (e.target !== boxImg) box.classList.remove('is-open'); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') box.classList.remove('is-open'); });
    boxLinks.forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        boxImg.src = a.getAttribute('href');
        box.classList.add('is-open');
      });
    });
  }

  // Textarea character counters
  document.querySelectorAll('[data-counter]').forEach(function (ta) {
    var out = document.getElementById(ta.getAttribute('data-counter'));
    function upd() { out.textContent = ta.value.length + ' / ' + ta.maxLength; }
    ta.addEventListener('input', upd); upd();
  });

  // Coming back with the browser Back button: reset any "Please wait" buttons
  window.addEventListener('pageshow', function (e) {
    if (!e.persisted) return;
    document.querySelectorAll('button.is-loading').forEach(function (btn) {
      btn.classList.remove('is-loading');
      btn.innerHTML = btn.getAttribute('data-label') || 'Submit';
    });
  });
})();

// Demo notice: tell visitors this is a student project, before they use the site (once per visit)
(function () {
  var body = document.body;
  if (body.classList.contains('admin') && !body.classList.contains('user-area')) return; // not in the admin panel
  var KEY = 'smsDemoNoticeSeen';
  try { if (sessionStorage.getItem(KEY)) return; } catch (e) {}
  var box = document.createElement('div');
  box.className = 'demo-notice';
  box.setAttribute('role', 'dialog');
  box.setAttribute('aria-modal', 'true');
  box.setAttribute('aria-labelledby', 'dnTitle');
  box.innerHTML =
    '<div class="dn-card">' +
      '<div class="dn-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>' +
      '<h2 id="dnTitle">Demo website only</h2>' +
      '<p>This is a <b>diploma student project</b> made for learning. It is not a real shop.</p>' +
      '<ul>' +
        '<li><i class="fa-solid fa-ban"></i><span>Do not place real orders. Nothing will be delivered.</span></li>' +
        '<li><i class="fa-solid fa-ban"></i><span>Do not make any real payment or enter real card details.</span></li>' +
        '<li><i class="fa-solid fa-ban"></i><span>Do not upload real ID documents.</span></li>' +
      '</ul>' +
      '<p class="dn-gu">આ ફક્ત ડેમો વેબસાઇટ છે. અહીં કોઈ સાચો ઓર્ડર કે પેમેન્ટ કરશો નહીં.</p>' +
      '<button type="button" class="btn btn-primary"><i class="fa-solid fa-check"></i> I understand / સમજી ગયો</button>' +
    '</div>';
  body.appendChild(box);
  body.classList.add('dn-open');
  var btn = box.querySelector('button');
  setTimeout(function () { btn.focus(); }, 50);
  btn.addEventListener('click', function () {
    try { sessionStorage.setItem(KEY, '1'); } catch (e) {}
    box.remove();
    body.classList.remove('dn-open');
  });
})();
