@extends('layouts.frontend.app')

@section('title', 'Shopping Now')

@section('content')
<style>
    form .form2 {
        box-shadow: 0px 0px 5px gainsboro;
        padding: 20px;
        border-radius: 5px;
        margin-top: 50px;
    }
</style>
<div class="wrapper">

    <form class="col-md-4 offset-md-4" action="{{route('password.send')}}" method="post">
        @csrf
        <div class="form form2">
            <h4 class="text-[#002f5f] text-left py-[10px] px-0"><b>Forgot Password </b></h4>
            <input type="hidden" value="Email" name="method">
            <div class="form-group">
                <label> Email <sup class="text-[red]">*</sup></label>
                <input type="email" name="username" id="username"
                    class="form-control @error('username') is-in-valid @enderror" required />
                @error('username')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <input class="form-control" type="submit" value="Submit">
            <br>
        </div>
    </form>
    <br>
    <div class="text-center py-3 text-bold">
        @if (setting('recovrAC') == "emailsms" || setting('recovrAC') == "sms")
            <a class="btn btn-info" href="{{route('password.recover.mobile')}}">Recover by phone {{ config('app.name') }}</a><br>
        @endif
        <a href="{{route('login')}}">Go Back to Sign In</a>
    </div>
</div>

@endsection
