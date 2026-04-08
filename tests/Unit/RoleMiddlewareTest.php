<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleMiddlewareTest extends TestCase
{
    public function test_user_with_correct_role_can_access()
    {
        $user = new User(['role' => 'admin']);
        Auth::shouldReceive('user')->andReturn($user);

        $middleware = new RoleMiddleware();
        $request = Request::create('/admin', 'GET');

        $response = $middleware->handle($request, function () {
            return response('OK');
        }, 'admin');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_user_with_incorrect_role_is_denied()
    {
        $user = new User(['role' => 'pelanggan']);
        Auth::shouldReceive('user')->andReturn($user);

        $middleware = new RoleMiddleware();
        $request = Request::create('/admin', 'GET');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Akses ditolak. Role tidak sesuai.');

        $middleware->handle($request, function () {}, 'admin');
    }

    public function test_unauthenticated_user_is_denied()
    {
        Auth::shouldReceive('user')->andReturn(null);

        $middleware = new RoleMiddleware();
        $request = Request::create('/admin', 'GET');

        $this->expectException(HttpException::class);
        $middleware->handle($request, function () {}, 'admin');
    }
}
