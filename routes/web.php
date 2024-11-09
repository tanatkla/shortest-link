<?php

use App\Http\Controllers\ShortLinkGuestController;
use App\Http\Controllers\UseShortLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShortLinkGuestController::class, 'index'])->name('short-link-guest-forms.index');

Route::resource('short-link-guests', ShortLinkGuestController::class);


Route::get('/myshort-{shortest_url}', [UseShortLinkController::class, 'shortestLink'])->name('shortest-url');


require __DIR__.'/auth.php';