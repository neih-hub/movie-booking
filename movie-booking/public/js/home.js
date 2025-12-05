document.addEventListener("DOMContentLoaded", function () {

  let movie = document.querySelector("#movie");
  let cinema = document.querySelector("#cinema");
  let date = document.querySelector("#date_start");
  let showtime = document.querySelector("#showtime");

  // =====================
  // RESET FUNCTIONS
  // =====================

  function resetDates() {
    date.innerHTML = `<option value="">-- Chọn ngày --</option>`;
  }

  function resetShowtimes() {
    showtime.innerHTML = `<option value="">-- Chọn suất chiếu --</option>`;
  }

  // =====================
  // LOAD NGÀY CHIẾU
  // =====================

  function loadDates() {
    resetDates();
    resetShowtimes();

    if (!movie.value || !cinema.value) return;

    console.log('Loading dates for:', { movie_id: movie.value, cinema_id: cinema.value });

    fetch(`/get-dates?movie_id=${movie.value}&cinema_id=${cinema.value}`)
      .then(res => res.json())
      .then(dates => {
        console.log('Dates received:', dates);
        
        if (dates && dates.length > 0) {
          dates.forEach(d => {
            date.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else {
          console.warn('No dates found');
          date.innerHTML = `<option value="">Không có ngày chiếu</option>`;
        }
      })
      .catch(err => {
        console.error('Error loading dates:', err);
        date.innerHTML = `<option value="">Lỗi tải dữ liệu</option>`;
      });
  }

  // =====================
  // LOAD SUẤT CHIẾU
  // =====================

  function loadShowtimes() {
    resetShowtimes();

    if (!movie.value || !cinema.value || !date.value) return;

    console.log('Loading showtimes for:', { 
      movie_id: movie.value, 
      cinema_id: cinema.value, 
      date_start: date.value 
    });

    fetch(`/search-showtime?movie_id=${movie.value}&cinema_id=${cinema.value}&date_start=${date.value}`)
      .then(res => res.json())
      .then(showtimes => {
        console.log('Showtimes received:', showtimes);
        
        if (showtimes && showtimes.length > 0) {
          showtimes.forEach(st => {
            showtime.innerHTML += `
              <option value="${st.id}">
                ${st.start_time} - Phòng ${st.room.name}
              </option>
            `;
          });
        } else {
          console.warn('No showtimes found');
          showtime.innerHTML = `<option value="">Không có suất chiếu</option>`;
        }
      })
      .catch(err => {
        console.error('Error loading showtimes:', err);
        showtime.innerHTML = `<option value="">Lỗi tải dữ liệu</option>`;
      });
  }

  // =====================
  // EVENT LISTENERS
  // =====================

  movie.addEventListener("change", loadDates);
  cinema.addEventListener("change", loadDates);

  date.addEventListener("change", loadShowtimes);

  // =====================
  // MUA VÉ
  // =====================

  document.querySelector("#btnBuy").onclick = function () {
    if (!showtime.value) {
      alert("Vui lòng chọn suất chiếu!");
      return;
    }
    window.location.href = "/showtime/" + showtime.value;
  };
});
