<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Holiday Plan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #636b6f; }
        h1, b { color: #636b6f; }
        .details { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>{{ $holidayPlan->title }}</h1>
    <p><b>Date:</b> {{ $holidayPlan->date }}</p>
    <p><b>Location:</b> {{ $holidayPlan->location }}</p>

    <div class="details">
        <h3>Description:</h6>
        <p>{{ $holidayPlan->description }}</p>
    </div>
    @if(isset($holidayPlan->participants) && !empty($holidayPlan->participants))
        <h4>Participants:</h4>
        <ul>
            @foreach($holidayPlan->participants as $participant)
            <li>{{ $participant }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
