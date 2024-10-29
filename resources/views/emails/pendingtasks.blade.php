<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Tasks Email</title>
</head>
<body>
    @component('mail::message')
    # Your Pending Tasks for Today

    Here are your pending tasks for today:

    <ul>
        @foreach ($tasks as $task)
            <li>
                <strong>{{ $task->title }}</strong>: {{ $task->description ?? 'No description' }} (Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }})
            </li>
        @endforeach
    </ul>

    Thanks,<br>
    {{ config('app.name') }}
    @endcomponent
</body>
</html>
