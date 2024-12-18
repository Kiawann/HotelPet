@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <P>admin</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
        
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
    </div>
@endsection
