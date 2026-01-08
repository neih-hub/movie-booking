
document.addEventListener('DOMContentLoaded', function () {
    const cinemas = window.cinemasData || [];
    const cinemaSelect = document.getElementById('cinema_select');
    const roomsContainer = document.getElementById('rooms_container');
    const roomsGrid = document.getElementById('rooms_grid');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    if (!cinemaSelect) return;

    cinemaSelect.addEventListener('change', function () {
        const cinemaId = this.value;

        if (!cinemaId) {
            roomsContainer.style.display = 'none';
            return;
        }

        const cinema = cinemas.find(c => c.id == cinemaId);

        if (cinema && cinema.rooms && cinema.rooms.length > 0) {
            roomsGrid.innerHTML = '';

            cinema.rooms.forEach(room => {
                const roomCard = createRoomCard(room);
                roomsGrid.appendChild(roomCard);
            });

            roomsContainer.style.display = 'block';
        } else {
            roomsGrid.innerHTML = `
                <div class="empty-rooms" style="grid-column: 1 / -1;">
                    <i class="bi bi-door-closed"></i>
                    <p>Rạp này chưa có phòng chiếu nào</p>
                </div>
            `;
            roomsContainer.style.display = 'block';
        }
    });

    function createRoomCard(room) {
        const roomCard = document.createElement('div');
        roomCard.className = 'room-card';

        roomCard.innerHTML = `
            <div class="room-card-content">
                <div class="room-name">${room.name}</div>
                <div class="room-title">Phòng chiếu ${room.name}</div>
                
                <div class="room-stats">
                    <div class="room-stat">
                        <span class="room-stat-value">${room.total_seats || 0}</span>
                        <span class="room-stat-label">Tổng ghế</span>
                    </div>
                    <div class="room-stat">
                        <span class="room-stat-value">
                            <i class="bi bi-grid-3x3"></i>
                        </span>
                        <span class="room-stat-label">Sơ đồ ghế</span>
                    </div>
                </div>
                
                <div class="room-actions">
                    <a href="/admin/rooms/edit/${room.id}" class="btn btn-warning edit-btn" onclick="event.stopPropagation()">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <button class="btn btn-danger delete-btn" data-room-id="${room.id}" onclick="event.stopPropagation()">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </div>
            </div>
        `;

        const deleteBtn = roomCard.querySelector('.delete-btn');
        deleteBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (confirm(`Bạn có chắc muốn xóa phòng ${room.name}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/rooms/delete/${room.id}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });

        roomCard.addEventListener('click', function () {
            window.location.href = `/admin/rooms/${room.id}/seats-honeycomb`;
        });

        return roomCard;
    }
});
