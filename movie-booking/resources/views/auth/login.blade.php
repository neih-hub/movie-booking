<form action="{{ route('login') }}" method="POST">
  @csrf
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Mật khẩu" required>
  <button type="submit">Đăng nhập</button>
</form>