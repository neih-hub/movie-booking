
document.addEventListener('DOMContentLoaded', function () {
    const movieSelect = document.getElementById('movie');
    const cinemaSelect = document.getElementById('cinema');
    const roomSelect = document.getElementById('room');
    const dateSelect = document.getElementById('date_start');
    const showtimeGrid = document.getElementById('showtimeGrid');
    const btnContinue = document.getElementById('btnContinue');

    let selectedShowtimeId = null;

    cinemaSelect.addEventListener('change', loadRooms);
    roomSelect.addEventListener('change', function () {
        loadDates();
        clearShowtimes();
    });
    dateSelect.addEventListener('change', loadShowtimes);
    btnContinue.addEventListener('click', proceedToBooking);

    movieSelect.addEventListener('change', function () {
        if (roomSelect.value) {
            loadDates();
        }
        clearShowtimes();
    });

    async function loadRooms() {
        const cinemaId = cinemaSelect.value;

        roomSelect.innerHTML = '<option value="">-- Chọn phòng --</option>';
        dateSelect.innerHTML = '<option value="">-- Chọn ngày --</option>';
        clearShowtimes();

        if (!cinemaId) return;

        try {
            const response = await fetch(`/api/rooms?cinema_id=${cinemaId}`);
            const rooms = await response.json();

            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = room.name;
                roomSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading rooms:', error);
        }
    }

    async function loadDates() {
        const movieId = movieSelect.value;
        const roomId = roomSelect.value;

        dateSelect.innerHTML = '<option value="">-- Chọn ngày --</option>';
        clearShowtimes();

        if (!movieId || !roomId) return;

        try {
            const response = await fetch(`/api/dates?movie_id=${movieId}&room_id=${roomId}`);
            const dates = await response.json();

            if (dates.length === 0) {
                dateSelect.innerHTML = '<option value="">Không có suất chiếu</option>';
                return;
            }

            dates.forEach(date => {
                const option = document.createElement('option');
                option.value = date;
                option.textContent = formatDate(date);
                dateSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading dates:', error);
        }
    }

    async function loadShowtimes() {
        const movieId = movieSelect.value;
        const roomId = roomSelect.value;
        const dateStart = dateSelect.value;

        clearShowtimes();

        if (!movieId || !roomId || !dateStart) return;

        try {
            const response = await fetch(`/api/showtimes?movie_id=${movieId}&room_id=${roomId}&date_start=${dateStart}`);
            const showtimes = await response.json();

            if (showtimes.length === 0) {
                showtimeGrid.innerHTML = `
                    <div class="empty-state col-12">
                        <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                        <p class="mt-2">Không có suất chiếu nào cho ngày này</p>
                    </div>
                `;
                return;
            }

            showtimeGrid.innerHTML = '';
            showtimes.forEach(showtime => {
                const showtimeBtn = document.createElement('button');
                showtimeBtn.type = 'button';
                showtimeBtn.className = 'showtime-btn';
                showtimeBtn.dataset.showtimeId = showtime.id;
                showtimeBtn.innerHTML = `
                    <div class="showtime-time">${showtime.start_time}</div>
                    <div class="showtime-price">${formatPrice(showtime.price)} đ</div>
                `;

                showtimeBtn.addEventListener('click', () => selectShowtime(showtime.id, showtimeBtn));
                showtimeGrid.appendChild(showtimeBtn);
            });
        } catch (error) {
            console.error('Error loading showtimes:', error);
            showtimeGrid.innerHTML = `
                <div class="empty-state col-12 text-danger">
                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                    <p class="mt-2">Có lỗi xảy ra khi tải suất chiếu</p>
                </div>
            `;
        }
    }

    function selectShowtime(showtimeId, buttonElement) {
        document.querySelectorAll('.showtime-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        buttonElement.classList.add('selected');
        selectedShowtimeId = showtimeId;

        btnContinue.disabled = false;
    }

    function clearShowtimes() {
        showtimeGrid.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-film" style="font-size: 3rem;"></i>
                <p class="mt-2">Vui lòng chọn phim, rạp, phòng và ngày để xem suất chiếu</p>
            </div>
        `;
        selectedShowtimeId = null;
        btnContinue.disabled = true;
    }

    function proceedToBooking() {
        if (!selectedShowtimeId) {
            alert('Vui lòng chọn suất chiếu!');
            return;
        }

        window.location.href = `/booking/${selectedShowtimeId}`;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        const dayOfWeek = days[date.getDay()];
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        return `${dayOfWeek}, ${day}/${month}/${year}`;
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
});
