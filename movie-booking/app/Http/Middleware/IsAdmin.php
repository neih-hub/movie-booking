namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
public function handle($request, Closure $next)
{
if (!Auth::check() || Auth::user()->role !== 0) {
return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
}
return $next($request);
}
}