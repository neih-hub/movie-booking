# SCRIPT THUYẾT TRÌNH DỰ ÁN MOVIE BOOKING SYSTEM

## PHẦN 1: GIỚI THIỆU TỔNG QUAN

Xin chào mọi người! Hôm nay tôi xin được thuyết trình về dự án **Hệ thống đặt vé xem phim trực tuyến** (Movie Booking System). Đây là một ứng dụng web được xây dựng bằng Laravel Framework, cho phép người dùng tìm kiếm phim, đặt vé trực tuyến và quản trị viên quản lý toàn bộ hệ thống rạp chiếu phim.

Dự án được chia thành 2 phần chính:
- **Phần người dùng (Client)**: Dành cho khách hàng tìm kiếm và đặt vé
- **Phần quản trị (Admin)**: Dành cho quản trị viên vận hành hệ thống

Bây giờ tôi sẽ đi vào chi tiết từng chức năng và giải thích cách hoạt động của chúng.

---

## PHẦN 2: CÁC CHỨC NĂNG NGƯỜI DÙNG (CLIENT FEATURES)

### 2.1. Chức Năng Xác Thực (Authentication)

#### A. Đăng Ký Tài Khoản

**Cách hoạt động:**
1. Người dùng truy cập trang `/register` và điền form đăng ký
2. Hệ thống validate dữ liệu đầu vào (email phải unique, password tối thiểu 6 ký tự)
3. Khi submit, `AuthController@register` xử lý:
   ```php
   $user = User::create([
       'name' => $request->name,
       'email' => $request->email,
       'password' => Hash::make($request->password),
       'role' => 1,      // Mặc định là khách hàng
       'status' => 1     // Mặc định là active
   ]);
   ```
4. Sau khi tạo tài khoản, hệ thống tự động đăng nhập người dùng bằng `Auth::login($user)`
5. Chuyển hướng về trang chủ

**Điểm đặc biệt:** Mọi tài khoản mới đều có `role = 1` (khách hàng) và `status = 1` (đã kích hoạt). Chỉ admin mới có thể thay đổi role của người dùng.

#### B. Đăng Nhập

**Cách hoạt động:**
1. Người dùng nhập email và password tại trang `/login`
2. `AuthController@login` sử dụng `Auth::attempt()` để xác thực:
   ```php
   if (Auth::attempt(['email' => $email, 'password' => $password])) {
       // Kiểm tra profile hoàn chỉnh
       if (!$user->name || !$user->phone || !$user->address) {
           return redirect()->route('profile');
       }
       return redirect()->route('home');
   }
   ```
3. **Logic đặc biệt:** Sau khi đăng nhập thành công, hệ thống kiểm tra xem người dùng đã điền đầy đủ thông tin cá nhân chưa (name, phone, address). Nếu thiếu, **bắt buộc** chuyển hướng về trang profile để hoàn thiện thông tin.

**Lý do:** Đảm bảo mọi người dùng đều có thông tin liên lạc đầy đủ trước khi đặt vé.

#### C. Đăng Xuất

**Cách hoạt động:**
- Đơn giản gọi `Auth::logout()` và xóa session
- Chuyển hướng về trang chủ

---

### 2.2. Chức Năng Tìm Kiếm Phim

#### A. Xem Danh Sách Phim

**Cách hoạt động:**
1. Người dùng truy cập `/movies`
2. `MovieController@index` lấy tất cả phim từ database:
   ```php
   $movies = Movie::orderBy('release_date', 'desc')->get();
   ```
3. Hiển thị dạng lưới (grid) với poster, tên phim, thể loại, thời lượng

#### B. Xem Chi Tiết Phim

**Cách hoạt động:**
1. Khi click vào một phim, truy cập `/movie/{id}`
2. `MovieController@show` sử dụng **Eager Loading** để tối ưu query:
   ```php
   $movie = Movie::with(['showtimes.room.cinema'])
       ->findOrFail($id);
   ```
3. Trang chi tiết hiển thị:
   - Thông tin phim: poster, trailer, mô tả, diễn viên, đạo diễn
   - **Danh sách suất chiếu** được nhóm theo rạp và ngày
   - Nút "Đặt vé" cho từng suất chiếu

**Kỹ thuật quan trọng:** Sử dụng `with()` để load trước các quan hệ, tránh vấn đề N+1 query (nếu có 100 suất chiếu, không dùng `with()` sẽ tạo ra 100+ queries riêng lẻ).

---

### 2.3. Chức Năng Đặt Vé Nhanh (Quick Booking)

Đây là một trong những chức năng phức tạp và thú vị nhất của hệ thống!

**Cách hoạt động:**

Ở trang chủ, có một form "Đặt vé nhanh" với 4 dropdown: Phim → Rạp → Ngày → Suất chiếu. Các dropdown này có **quan hệ phụ thuộc tầng** (Cascading Dropdowns).

#### Bước 1: Chọn Phim
- Người dùng chọn phim từ dropdown đầu tiên
- JavaScript bắt sự kiện `change` và gọi AJAX đến `/api/cinemas-by-movie?movie_id=X`

**Backend xử lý (`HomeController@getCinemasByMovie`):**
```php
$cinemas = Cinema::whereHas('rooms.showtimes', function($query) use ($movieId) {
    $query->where('movie_id', $movieId);
})->get();
```

**Giải thích:** Query này tìm tất cả rạp (`Cinema`) mà có phòng (`rooms`) có suất chiếu (`showtimes`) của phim đã chọn. Đây là một **nested relationship query** rất mạnh mẽ của Eloquent.

#### Bước 2: Chọn Rạp
- Sau khi chọn rạp, gọi `/api/rooms?cinema_id=Y&movie_id=X`
- Backend trả về danh sách phòng của rạp đó

#### Bước 3: Chọn Ngày
- Gọi `/api/dates?movie_id=X&room_id=Z`
- Backend query:
```php
$dates = Showtime::where('movie_id', $movieId)
    ->where('room_id', $roomId)
    ->distinct()
    ->pluck('date_start');
```
- Trả về các ngày chiếu duy nhất

#### Bước 4: Tìm Suất Chiếu
- Khi nhấn "Tìm kiếm", gọi `/api/showtimes?movie_id=X&room_id=Z&date=...`
- Backend trả về chi tiết suất chiếu khớp tất cả điều kiện
- Người dùng click "Đặt vé" → chuyển đến trang chọn ghế

**Điểm mạnh:** Mỗi lần chọn, hệ thống chỉ hiển thị các option hợp lệ, tránh người dùng chọn nhầm suất chiếu không tồn tại.

---

### 2.4. Chức Năng Đặt Vé (Booking Process)

Đây là **luồng nghiệp vụ cốt lõi** của hệ thống. Tôi sẽ giải thích chi tiết từng bước:

#### BƯỚC 1: Khởi Tạo Trang Đặt Vé

**Cách hoạt động:**
1. Người dùng click "Đặt vé" tại một suất chiếu → truy cập `/booking/{showtime_id}`
2. `BookingController@create` xử lý:
   ```php
   $showtime = Showtime::with(['movie', 'room.cinema', 'room.seats'])
       ->findOrFail($showtime_id);
   return view('bookings.create', compact('showtime'));
   ```
3. Trang hiển thị thông tin suất chiếu: phim, rạp, phòng, giờ chiếu, giá vé

#### BƯỚC 2: Load Sơ Đồ Ghế (Seat Map)

**Cách hoạt động:**

Khi trang load xong, JavaScript tự động gọi AJAX: `GET /api/seats/{showtime_id}`

**Backend xử lý (`BookingController@getSeats`):**
```php
// 1. Lấy tất cả ghế của phòng
$showtime = Showtime::with('room.seats')->findOrFail($showtime_id);

// 2. Tìm các ghế ĐÃ ĐƯỢC ĐẶT trong suất chiếu này
$bookedSeatIds = BookingSeat::whereHas('booking', function($query) use ($showtime_id) {
    $query->where('showtime_id', $showtime_id);
})->pluck('seat_id')->toArray();

// 3. Map từng ghế với trạng thái
$seats = $showtime->room->seats->map(function($seat) use ($bookedSeatIds) {
    return [
        'id' => $seat->id,
        'seat_number' => $seat->seat_number,
        'type' => $seat->type,  // thường, VIP, đôi
        'is_occupied' => in_array($seat->id, $bookedSeatIds)
    ];
});

return response()->json(['seats' => $seats, 'price' => $showtime->price]);
```

**Frontend xử lý:**
- Nhận JSON và render lưới ghế (10 ghế/hàng)
- Ghế có `is_occupied = true` → hiển thị màu xám, disable click
- Ghế trống → cho phép click, khi click thêm vào mảng `selectedSeats`
- Tính tổng tiền realtime: `totalPrice = selectedSeats.length * price`

#### BƯỚC 3: Chọn Đồ Ăn (Optional)

**Cách hoạt động:**
- Gọi AJAX: `GET /api/foods`
- Backend trả về danh sách đồ ăn còn hàng:
  ```php
  $foods = Food::where('total', '>', 0)->get();
  ```
- Người dùng chọn món và số lượng
- Cập nhật tổng tiền: `totalPrice += food.price * quantity`

#### BƯỚC 4: Xác Nhận Đặt Vé

**Cách hoạt động:**

Khi nhấn "Xác nhận đặt vé", form submit `POST /booking` với dữ liệu:
```javascript
{
    showtime_id: 123,
    seat_ids: [45, 46, 47],
    foods: [
        {id: 1, quantity: 2},
        {id: 3, quantity: 1}
    ]
}
```

**Backend xử lý (`BookingController@store`):**

Đây là phần **QUAN TRỌNG NHẤT**, sử dụng **Database Transaction** để đảm bảo tính toàn vẹn dữ liệu:

```php
DB::beginTransaction();
try {
    // 1. Kiểm tra đăng nhập
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // 2. Tính tổng tiền
    $seatPrice = $showtime->price * count($request->seat_ids);
    $foodPrice = 0;
    foreach ($request->foods as $foodItem) {
        $food = Food::find($foodItem['id']);
        $foodPrice += $food->price * $foodItem['quantity'];
    }
    $totalPrice = $seatPrice + $foodPrice;

    // 3. Tạo bản ghi Booking
    $booking = Booking::create([
        'user_id' => Auth::id(),
        'showtime_id' => $request->showtime_id,
        'total_price' => $totalPrice,
        'status' => 1
    ]);

    // 4. Tạo các bản ghi BookingSeat (lưu giá vé tại thời điểm đặt)
    foreach ($request->seat_ids as $seatId) {
        BookingSeat::create([
            'booking_id' => $booking->id,
            'seat_id' => $seatId,
            'price' => $showtime->price
        ]);
    }

    // 5. Tạo các bản ghi BookingFood
    foreach ($request->foods as $foodItem) {
        $food = Food::find($foodItem['id']);
        BookingFood::create([
            'booking_id' => $booking->id,
            'food_id' => $foodItem['id'],
            'quantity' => $foodItem['quantity'],
            'price' => $food->price
        ]);
        
        // 6. TRỪ KHO đồ ăn
        $food->decrement('total', $foodItem['quantity']);
    }

    // 7. Tạo thông báo cho người dùng
    Notification::create([
        'user_id' => Auth::id(),
        'type' => 'booking_success',
        'message' => "Bạn vừa đặt thành công vé xem phim..."
    ]);

    // 8. Commit transaction
    DB::commit();
    
    return redirect()->route('booking.success', $booking->id);
    
} catch (\Exception $e) {
    // Nếu có lỗi, rollback tất cả
    DB::rollBack();
    return back()->with('error', 'Có lỗi xảy ra');
}
```

**Tại sao phải dùng Transaction?**

Giả sử có 1000 người cùng đặt vé một lúc:
- Nếu không dùng transaction, có thể xảy ra: Tạo được Booking nhưng lỗi khi tạo BookingSeat → dữ liệu không nhất quán
- Với transaction: Nếu bất kỳ bước nào lỗi, **TẤT CẢ** đều bị hủy (rollback), đảm bảo database luôn ở trạng thái hợp lệ

#### BƯỚC 5: Trang Thành Công

**Cách hoạt động:**
- Chuyển hướng đến `/booking/success/{id}`
- `BookingController@success` load thông tin booking với **Eager Loading**:
  ```php
  $booking = Booking::with([
      'showtime.movie',
      'showtime.room.cinema',
      'bookingSeats.seat',
      'bookingFoods.food'
  ])->findOrFail($id);
  ```
- Hiển thị mã vé, thông tin phim, ghế đã đặt, đồ ăn, tổng tiền

**Bảo mật:** Kiểm tra `$booking->user_id === Auth::id()` để đảm bảo người dùng chỉ xem được vé của chính mình.

---

### 2.5. Chức Năng Quản Lý Hồ Sơ (Profile Management)

**Cách hoạt động:**

#### A. Xem Hồ Sơ
- Truy cập `/profile`
- `ProfileController@show` hiển thị thông tin: tên, email, số điện thoại, địa chỉ, ngày sinh, giới tính, avatar

#### B. Cập Nhật Hồ Sơ
- Submit form `POST /profile`
- `ProfileController@update` xử lý:
  ```php
  $data = $request->only(['name', 'phone', 'address', 'birthday', 'gender']);
  
  // Xử lý upload avatar
  if ($request->hasFile('avatar')) {
      $file = $request->file('avatar');
      $name = time() . "_" . $file->getClientOriginalName();
      $file->move('uploads/avatars', $name);
      $data['avatar'] = 'uploads/avatars/' . $name;
  }
  
  Auth::user()->update($data);
  ```

**Điểm đặc biệt:** Nếu upload avatar mới, file được lưu với tên unique (timestamp + tên gốc) để tránh trùng lặp.

#### C. Xem Lịch Sử Đặt Vé
- Hiển thị tất cả booking của người dùng:
  ```php
  $bookings = Auth::user()->bookings()
      ->with(['showtime.movie', 'showtime.room.cinema'])
      ->orderBy('created_at', 'desc')
      ->get();
  ```

---

### 2.6. Chức Năng Thông Báo (Notifications)

**Cách hoạt động:**

#### A. Hiển Thị Thông Báo
- Truy cập `/notifications`
- `NotificationController@index` lấy thông báo của người dùng:
  ```php
  $notifications = Auth::user()->notifications()
      ->orderBy('created_at', 'desc')
      ->paginate(20);
  ```

#### B. Đếm Thông Báo Chưa Đọc
- AJAX gọi `/api/notifications/unread-count`
- Backend:
  ```php
  $count = Auth::user()->notifications()
      ->where('is_read', 0)
      ->count();
  return response()->json(['count' => $count]);
  ```
- Frontend hiển thị badge số lượng trên icon chuông

#### C. Đánh Dấu Đã Đọc
- Khi click vào thông báo, gọi `POST /notifications/{id}/mark-read`
- Backend:
  ```php
  $notification->update(['is_read' => 1]);
  ```

---

## PHẦN 3: CÁC CHỨC NĂNG QUẢN TRỊ (ADMIN FEATURES)

Tất cả các route admin đều được bảo vệ bởi **middleware `admin`**, chỉ cho phép người dùng có `role = 0` truy cập.

### 3.1. Dashboard Thống Kê

**Cách hoạt động:**

Truy cập `/admin`, `AdminController@dashboard` tính toán các số liệu:

```php
// Tổng số người dùng
$totalUsers = User::count();

// Tổng số phim
$totalMovies = Movie::count();

// Tổng doanh thu
$totalRevenue = Booking::sum('total_price');

// Số vé đã bán
$totalBookings = Booking::count();

// Phim phổ biến (theo số suất chiếu)
$popularMovies = Movie::withCount('showtimes')
    ->orderBy('showtimes_count', 'desc')
    ->take(5)
    ->get();
```

**Lưu ý:** Logic "phim phổ biến" hiện tại dựa trên số lượng suất chiếu, không phải số vé bán ra. Có thể cải thiện bằng cách đếm số booking.

---

### 3.2. Quản Lý Phim (Movie Management)

#### A. Xem Danh Sách Phim
- Truy cập `/admin/movies`
- Hiển thị tất cả phim dạng bảng

#### B. Thêm Phim Mới

**Cách hoạt động:**
1. Truy cập `/admin/movies/create`
2. Điền form: tên phim, mô tả, thể loại, thời lượng, ngày phát hành, trailer URL, poster
3. Submit `POST /admin/movies`
4. `MovieAdminController@store` xử lý:

```php
$data = $request->all();

// Xử lý thể loại (từ string thành array)
if ($request->genre) {
    $data['genre'] = array_map('trim', explode(',', $request->genre));
} else {
    $data['genre'] = [];
}

// Upload poster
if ($request->hasFile('poster')) {
    $file = $request->file('poster');
    $name = time().'_'.$file->getClientOriginalName();
    $file->move('uploads/posters', $name);
    $data['poster'] = 'uploads/posters/'.$name;
}

Movie::create($data);
```

**Giải thích:**
- Thể loại nhập dạng chuỗi phân tách dấu phẩy: "Hành động, Hài, Kinh dị"
- `explode(',', ...)` tách thành mảng: `["Hành động", "Hài", "Kinh dị"]`
- `array_map('trim', ...)` loại bỏ khoảng trắng thừa
- Model `Movie` có cast `'genre' => 'array'` nên tự động lưu dạng JSON trong database

#### C. Sửa Phim

**Cách hoạt động:**
- Tương tự thêm mới, nhưng dùng `$movie->update($data)`
- Nếu upload poster mới, file cũ vẫn còn trên server (có thể cải thiện bằng cách xóa file cũ)

#### D. Xóa Phim

**Cách hoạt động:**
```php
Movie::destroy($id);
```

**Lưu ý:** Nếu phim đã có suất chiếu hoặc booking, việc xóa có thể gây lỗi. Nên thêm kiểm tra hoặc dùng soft delete.

---

### 3.3. Quản Lý Rạp & Phòng (Cinema & Room Management)

#### A. Quản Lý Rạp
- CRUD cơ bản: thêm, sửa, xóa rạp
- Mỗi rạp có: tên, địa chỉ, số điện thoại

#### B. Quản Lý Phòng

Đây là phần **RẤT THÚ VỊ** vì có logic tự động tạo ghế!

**Thêm Phòng Mới:**

```php
// RoomAdminController@store
$room = Room::create([
    'cinema_id' => $request->cinema_id,
    'name' => $request->name,
    'total_seats' => $request->total_seats,
    'seats_per_row' => $request->seats_per_row ?? 10
]);

// TỰ ĐỘNG TẠO GHẾ
for ($i = 1; $i <= $request->total_seats; $i++) {
    Seat::create([
        'room_id' => $room->id,
        'seat_number' => $room->name . str_pad($i, 2, '0', STR_PAD_LEFT),
        'type' => 'normal'  // Mặc định là ghế thường
    ]);
}
```

**Ví dụ:** Tạo phòng "A" với 30 ghế → Hệ thống tự động tạo 30 bản ghi Seat với số ghế: A01, A02, A03, ..., A30.

**Cập Nhật Số Lượng Ghế:**

```php
// RoomAdminController@update
$oldTotal = $room->total_seats;
$newTotal = $request->total_seats;

if ($newTotal > $oldTotal) {
    // Thêm ghế mới
    for ($i = $oldTotal + 1; $i <= $newTotal; $i++) {
        Seat::create([
            'room_id' => $room->id,
            'seat_number' => $room->name . str_pad($i, 2, '0', STR_PAD_LEFT),
            'type' => 'normal'
        ]);
    }
} elseif ($newTotal < $oldTotal) {
    // Xóa ghế thừa (xóa từ cuối)
    $diff = $oldTotal - $newTotal;
    Seat::where('room_id', $room->id)
        ->orderBy('id', 'desc')
        ->limit($diff)
        ->delete();
}
```

**Ưu điểm:** Giữ nguyên ID của ghế cũ, không làm hỏng dữ liệu booking lịch sử.

**Nhược điểm:** Nếu xóa ghế đã từng được đặt, có thể gây lỗi dữ liệu.

#### C. Xem Sơ Đồ Ghế (Seat Map)

**Cách hoạt động:**
- Truy cập `/admin/rooms/{id}/seats-honeycomb`
- `RoomAdminController@showSeatsHoneycomb` xử lý:

```php
$room = Room::with(['seats.bookings' => function($query) {
    $query->latest()->limit(1);  // Lấy booking mới nhất của mỗi ghế
}])->findOrFail($id);

$seatsPerRow = $room->seats_per_row ?? 10;
$rows = [];

foreach ($room->seats as $index => $seat) {
    $rowIndex = floor($index / $seatsPerRow);
    $rows[$rowIndex][] = [
        'seat' => $seat,
        'latest_booking' => $seat->bookings->first()
    ];
}

return view('admin.rooms.seats-honeycomb', compact('room', 'rows'));
```

**Hiển thị:** Ghế được chia thành các hàng (mặc định 10 ghế/hàng), mỗi ghế hiển thị thông tin booking mới nhất (nếu có).

---

### 3.4. Quản Lý Suất Chiếu (Showtime Management)

#### A. Thêm Suất Chiếu

**Cách hoạt động:**
```php
Showtime::create([
    'movie_id' => $request->movie_id,
    'room_id' => $request->room_id,
    'date_start' => $request->date_start,
    'start_time' => $request->start_time,
    'price' => $request->price
]);
```

**Lưu ý:** Hiện tại **CHƯA CÓ** kiểm tra trùng lịch. Nếu admin tạo 2 suất chiếu cùng phòng, cùng giờ → có thể gây lỗi khi đặt vé.

**Cải thiện đề xuất:** Thêm validation kiểm tra overlap:
```php
$exists = Showtime::where('room_id', $request->room_id)
    ->where('date_start', $request->date_start)
    ->where('start_time', $request->start_time)
    ->exists();
    
if ($exists) {
    return back()->with('error', 'Suất chiếu này đã tồn tại!');
}
```

#### B. Lọc Suất Chiếu

**Cách hoạt động:**
- Trang danh sách hỗ trợ lọc theo: Phim, Rạp, Ngày
- `ShowtimeAdminController@list`:

```php
$query = Showtime::with(['movie', 'room.cinema']);

if ($request->filled('movie_id')) {
    $query->where('movie_id', $request->movie_id);
}

if ($request->filled('cinema_id')) {
    $query->whereHas('room', function($q) use ($request) {
        $q->where('cinema_id', $request->cinema_id);
    });
}

if ($request->filled('date')) {
    $query->where('date_start', $request->date);
}

$showtimes = $query->orderBy('date_start', 'desc')->paginate(20);
```

**Kỹ thuật:** Sử dụng `whereHas()` để lọc qua quan hệ (filter showtime theo cinema thông qua room).

---

### 3.5. Quản Lý Đặt Vé (Booking Management)

#### A. Xem Danh Sách Booking

**Cách hoạt động:**
```php
$bookings = Booking::with([
    'user',
    'showtime.movie',
    'showtime.room.cinema',
    'bookingSeats.seat'
])->orderBy('created_at', 'desc')->paginate(20);
```

**Hiển thị:** Bảng với các cột: Mã booking, Người dùng, Phim, Rạp, Ghế, Tổng tiền, Trạng thái.

#### B. Hủy/Xóa Booking

**Cách hoạt động:**
```php
// BookingAdminController@destroy
$booking = Booking::findOrFail($id);

DB::beginTransaction();
try {
    // 1. Hoàn lại kho đồ ăn
    foreach ($booking->bookingFoods as $bf) {
        $bf->food->increment('total', $bf->quantity);
    }
    
    // 2. Xóa booking seats
    $booking->bookingSeats()->delete();
    
    // 3. Xóa booking foods
    $booking->bookingFoods()->delete();
    
    // 4. Xóa booking
    $booking->delete();
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
}
```

**Quan trọng:** Phải hoàn lại số lượng đồ ăn đã trừ khi đặt vé!

---

### 3.6. Quản Lý Người Dùng (User Management)

#### A. Xem Danh Sách Người Dùng

**Cách hoạt động:**
- Hỗ trợ tìm kiếm theo tên/email, lọc theo role và status
```php
$query = User::query();

if ($request->filled('search')) {
    $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%$search%")
          ->orWhere('email', 'like', "%$search%");
    });
}

if ($request->filled('role')) {
    $query->where('role', $request->role);
}

if ($request->filled('status')) {
    $query->where('status', $request->status);
}

$users = $query->orderBy('created_at', 'desc')->paginate(15);
```

#### B. Sửa Thông Tin Người Dùng

**Cách hoạt động:**
- Admin có thể sửa: tên, email, role, status, thông tin cá nhân
- Có thể reset password cho người dùng
```php
if ($request->filled('password')) {
    $data['password'] = Hash::make($request->password);
}
```

#### C. Khóa/Mở Khóa Tài Khoản

**Cách hoạt động:**
```php
// UserAdminController@toggleStatus
$user->status = $user->status == 1 ? 0 : 1;
$user->save();
```

**Ý nghĩa:** `status = 0` → tài khoản bị khóa, không thể đăng nhập.

#### D. Xóa Người Dùng

**Cách hoạt động:**
```php
// UserAdminController@destroy
if ($user->id == Auth::id()) {
    return back()->with('error', 'Bạn không thể xóa tài khoản của chính bạn!');
}

$user->delete();
```

**Bảo mật:** Ngăn admin tự xóa chính mình.

**Lưu ý:** Nếu người dùng đã có booking, việc xóa có thể gây lỗi. Nên dùng soft delete hoặc chỉ cho phép khóa tài khoản.

---

### 3.7. Quản Lý Đồ Ăn (Food Management)

#### A. Thêm Món Ăn

**Cách hoạt động:**
```php
$data = $request->all();

// Upload ảnh
if ($request->hasFile('image')) {
    $file = $request->file('image');
    $name = time().'_'.$file->getClientOriginalName();
    $file->move('public/uploads/foods', $name);
    $data['image'] = 'uploads/foods/'.$name;
}

Food::create($data);
```

#### B. Cập Nhật Món Ăn

**Cách hoạt động:**
```php
// FoodAdminController@update
if ($request->hasFile('image')) {
    // Xóa ảnh cũ
    if ($food->image && file_exists(public_path($food->image))) {
        unlink(public_path($food->image));
    }
    
    // Upload ảnh mới
    $file = $request->file('image');
    $name = time().'_'.$file->getClientOriginalName();
    $file->move('public/uploads/foods', $name);
    $data['image'] = 'uploads/foods/'.$name;
}

$food->update($data);
```

**Điểm mạnh:** Tự động xóa file ảnh cũ khi upload ảnh mới, tiết kiệm dung lượng server.

#### C. Quản Lý Tồn Kho

**Cách hoạt động:**
- Mỗi món ăn có trường `total` (số lượng tồn kho)
- Khi khách đặt vé kèm đồ ăn → `$food->decrement('total', $quantity)`
- Admin có thể cập nhật lại số lượng tồn kho bất kỳ lúc nào

---

### 3.8. Quản Lý Bài Viết (Post Management)

#### A. Thêm Bài Viết

**Cách hoạt động:**
```php
$data = $request->all();

// Upload thumbnail
if ($request->hasFile('thumbnail')) {
    $file = $request->file('thumbnail');
    $name = time().'_'.$file->getClientOriginalName();
    $file->move('uploads/posts', $name);
    $data['thumbnail'] = 'uploads/posts/'.$name;
}

$data['author_id'] = Auth::id();  // Lưu tác giả
$data['status'] = $request->status ?? 'draft';  // Mặc định là nháp

Post::create($data);
```

#### B. Trạng Thái Bài Viết

**Cách hoạt động:**
- Mỗi bài viết có `status`: `'draft'` (nháp) hoặc `'published'` (đã xuất bản)
- Chỉ bài viết `published` mới hiển thị ở trang người dùng:
```php
// PostController@index (trang người dùng)
$posts = Post::where('status', 'published')
    ->orderBy('created_at', 'desc')
    ->paginate(10);
```

**Ý nghĩa:** Admin có thể soạn bài trước, lưu dạng nháp, sau đó mới publish.

---

## PHẦN 4: CÁC KỸ THUẬT ĐẶC BIỆT

### 4.1. Eager Loading (Tối Ưu Query)

**Vấn đề N+1 Query:**
```php
// BAD: Tạo ra 1 + N queries
$bookings = Booking::all();
foreach ($bookings as $booking) {
    echo $booking->user->name;  // Mỗi lần loop query 1 lần
}
```

**Giải pháp:**
```php
// GOOD: Chỉ 2 queries
$bookings = Booking::with('user')->all();
foreach ($bookings as $booking) {
    echo $booking->user->name;  // Không query thêm
}
```

**Ứng dụng trong dự án:**
- Trang chi tiết phim: `Movie::with(['showtimes.room.cinema'])`
- Trang booking success: `Booking::with(['showtime.movie', 'bookingSeats.seat'])`

### 4.2. Database Transaction

**Khi nào dùng:**
- Khi có nhiều thao tác database phụ thuộc nhau
- Nếu 1 thao tác lỗi, tất cả phải rollback

**Ví dụ trong dự án:**
- Đặt vé: Tạo Booking + BookingSeat + BookingFood + Trừ kho + Tạo thông báo
- Xóa booking: Xóa Booking + BookingSeat + BookingFood + Hoàn kho

### 4.3. Middleware Bảo Mật

**Admin Middleware:**
```php
// app/Http/Middleware/AdminMiddleware.php
if (Auth::check() && Auth::user()->role == 0) {
    return $next($request);
}
return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
```

**Ứng dụng:**
- Tất cả route `/admin/*` đều dùng middleware này
- Ngăn người dùng thường truy cập trang quản trị

### 4.4. Cascading Dropdowns (AJAX)

**Kỹ thuật:**
- Mỗi dropdown phụ thuộc vào dropdown trước
- Khi dropdown cha thay đổi → gọi AJAX load dữ liệu cho dropdown con
- Sử dụng `whereHas()` để query qua nhiều quan hệ

**Ứng dụng:** Chức năng "Đặt vé nhanh" ở trang chủ.

### 4.5. File Management

**Upload File:**
```php
$file = $request->file('avatar');
$name = time() . "_" . $file->getClientOriginalName();
$file->move('uploads/avatars', $name);
```

**Xóa File Cũ:**
```php
if (file_exists(public_path($oldPath))) {
    unlink(public_path($oldPath));
}
```

**Ứng dụng:** Upload poster phim, avatar người dùng, ảnh đồ ăn.

---

## PHẦN 5: KẾT LUẬN

### Tổng Kết Các Chức Năng

**Phía Người Dùng:**
1. ✅ Đăng ký/Đăng nhập với kiểm tra profile hoàn chỉnh
2. ✅ Tìm kiếm và xem chi tiết phim
3. ✅ Đặt vé nhanh với cascading dropdowns
4. ✅ Chọn ghế trực quan với sơ đồ realtime
5. ✅ Đặt thêm đồ ăn khi đặt vé
6. ✅ Quản lý hồ sơ cá nhân và lịch sử đặt vé
7. ✅ Nhận thông báo khi đặt vé thành công

**Phía Quản Trị:**
1. ✅ Dashboard thống kê tổng quan
2. ✅ Quản lý phim (CRUD + upload poster)
3. ✅ Quản lý rạp và phòng chiếu
4. ✅ Tự động tạo/cập nhật ghế khi thay đổi phòng
5. ✅ Quản lý suất chiếu với lọc đa điều kiện
6. ✅ Quản lý đặt vé và hủy vé
7. ✅ Quản lý người dùng (khóa/mở khóa/xóa)
8. ✅ Quản lý đồ ăn với tồn kho tự động
9. ✅ Quản lý bài viết với trạng thái draft/published

### Điểm Mạnh Của Hệ Thống

1. **Sử dụng Transaction:** Đảm bảo tính toàn vẹn dữ liệu
2. **Eager Loading:** Tối ưu hiệu suất query
3. **Middleware Bảo Mật:** Phân quyền rõ ràng
4. **AJAX Realtime:** Trải nghiệm người dùng mượt mà
5. **File Management:** Tự động xóa file cũ khi upload mới
6. **Validation Chặt Chẽ:** Kiểm tra dữ liệu đầu vào

### Hướng Cải Thiện

1. **Kiểm tra trùng suất chiếu:** Thêm validation khi tạo showtime
2. **Soft Delete:** Thay vì xóa cứng, dùng soft delete cho User, Movie, Booking
3. **Thống kê nâng cao:** Dashboard hiển thị biểu đồ doanh thu theo thời gian
4. **Payment Gateway:** Tích hợp cổng thanh toán online (VNPay, Momo)
5. **Email Notification:** Gửi email xác nhận khi đặt vé thành công
6. **QR Code:** Tạo mã QR cho vé để quét khi vào rạp

---

## CÂU HỎI THƯỜNG GẶP

**Q: Nếu 2 người cùng chọn 1 ghế cùng lúc thì sao?**
A: Hiện tại hệ thống chưa có cơ chế lock ghế. Người submit form trước sẽ được ưu tiên. Người sau sẽ nhận thông báo lỗi (nếu có validation). Cải thiện: Thêm cơ chế "giữ ghế tạm thời" trong 5 phút.

**Q: Tại sao phải lưu giá vé vào BookingSeat?**
A: Vì giá vé có thể thay đổi theo thời gian. Lưu giá tại thời điểm đặt vé đảm bảo dữ liệu lịch sử chính xác.

**Q: Làm sao để ngăn admin tự xóa tài khoản mình?**
A: Có kiểm tra `if ($user->id == Auth::id())` trong hàm `destroy()`.

**Q: Cascading dropdown hoạt động như thế nào?**
A: Mỗi dropdown có sự kiện `onChange` → gọi AJAX → backend query dữ liệu phù hợp → trả JSON → frontend render dropdown tiếp theo.

---

**Cảm ơn mọi người đã lắng nghe! Có câu hỏi nào không ạ?** 🎬🍿
