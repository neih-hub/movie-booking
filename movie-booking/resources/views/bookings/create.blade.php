<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt Vé - {{ $showtime->movie->title }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
</head>
<body>
    <!-- Header -->
    @include('layouts.header')

    <div class="booking-container">
        <h1 class="text-center mb-4" style="font-weight: 700; color: #1e293b;">Đặt Vé Xem Phim</h1>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-progress" id="stepProgress" style="width: 0%;"></div>
            
            <div class="step-item active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Xác nhận</div>
            </div>
            
            <div class="step-item" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Chọn ghế</div>
            </div>
            
            <div class="step-item" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Thức ăn</div>
            </div>
            
            <div class="step-item" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Thanh toán</div>
            </div>
        </div>

        <div class="booking-layout">
            <!-- Main Content -->
            <div class="booking-main">
                <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                    <div id="seatHiddenInputs"></div>
<div id="foodHiddenInputs"></div>
                    <!-- xác nhận -->
                    <div class="step-content active" data-step="1">
                        <h2 class="step-title">Xác nhận thông tin</h2>
                        
                        <div class="movie-info">
                            <img src="{{ asset($showtime->movie->poster) }}" 
                                alt="{{ $showtime->movie->title }}" 
                               class="movie-poster">

                            <div class="movie-details">
                                <h3>{{ $showtime->movie->title }}</h3>
                                
                                <div class="info-row">
                                    <i class="bi bi-building"></i>
                                    <strong>Rạp:</strong>
                                    <span>{{ $showtime->room->cinema->name }}</span>
                                </div>
                                
                                <div class="info-row">
                                    <i class="bi bi-door-open"></i>
                                    <strong>Phòng:</strong>
                                    <span>{{ $showtime->room->name }}</span>
                                </div>
                                
                                <div class="info-row">
                                    <i class="bi bi-calendar"></i>
                                    <strong>Ngày chiếu:</strong>
                                    <span>{{ \Carbon\Carbon::parse($showtime->date_start)->format('d/m/Y') }}</span>
                                </div>
                                
                                <div class="info-row">
                                    <i class="bi bi-clock"></i>
                                    <strong>Giờ chiếu:</strong>
                                    <span>{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                                </div>
                                
                                <div class="info-row">
                                    <i class="bi bi-hourglass"></i>
                                    <strong>Thời lượng:</strong>
                                    <span>{{ $showtime->movie->duration }} phút</span>
                                </div>
                                
                                <div class="info-row">
                                    <i class="bi bi-tag"></i>
                                    <strong>Giá vé:</strong>
                                    <span class="text-primary fw-bold">{{ number_format($showtime->price) }} đ</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Seat Selection -->
                    <div class="step-content" data-step="2">
                        <h2 class="step-title">Chọn ghế ngồi</h2>
                        
                        <div class="screen"></div>
                        
                        <div class="seat-container-wrapper">
                            <div id="seatGrid" class="seat-grid-10col">
                                <!-- Seats will be loaded here via JavaScript -->
                            </div>
                        </div>
                        
                        <div class="seat-legend">
                            <div class="legend-item">
                                <div class="legend-box available"></div>
                                <span>Còn trống</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-box selected"></div>
                                <span>Đang chọn</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-box occupied"></div>
                                <span>Đã đặt</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Food Selection -->
                    <div class="step-content" data-step="3">
                        <h2 class="step-title">Chọn thức ăn & đồ uống</h2>
                        
                        <div class="food-grid" id="foodGrid">
                            <!-- Foods will be loaded here via JavaScript -->
                        </div>
                    </div>

                    <!-- Step 4: Payment -->
                    <div class="step-content" data-step="4">
                        <h2 class="step-title">Thanh toán</h2>
                        
                        <div class="payment-section">
                            <p class="text-muted mb-4">Quét mã QR để hoàn tất thanh toán</p>
                            
                            <div class="qr-container">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https://www.facebook.com" 
                                     alt="QR Code" 
                                     class="qr-code">
                            </div>
                            
                            <div class="payment-info">
                                <i class="bi bi-info-circle-fill"></i>
                                <p>Đây là mã QR demo. Quét để chuyển đến trang Facebook.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </button>
                        
                        <button type="button" class="btn btn-primary" id="nextBtn">
                            Tiếp tục <i class="bi bi-arrow-right"></i>
                        </button>
                        
                        <button type="button" class="btn btn-primary skip-food-btn" id="skipFoodBtn" style="display: none;">
                            <i class="bi bi-skip-forward"></i> Bỏ qua
                        </button>
                        
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
                            <i class="bi bi-check-circle"></i> Hoàn tất đặt vé
                        </button>
                    </div>
                </form>
            </div>

            <!-- Summary Panel -->
            <div class="booking-summary">
                <div class="summary-header">
                    <h3>Thông tin đặt vé</h3>
                    <p>{{ $showtime->movie->title }}</p>
                </div>

                <div class="summary-section">
                    <h4>Thông tin suất chiếu</h4>
                    <div class="summary-item">
                        <span class="summary-item-label">Rạp</span>
                        <span class="summary-item-value">{{ $showtime->room->cinema->name }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-item-label">Phòng</span>
                        <span class="summary-item-value">{{ $showtime->room->name }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-item-label">Ngày</span>
                        <span class="summary-item-value">{{ \Carbon\Carbon::parse($showtime->date_start)->format('d/m/Y') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-item-label">Giờ</span>
                        <span class="summary-item-value">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                    </div>
                </div>

                <div class="summary-section" id="seatSummary" style="display: none;">
                    <h4>Ghế đã chọn</h4>
                    <div id="seatList"></div>
                </div>

                <div class="summary-section" id="foodSummary" style="display: none;">
                    <h4>Thức ăn & đồ uống</h4>
                    <div id="foodList"></div>
                </div>

                <div class="summary-total">
                    <div class="summary-total-label">Tổng cộng</div>
                    <div class="summary-total-value" id="totalPrice">0 đ</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Configuration
        const showtimeId = {{ $showtime->id }};
        const seatPrice = {{ $showtime->price }};
        let currentStep = 1;
        let selectedSeats = [];
        let selectedFoods = {};
        let allSeats = [];
        let allFoods = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadSeats();
            loadFoods();
            updateStepIndicator();
        });

        // Load seats from API
        async function loadSeats() {
            try {
                const response = await fetch(`/api/seats/${showtimeId}`);
                const data = await response.json();
                allSeats = data.seats;
                renderSeats();
            } catch (error) {
                console.error('Error loading seats:', error);
            }
        }

        // Render seats in grid of 10 per row
        function renderSeats() {
            const seatGrid = document.getElementById('seatGrid');
            seatGrid.innerHTML = '';

            if (allSeats.length === 0) {
                seatGrid.innerHTML = '<p class="text-center text-muted">Đang tải ghế...</p>';
                return;
            }

            // Sort seats by seat_number
            const sortedSeats = [...allSeats].sort((a, b) => {
                return a.seat_number.localeCompare(b.seat_number);
            });

            // Create rows of 10 seats
            let currentRow = document.createElement('div');
            currentRow.className = 'seat-row-10';
            
            sortedSeats.forEach((seat, index) => {
                const seatDiv = document.createElement('div');
                seatDiv.className = 'seat';
                seatDiv.textContent = seat.seat_number;
                seatDiv.dataset.seatId = seat.id;

                if (seat.is_occupied) {
                    seatDiv.classList.add('occupied');
                } else {
                    seatDiv.addEventListener('click', () => toggleSeat(seat.id, seatDiv));
                }

                currentRow.appendChild(seatDiv);

                // Create new row after every 10 seats
                if ((index + 1) % 10 === 0) {
                    seatGrid.appendChild(currentRow);
                    currentRow = document.createElement('div');
                    currentRow.className = 'seat-row-10';
                }
            });

            // Append remaining seats if any
            if (currentRow.children.length > 0) {
                seatGrid.appendChild(currentRow);
            }
        }

        // Toggle seat selection
        function toggleSeat(seatId, seatElement) {
            const index = selectedSeats.indexOf(seatId);
            
            if (index > -1) {
                selectedSeats.splice(index, 1);
                seatElement.classList.remove('selected');
            } else {
                selectedSeats.push(seatId);
                seatElement.classList.add('selected');
            }

            updateSummary();
        }

        // Load foods from API
        async function loadFoods() {
            try {
                const response = await fetch('/api/foods');
                allFoods = await response.json();
                renderFoods();
            } catch (error) {
                console.error('Error loading foods:', error);
            }
        }

        // Render foods
        function renderFoods() {
            const foodGrid = document.getElementById('foodGrid');
            foodGrid.innerHTML = '';

            allFoods.forEach(food => {
                const foodCard = document.createElement('div');
                foodCard.className = 'food-card';
                foodCard.innerHTML = `
                    <img src="${food.image ? '/uploads/foods/' + food.image : '/image/default-food.jpg'}" 
                         alt="${food.name}" 
                         class="food-image">
                    <div class="food-info">
                        <div class="food-name">${food.name}</div>
                        <div class="food-price">${formatPrice(food.price)} đ</div>
                        <div class="food-quantity">
                            <button type="button" class="qty-btn" onclick="updateFoodQty(${food.id}, -1)">-</button>
                            <input type="number" class="qty-input" id="food-${food.id}" value="0" readonly>
                            <button type="button" class="qty-btn" onclick="updateFoodQty(${food.id}, 1)">+</button>
                        </div>
                    </div>
                `;
                foodGrid.appendChild(foodCard);
            });
        }

        // Update food quantity
        function updateFoodQty(foodId, change) {
            const input = document.getElementById(`food-${foodId}`);
            let currentQty = parseInt(input.value) || 0;
            let newQty = Math.max(0, currentQty + change);

            const food = allFoods.find(f => f.id === foodId);
            if (food && newQty > food.total) {
                newQty = food.total;
            }

            input.value = newQty;

            if (newQty > 0) {
                selectedFoods[foodId] = {
                    id: foodId,
                    quantity: newQty,
                    name: food.name,
                    price: food.price
                };
            } else {
                delete selectedFoods[foodId];
            }

            updateSummary();
        }

        // Update summary panel
        function updateSummary() {
            // Update seat summary
            const seatSummary = document.getElementById('seatSummary');
            const seatList = document.getElementById('seatList');
            
            if (selectedSeats.length > 0) {
                seatSummary.style.display = 'block';
                const seatNumbers = selectedSeats.map(id => {
                    const seat = allSeats.find(s => s.id === id);
                    return seat ? seat.seat_number : '';
                }).join(', ');
                
                seatList.innerHTML = `
                    <div class="summary-item">
                        <span class="summary-item-label">${selectedSeats.length} ghế</span>
                        <span class="summary-item-value">${formatPrice(selectedSeats.length * seatPrice)} đ</span>
                    </div>
                    <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.5rem;">
                        ${seatNumbers}
                    </div>
                `;
            } else {
                seatSummary.style.display = 'none';
            }

            // Update food summary
            const foodSummary = document.getElementById('foodSummary');
            const foodList = document.getElementById('foodList');
            const foodItems = Object.values(selectedFoods);
            
            if (foodItems.length > 0) {
                foodSummary.style.display = 'block';
                foodList.innerHTML = foodItems.map(food => `
                    <div class="summary-item">
                        <span class="summary-item-label">${food.name} x${food.quantity}</span>
                        <span class="summary-item-value">${formatPrice(food.price * food.quantity)} đ</span>
                    </div>
                `).join('');
            } else {
                foodSummary.style.display = 'none';
            }

            // Update total price
            const seatTotal = selectedSeats.length * seatPrice;
            const foodTotal = foodItems.reduce((sum, food) => sum + (food.price * food.quantity), 0);
            const total = seatTotal + foodTotal;
            
            document.getElementById('totalPrice').textContent = formatPrice(total) + ' đ';
        }

        // Format price
        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price);
        }

        // Step navigation
        document.getElementById('nextBtn').addEventListener('click', function() {
            if (currentStep === 2 && selectedSeats.length === 0) {
                alert('Vui lòng chọn ít nhất 1 ghế!');
                return;
            }

            if (currentStep < 4) {
                currentStep++;
                updateStepIndicator();
                showStep(currentStep);
            }
        });

        document.getElementById('prevBtn').addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStepIndicator();
                showStep(currentStep);
            }
        });

        document.getElementById('skipFoodBtn').addEventListener('click', function() {
            currentStep = 4;
            updateStepIndicator();
            showStep(currentStep);
        });

        // Show specific step
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });

            // Show current step
            document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');

            // Update buttons
            document.getElementById('prevBtn').style.display = step > 1 ? 'block' : 'none';
            document.getElementById('nextBtn').style.display = step < 4 ? 'block' : 'none';
            document.getElementById('skipFoodBtn').style.display = step === 3 ? 'block' : 'none';
            document.getElementById('submitBtn').style.display = step === 4 ? 'block' : 'none';
        }

        // Update step indicator
        function updateStepIndicator() {
            const progress = ((currentStep - 1) / 3) * 100;
            document.getElementById('stepProgress').style.width = progress + '%';

            document.querySelectorAll('.step-item').forEach((item, index) => {
                const stepNum = index + 1;
                item.classList.remove('active', 'completed');
                
                if (stepNum < currentStep) {
                    item.classList.add('completed');
                } else if (stepNum === currentStep) {
                    item.classList.add('active');
                }
            });
        }

        // Form submission
      document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (selectedSeats.length === 0) {
        alert('Vui lòng chọn ít nhất 1 ghế!');
        return;
    }

    // ====== SEATS ======
    const seatContainer = document.getElementById('seatHiddenInputs');
    seatContainer.innerHTML = '';

    selectedSeats.forEach(seatId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'seat_ids[]';
        input.value = seatId;
        seatContainer.appendChild(input);
    });

    // ====== FOODS (CHUẨN ARRAY CHO LARAVEL) ======
    // ====== FOODS (ĐÚNG CHUẨN LARAVEL) ======
const foodContainer = document.getElementById('foodHiddenInputs');
foodContainer.innerHTML = '';

Object.values(selectedFoods).forEach((food, index) => {
    const foodIdInput = document.createElement('input');
    foodIdInput.type = 'hidden';
    foodIdInput.name = `foods[${index}][id]`;
    foodIdInput.value = food.id;

    const qtyInput = document.createElement('input');
    qtyInput.type = 'hidden';
    qtyInput.name = `foods[${index}][quantity]`;
    qtyInput.value = food.quantity;

    foodContainer.appendChild(foodIdInput);
    foodContainer.appendChild(qtyInput);
});

    this.submit();
});



    </script>
</body>
</html>
