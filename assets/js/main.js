(function () {
  "use strict";

  // WhatsApp number used by the quick-request form and floating button.
  // Overridable from the CMS via window.CMS_WHATSAPP_NUMBER (set inline in index.php).
  var WHATSAPP_NUMBER = window.CMS_WHATSAPP_NUMBER || "6281321928034";

  document.addEventListener("DOMContentLoaded", function () {
    initHeaderScroll();
    initMobileNav();
    initRevealAnimations();
    initStatCounters();
    initSearchForm();
    initWhatsAppFloat();
    document.getElementById("year").textContent = new Date().getFullYear();
  });

  // Sticky header background on scroll
  function initHeaderScroll() {
    var header = document.getElementById("siteHeader");
    if (!header) return;
    function update() {
      if (window.scrollY > 40) {
        header.classList.add("scrolled");
      } else {
        header.classList.remove("scrolled");
      }
    }
    update();
    window.addEventListener("scroll", update, { passive: true });
  }

  // Mobile nav toggle
  function initMobileNav() {
    var toggle = document.getElementById("navToggle");
    var nav = document.getElementById("mainNav");
    if (!toggle || !nav) return;
    toggle.addEventListener("click", function () {
      var isOpen = nav.classList.toggle("open");
      toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
      toggle.classList.toggle("is-active", isOpen);
    });
    nav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        nav.classList.remove("open");
        toggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  // Scroll-reveal for elements with .reveal
  function initRevealAnimations() {
    var items = document.querySelectorAll(".reveal");
    if (!items.length) return;

    if (!("IntersectionObserver" in window)) {
      items.forEach(function (el) { el.classList.add("is-visible"); });
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry, index) {
          if (entry.isIntersecting) {
            var el = entry.target;
            var delay = (index % 6) * 60;
            setTimeout(function () { el.classList.add("is-visible"); }, delay);
            observer.unobserve(el);
          }
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -40px 0px" }
    );

    items.forEach(function (el) { observer.observe(el); });
  }

  // Animated number counters in the stats strip
  function initStatCounters() {
    var counters = document.querySelectorAll(".stat-num");
    if (!counters.length) return;

    var animated = false;

    function run() {
      if (animated) return;
      animated = true;
      counters.forEach(function (el) {
        var target = parseInt(el.getAttribute("data-count"), 10) || 0;
        var duration = 1400;
        var start = null;

        function step(timestamp) {
          if (start === null) start = timestamp;
          var progress = Math.min((timestamp - start) / duration, 1);
          var eased = 1 - Math.pow(1 - progress, 3);
          el.textContent = Math.floor(eased * target);
          if (progress < 1) requestAnimationFrame(step);
          else el.textContent = target;
        }
        requestAnimationFrame(step);
      });
    }

    if (!("IntersectionObserver" in window)) {
      run();
      return;
    }

    var strip = document.querySelector(".stats-strip");
    if (!strip) return;
    var obs = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            run();
            obs.disconnect();
          }
        });
      },
      { threshold: 0.4 }
    );
    obs.observe(strip);
  }

  // Quick search form -> builds a WhatsApp message with the search details
  function initSearchForm() {
    var form = document.getElementById("searchForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var from = form.from.value.trim();
      var to = form.to.value.trim();
      var depart = form.depart.value;
      var pax = form.pax.value;

      var departLabel = depart
        ? new Date(depart + "T00:00:00").toLocaleDateString("id-ID", {
            day: "numeric", month: "long", year: "numeric"
          })
        : "-";

      var message =
        "Halo PT Rama Setya Mandiri, saya ingin mengajukan permintaan penerbangan:\n" +
        "Dari: " + from + "\n" +
        "Ke: " + to + "\n" +
        "Tanggal dibutuhkan: " + departLabel + "\n" +
        "Jenis muatan: " + pax;

      var url = "https://wa.me/" + WHATSAPP_NUMBER + "?text=" + encodeURIComponent(message);
      window.open(url, "_blank", "noopener");
    });
  }

  // Set the floating WhatsApp button target
  function initWhatsAppFloat() {
    var btn = document.getElementById("waFloat");
    if (!btn) return;
    var message = "Halo PT Rama Setya Mandiri, saya ingin bertanya tentang layanan transportasi udara / Ground Handling.";
    btn.href = "https://wa.me/" + WHATSAPP_NUMBER + "?text=" + encodeURIComponent(message);
  }
})();
