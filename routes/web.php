<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Produtividade;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {
    return $request->session()->get('logged_in') ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    if ($email === 'test@test.com' && $password === 'test123') {
        $request->session()->put('logged_in', true);
        $request->session()->put('user_email', $email);
        return redirect()->route('dashboard');
    }

    return redirect()->route('login')->withErrors([
        'email' => 'Credenciais inválidas. Use test@test.com / test123',
    ])->withInput();
})->name('login.submit');

Route::post('/logout', function (Request $request) {
    $request->session()->forget(['logged_in', 'user_email']);
    return redirect()->route('login');
})->name('logout');

Route::get('/dashboard', function (Request $request) {
    if (!$request->session()->get('logged_in')) {
        return redirect()->route('login');
    }

    $linhas = [
        'geladeira' => 'Geladeira',
        'maquina' => 'Máq. de Lavar',
        'tv' => 'TV',
        'arcondicionado' => 'Ar-Condicionado',
    ];

    $rows = Produtividade::query()
        ->select([
            'linha',
            DB::raw('SUM(quantidade_produzida) as produzida'),
            DB::raw('SUM(quantidade_defeitos) as defeitos'),
        ])
        ->whereBetween('data_producao', ['2026-01-01', '2026-01-31'])
        ->groupBy('linha')
        ->get();

    $data = $rows->map(function ($r) use ($linhas) {
        return [
            'id' => $r->linha,
            'label' => $linhas[$r->linha] ?? $r->linha,
            'produzida' => (int) $r->produzida,
            'defeitos' => (int) $r->defeitos,
        ];
    })->values();

    return view('dashboard', [
        'userEmail' => $request->session()->get('user_email'),
        'data' => $data,
    ]);
})->name('dashboard');
