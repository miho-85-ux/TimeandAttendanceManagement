@extends('layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
@endsection

@section('content')
<div class="attendance-detail__content">
    <h1 class="attendance-detail__title">勤怠詳細</h1>
    <form action="{{ route('attendance.update',  $attendance -> id) }}" method="POST">
        @method('PATCH')
        @csrf 
        @if($attendance->status === 'pending')
        <!-- 表示モード -->
            <div class="attendance-detail__full-table" >
                <table class="attendance-detail__table">
                    
                    <tr class="table-line">
                        <th class="table-title">名前</th>
                        <td class="table-item"> {{ $attendance->user->name }}</td>
                    </tr>
                    <tr class="table-line">
                        <th class="table-title">日付</th>
                        <td class="table-item">{{ $attendance->detail_date }}</td>
                    </tr>
                    <tr class="table-line">
                        <th class="table-title">出勤・退勤</th>
                        <td class="approval__table-item ">
                            <p class="approval__table-text"> {{ $attendance->check_in_time }}</p> 
                            <span class="approval__table-span">~</span>
                            <p class="approval__table-text">{{ $attendance->check_out_time }}</p>
                        </td>
                    </tr>
                    @foreach ($attendance->breaktimes as $breaktime)
                        <tr class="table-line">
                            <th class="table-title">
                                @if($loop->first)
                                    休憩
                                @else
                                    休憩{{ $loop->iteration }}
                                @endif
                            </th>
                            <td class="approval__table-item ">
                                <p class="approval__table-text"> {{ $breaktime->break_start_time }}</p> 
                                <span class="approval__table-span">~</span>
                                <p class="approval__table-text">{{ $breaktime->break_end_time }}</p>
                            </td>
                        </tr>
                    @endforeach
                    
                    <tr >
                        <th class="table-title">備考</th>
                        <td class="approval__table-item ">
                            <p class="approval__table-textarea">{{ $attendance->remarks }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="button">
                <p class="approval">*承認待ちのため修正はできません。</p>
            </div>
        @else
        <!-- 編集モード -->
            <div class="attendance-detail__full-table" >
                <table class="attendance-detail__table">
                    <tr class="table-line">
                        <th class="table-title">名前</th>
                        <td class="table-item"> {{ $attendance->user->name }}</td>
                    </tr>
                    <tr class="table-line">
                        <th class="table-title">日付</th>
                        <td class="table-item">{{ $attendance->detail_date }}</td>
                    </tr>
                    <tr class="table-line">
                        <th class="table-title">出勤・退勤</th>
                        <td class="table-item">
                            <input class="table-input" type="text" name="check_in" value="{{ $attendance->check_in_time }}"> 
                            <span class="table-span">~</span>
                            <input class="table-input" type="text" name="check_out" value="{{ $attendance->check_out_time }}">
                            @error('check_in')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            @error('check_out')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            @foreach ($attendance->breaktimes as $breaktime)
                        </td>
                    </tr>
                        <tr class="table-line">
                            <th class="table-title">
                                @if($loop->first)
                                    休憩
                                @else
                                    休憩{{ $loop->iteration }}
                                @endif
                            </th>
                            <td class="table-item">
                                <input class="table-input" type="text" name="break_start[]" value="{{  old('break_start.' . $loop->index, $breaktime->break_start_time) }}"> 
                                <span class="table-span">~</span>
                                <input class="table-input" type="text" name="break_end[]" value="{{  old('break_end.' . $loop->index, $breaktime->break_end_time) }}">
                                @error('break_start.' . $loop->index)
                                    <p class="error">{{ $message }}</p>
                                @enderror   
                                @error('break_end.' . $loop->index)
                                    <p class="error">{{ $message }}</p>
                                @enderror   
                            </td>
                        </tr>
                    @endforeach
                    @php
                        $newIndex = $attendance->breaktimes->count();
                    @endphp
                    <tr class="table-line">
                        <th class="table-title">
                            @if($attendance->breaktimes->count() == 0)
                                休憩
                            @else
                                休憩{{ $attendance->breaktimes->count() + 1 }}
                            @endif
                        </th>
                        <td class="table-item">
                            <input class="table-input" type="text" name="break_start[]" value="{{ old('break_start.' . $newIndex) }}"> 
                            <span class="table-span">~</span>
                            <input class="table-input" type="text" name="break_end[]" value="{{ old('break_end.' . $newIndex) }}">
                            @error('break_start.' . $newIndex)
                                <p class="error">{{ $message }}</p>
                            @enderror
                            @error('break_end.' . $newIndex)
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>                       
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
        @endif
    </form>
</div>
@endsection