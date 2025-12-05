// public/js/genre.js
(function () {
  function log(...args) {
    // console.log(...args); // bật khi debug
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

    // hiện trạng selected list
    let selected = [];

    // delegate event
    wrapper.addEventListener("click", function (e) {
      const btn = e.target.closest(".genre-btn");
      if (!btn) return;

      const value = btn.getAttribute("data-value");
      if (!value) return;

      // nếu đang active -> bỏ chọn
      if (btn.classList.contains("active")) {
        btn.classList.remove("active");
        btn.setAttribute('aria-pressed', 'false');
        selected = selected.filter(g => g !== value);
      } else {
        // giới hạn 4
        if (selected.length >= 4) {
          // bạn có thể thay alert bằng tooltip nhỏ
          alert("Bạn chỉ được chọn tối đa 4 thể loại.");
          return;
        }
        btn.classList.add("active");
        btn.setAttribute('aria-pressed', 'true');
        selected.push(value);
      }

      // cập nhật input ẩn
      input.value = selected.join(", ");
      log("genres:", selected);
    });

    // nếu muốn preload selected từ input (edit page), parse và active button tương ứng
    (function preload() {
      try {
        // Ưu tiên lấy từ window.selectedGenres (được set từ blade template)
        let pre = [];
        if (window.selectedGenres && Array.isArray(window.selectedGenres)) {
          pre = window.selectedGenres;
        } else {
          // Fallback: parse từ input value
          const val = input.value ? input.value.trim() : "";
          if (val) {
            pre = val.split(",").map(s => s.trim()).filter(Boolean);
          }
        }
        
        // Active các button tương ứng
        pre.forEach(v => {
          const b = wrapper.querySelector(`.genre-btn[data-value="${v}"]`);
          if (b) {
            b.classList.add("active");
            b.setAttribute('aria-pressed','true');
            selected.push(v);
          }
        });
        
        // Cập nhật input với giá trị đã chọn
        if (selected.length > 0) {
          input.value = selected.join(", ");
        }
      } catch (err) {
        console.warn("[genre.js] preload error", err);
      }
    })();

  });
})();
