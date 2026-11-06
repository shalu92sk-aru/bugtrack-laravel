@extends('layouts.app')
@section('title','Sign in')
@section('guest')
<div class="login-wrap"><div class="card login-card"><div class="text-center mb-4"><div class="text-primary fs-1"><i class="bi bi-bug-fill"></i></div><h3 class="fw-bold">Welcome to BugTrack</h3><p class="muted">Sign in to manage your team's issues</p></div>
@if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
<form method="POST" action="{{route('login.submit')}}">@csrf<div class="mb-3"><label class="form-label">Email address</label><input type="email" name="email" class="form-control" value="{{old('email')}}" required autofocus></div><div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div><div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label for="remember" class="form-check-label">Remember me</label></div><button class="btn btn-primary w-100 py-2">Sign in</button></form><hr><small class="muted">Demo: admin@bugtrack.test / ChangeMe123!</small></div></div>
@endsection
