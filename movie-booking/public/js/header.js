document.addEventListener("DOMContentLoaded", function () {

  const icon = document.getElementById("openSearch");
  const input = document.getElementById("searchInput");
  const results = document.getElementById("searchResults");


  icon.addEventListener("click", function () {

    input.classList.toggle("active");

    if (input.classList.contains("active")) {
      input.classList.remove("d-none");
      results.classList.add("d-none"); // ẩn kết quả khi mới mở
      input.focus();
    } else {
      input.classList.add("d-none");
      results.classList.add("d-none");
      results.innerHTML = "";
    }
  });

  input.addEventListener("keyup", function () {

    let keyword = this.value.trim();

    if (keyword.length < 1) {
      results.classList.add("d-none");
      results.innerHTML = "";
      return;
    }

    fetch(`/search-advanced?keyword=${keyword}`)
      .then(res => res.json())
      .then(data => {

        let html = "";

        if (data.length === 0) {
          html = `<div class="list-group-item">Không tìm thấy kết quả</div>`;
        } else {
          data.forEach(item => {
            html += `
                            <a href="/movie/${item.id}" class="list-group-item list-group-item-action">
                                <strong>${item.title}</strong><br>
                                <small>${item.category ?? ''} – ${item.director ?? ''}</small>
                            </a>
                        `;
          });
        }

        results.innerHTML = html;
        results.classList.remove("d-none");
      });

  });

});
