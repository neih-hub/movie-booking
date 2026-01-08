document.addEventListener("DOMContentLoaded", function () {

  let movie = document.querySelector("#movie");
  let cinema = document.querySelector("#cinema");
  let room = document.querySelector("#room");
  let date = document.querySelector("#date_start");
  let showtime = document.querySelector("#showtime");

  function resetCinemas() {
    cinema.innerHTML = `<option value="">-- Chọn rạp --</option>`;
  }

  function resetRooms() {
    room.innerHTML = `<option value="">-- Chọn phòng --</option>`;
  }

  function resetDates() {
    date.innerHTML = `<option value="">-- Chọn ngày --</option>`;
  }

  function resetShowtimes() {
    showtime.innerHTML = `<option value="">-- Chọn suất --</option>`;
  }

  function loadCinemasByMovie() {
    resetCinemas();
    resetRooms();
    resetDates();
    resetShowtimes();

    if (!movie.value) return;

    console.log('Loading cinemas for movie:', movie.value);

    fetch(`${window.baseUrl}/api/cinemas-by-movie?movie_id=${movie.value}`)
      .then(res => res.json())
      .then(cinemas => {
        console.log('Cinemas received:', cinemas);
        
        if (cinemas && cinemas.length > 0) {
          cinemas.forEach(c => {
            cinema.innerHTML += `<option value="${c.id}">${c.name}</option>`;
          });
        } else {
          console.warn('No cinemas found for this movie');
          cinema.innerHTML = `<option value="">Phim chưa có lịch chiếu</option>`;
        }
      })
      .catch(err => {
        console.error('Error loading cinemas:', err);
        cinema.innerHTML = `<option value="">Lỗi tải dữ liệu</option>`;
      });
  }


  function loadRooms() {
    resetRooms();
    resetDates();
    resetShowtimes();

    if (!cinema.value || !movie.value) return;

    console.log('Loading rooms for cinema:', cinema.value, 'and movie:', movie.value);

    fetch(`${window.baseUrl}/api/rooms?cinema_id=${cinema.value}&movie_id=${movie.value}`)
      .then(res => res.json())
      .then(rooms => {
        console.log('Rooms received:', rooms);
        
        if (rooms && rooms.length > 0) {
          rooms.forEach(r => {
            room.innerHTML += `<option value="${r.id}">${r.name}</option>`;
          });
        } else {
          console.warn('No rooms found');
          room.innerHTML = `<option value="">Không có phòng chiếu phim này</option>`;
        }
      })
      .catch(err => {
        console.error('Error loading rooms:', err);
        room.innerHTML = `<option value="">Lỗi tải dữ liệu</option>`;
      });
  }


  function loadDates() {
    resetDates();
    resetShowtimes();

    if (!movie.value || !room.value) return;

    console.log('Loading dates for:', { movie_id: movie.value, room_id: room.value });

    fetch(`${window.baseUrl}/api/dates?movie_id=${movie.value}&room_id=${room.value}`)
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

  function loadShowtimes() {
    resetShowtimes();

    if (!movie.value || !room.value || !date.value) return;

    console.log('Loading showtimes for:', { 
      movie_id: movie.value, 
      room_id: room.value, 
      date_start: date.value 
    });

    fetch(`${window.baseUrl}/api/showtimes?movie_id=${movie.value}&room_id=${room.value}&date_start=${date.value}`)
      .then(res => res.json())
      .then(showtimes => {
        console.log('Showtimes received:', showtimes);
        
        if (showtimes && showtimes.length > 0) {
          showtimes.forEach(st => {
            showtime.innerHTML += `
              <option value="${st.id}">
                ${st.start_time} - ${st.price.toLocaleString('vi-VN')}đ
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

  movie.addEventListener("change", loadCinemasByMovie);

  cinema.addEventListener("change", loadRooms);
  
  room.addEventListener("change", loadDates);

  date.addEventListener("change", loadShowtimes);


  document.querySelector("#btnBuy").onclick = function () {
    if (!showtime.value) {
      alert("Vui lòng chọn suất chiếu!");
      return;
    }
    window.location.href = `${window.baseUrl}/booking/${showtime.value}`;
  };
});
