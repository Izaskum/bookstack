@extends('layouts.simple')


@section('body')
<style>
    

    .header-1 {
        display: block;
        font-size: 2em;
        margin-block-start: 0.67em;
        margin-block-end: 0.67em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
        margin: 0px;
    }

    .header-2 {
        display: block;
        font-size: 1.5em;
        margin-block-start: 0.83em;
        margin-block-end: 0.83em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
        margin: 0px;
    }

    .certificate-frame {
        width: 700px;
        height: 450px;
        border: 1px solid black;
        box-shadow: 5px 10px 8px #888888;
        margin: auto auto;
        padding: 50px;
        background-image: url("{{ asset('images/certificate-background.png') }}");
        background-size: cover;
        background-repeat: no-repeat;
    }

    .certificate-panel {
        text-align: center;
        padding-top: 50px;
    }

    .student-name {
        font-family: 'yellowtailregular';
        font-size: 32px;
        color: #ff5722;
    }

    .lecturer-name {
        font-family: 'yellowtailregular';
        font-size: 24px;
        color: #2196f3;
        margin: 0px;
        padding: 0px;
    }
</style>
<div class="certificate-frame">
    <div class="certificate-panel">
        <div>
            <p class="header-1">This is a Certificate that</p>
            <p class="student-name header-2">{{ $student->name }}</p>
            <p class="header-2">Has passed {{ $exam->course->subject }} Course Exam with percent:
                <stong style="color: #009688;">{{ $percent }}%</stong></p>
        </div>

        <div>
            <div>
                Provided by: KMS System
            </div>
            <div style="float: left;">
                Lecturer
                <p class="lecturer-name">
                    <strong>{{ $exam->course->lecturer->name }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection