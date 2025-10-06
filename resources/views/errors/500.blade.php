@extends('errors.layout')

@section('title', '500 - Kesalahan Server')
@section('code', '500')
@section('color', '#dc2626')
@section('image', 'https://illustrations.popsy.co/gray/server-down.svg')
@section('message-title', 'Terjadi Kesalahan di Server')
@section('message-body', 'Terjadi kesalahan internal di server kami. Silakan coba beberapa saat lagi.')
@section('body-style', 'background: linear-gradient(135deg, #fee2e2, #fecaca); color:#7f1d1d;')
