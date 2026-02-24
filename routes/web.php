<?php

use App\Models\BusinessCard;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/', fn () => redirect('/admin'));

Route::middleware('throttle:30,1')->group(function () {

    Route::get('/card/{slug}', function ($slug) {
        $card = BusinessCard::where('slug', $slug)
                    ->where('is_active', true)
                    ->firstOrFail();

        $qrCode = QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->generate(route('card.show', $card->slug));

        return view('card.show', compact('card', 'qrCode'));
    })->name('card.show');

    // vCard download
    Route::get('/card/{slug}/vcard', function ($slug) {
        $card = BusinessCard::where('slug', $slug)
                    ->where('is_active', true)
                    ->firstOrFail();

        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= "FN:{$card->name}\r\n";
        $vcard .= "TITLE:{$card->position}\r\n";
        $vcard .= "ORG:{$card->company}\r\n";
        $vcard .= "EMAIL:{$card->email}\r\n";
        $vcard .= "TEL;TYPE=WORK:{$card->work_phone}\r\n";
        $vcard .= "TEL;TYPE=CELL:{$card->mobile}\r\n";
        $vcard .= "END:VCARD\r\n";

        return response($vcard)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', "attachment; filename={$card->slug}.vcf");
    })->name('card.vcard');

});