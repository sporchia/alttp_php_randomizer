@extends('layouts.default', ['title' => __('navigation.daily') . ' - '])

@section('content')
<h3>{{ __('daily.header') }}</h3>
<div class="card card-body bg-light">
    @foreach (__('daily.content') as $block)
        <p>{!! $block !!}</p>
    @endforeach
</div>

<Hashloader current_rom_hash="{{ $md5 }}" override-base-bps="{{ $bpsLocation }}" hash="{{ $hash }}"></Hashloader>
@overwrite
