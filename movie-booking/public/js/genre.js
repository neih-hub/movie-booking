document.addEventListener('DOMContentLoaded', () => {

  const btns = document.querySelectorAll('.genre-btn');
  const input = document.getElementById('genreInput');

  let selected = window.selectedGenres || [];

  // Set active khi edit
  btns.forEach(btn => {
    if (selected.includes(btn.dataset.value)) {
      btn.classList.add('active');
    }
  });

  updateInput();

  btns.forEach(btn => {
    btn.addEventListener('click', () => {

      if (!btn.classList.contains('active') && selected.length >= 4) {
        alert("Bạn chỉ được chọn tối đa 4 thể loại!");
        return;
      }

      btn.classList.toggle('active');

      selected = [...document.querySelectorAll('.genre-btn.active')]
        .map(b => b.dataset.value);

      updateInput();
    });
  });

  function updateInput() {
    input.value = JSON.stringify(selected);
  }

});
