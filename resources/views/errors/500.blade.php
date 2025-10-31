@extends('errors.layout')

@section('title', 'Kesalahan Server')
@section('code', '500')
@section('message-title', 'Terjadi Kesalahan')
@section('message-body', 'Maaf, terjadi kesalahan pada server. Silakan coba lagi nanti.')
@section('image', asset('assets/image/500-error.svg'))