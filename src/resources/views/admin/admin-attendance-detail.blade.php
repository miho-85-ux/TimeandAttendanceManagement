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

    @php
        $isPending = $attendance->attendanceRequests->contains('status', 'pending');
    @endphp

    @if($isPending)
        <p class="error">承認待ちのため修正はできません。</p>
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
                            <input class="table-input" type="text" name="check_in" value="{{ old('check_in', $attendance->check_in_time) }}" @if($isPending)disabled @endif > 
                            <span class="table-span">~</span>
                            <input class="table-input" type="text" name="check_out" value="{{ old('check_out', $attendance->check_out_time) }}" @if($isPending)disabled @endif >
                            @error('check_in')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            @error('check_out')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    @foreach($attendance->breaktimes as $breaktime)
                        <tr class="table-line">
                            <th class="table-title">
                                @if($loop->first)
                                    休憩
                                @else
                                    休憩{{ $loop->iteration }}
                                @endif
                            </th>
                            <td class="table-item">
                                <input class="table-input" type="text" name="break_start[]" value="{{ old('break_start.' . $loop->index, $breaktime->break_start_time) }}" @if($isPending) disabled @endif>
                                <span class="table-span">~</span>
                                <input class="table-input" type="text" name="break_end[]" value="{{ old('break_end.' . $loop->index, $breaktime->break_end_time) }}" @if($isPending) disabled @endif>
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
                            @if($attendance->breaktimes->count() == 0 )
                                休憩
                            @else
                                休憩{{ $attendance->breaktimes->count() + 1 }}
                            @endif
                        </th>
                        <td class="table-item">
                            <input class="table-input" type="text" name="break_start[]" value="{{ old('break_start.' . $newIndex) }}" @if($isPending) disabled @endif>
                            <span class="table-span">~</span>
                            <input class="table-input" type="text" name="break_end[]" value="{{ old('break_end.' . $newIndex) }}" @if($isPending) disabled @endif>
                        </td>
                    </tr>
                    <tr class="table-line">
                        <th class="table-title">備考</th>
                        <td class="table-item">
                            <textarea class="table-textarea" name="remarks" cols="50" rows="3"  @if($isPending)disabled @endif >{{ old('remarks', $attendance->remarks) }}</textarea>
                            @error('remarks')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                </table>
            </div>
            <div class="buttons">
                <button class="button-submit" type="submit" @if($isPending)disabled @endif>修正</button>
            </div>
    </form>
</div>
@endsection