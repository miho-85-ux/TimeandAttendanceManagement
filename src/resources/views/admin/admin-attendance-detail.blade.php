@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
@endsection

@section('content')
<div class="attendance-detail__content">
    <h1 class="attendance-detail__title">勤怠詳細</h1>
    @if(session('message'))
    <p class="success-message">
        {{ session('message') }}
    </p>
    @endif
    <form action="{{ route('admin.update', $attendance->id) }}" method="POST">
        @method('PATCH')
        @csrf 
            <div class="attendance-detail__full-table" >
                <table class="attendance-detail__table">
                    
                    <tr class="table-line">
                        <th class="table-title">名前</th>
                        <td class="table-item">{{ $attendance->user->name }} </td>
                    </tr>
                    <tr class="table-line">
                        <th class="table-title">日付</th>
                        <td class="table-item"> {{ $attendance->detail_date }}</td>
                    </tr>
                    <tr class="table-line">
                        <th class="table-title">出勤・退勤</th>
                        <td class="table-item">
                            <input class="table-input" type="text" name="check_in" value="{{ old('check_in', $attendance->check_in_time) }}"> 
                            <span class="table-span">~</span>
                            <input class="table-input" type="text" name="check_out" value="{{ old('check_out', $attendance->check_out_time) }}">
                            @error('check_in')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            @error('check_out')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr class="table-line">
                        <th class="table-title">休憩</th>
                        <td class="table-item"></td>
                    </tr>
                    
                    
                    <tr >
                        <th class="table-title">備考</th>
                        <td class="table-item">
                            <textarea class="table-textarea" name="remarks" cols="50" rows="3">{{ old('remarks', $attendance->remarks) }}</textarea>
                            @error('remarks')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                </table>
            </div>
            <div class="button">
                <button class="button-submit" type="submit">修正</button>
            </div>
    </form>
</div>
@endsection