
(function () {
  function log(...args) {
    console.log(...args); 
  }

  document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.getElementById("genre-wrapper");
    const input = document.getElementById("genreInput");

    if (!wrapper) {
      console.error("[genre.js] Không tìm thấy #genre-wrapper");
      return;
    }
    if (!input) {
      console.error("[genre.js] Không tìm thấy #genreInput");
      return;
    }

    let selected = [];

    wrapper.addEventListener("click", function (e) {
      const btn = e.target.closest(".genre-btn");
      if (!btn) return;

      const value = btn.getAttribute("data-value");
      if (!value) return;

      if (btn.classList.contains("active")) {
        btn.classList.remove("active");
        btn.setAttribute('aria-pressed', 'false');
        selected = selected.filter(g => g !== value);
      } else {
        if (selected.length >= 4) {
          alert("Bạn chỉ được chọn tối đa 4 thể loại.");
          return;
        }
        btn.classList.add("active");
        btn.setAttribute('aria-pressed', 'true');
        selected.push(value);
      }

      input.value = selected.join(", ");
      log("genres:", selected);
    });

    (function preload() {
      try {
        let pre = [];
        if (window.selectedGenres && Array.isArray(window.selectedGenres)) {
          pre = window.selectedGenres;
        } else {
          const val = input.value ? input.value.trim() : "";
          if (val) {
            pre = val.split(",").map(s => s.trim()).filter(Boolean);
          }
        }
        
        pre.forEach(v => {
          const b = wrapper.querySelector(`.genre-btn[data-value="${v}"]`);
          if (b) {
            b.classList.add("active");
            b.setAttribute('aria-pressed','true');
            selected.push(v);
          }
        });
        
        if (selected.length > 0) {
          input.value = selected.join(", ");
        }
      } catch (err) {
        console.warn("[genre.js] preload error", err);
      }
    })();

  });
})();
