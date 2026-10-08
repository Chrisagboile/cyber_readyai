```php
<?php

namespace App\Modules\PublicTest\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicTestAuth
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (! auth()->guard('public_test')->check()) {
            return redirect()->route(
                'public-test.login'
            );
        }

        return $next($request);
    }
}
```
