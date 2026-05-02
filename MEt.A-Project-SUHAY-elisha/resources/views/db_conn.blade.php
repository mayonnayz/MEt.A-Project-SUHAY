<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection</title>
</head>
<body>
    <h2>Project SUHAY Proponents</h2>

    @foreach($proponents as $p)
        <h3>{{ $p['full_name'] }}</h3>
        <h4>{{ $p['role'] }}</h4>
    @endforeach

</body>
</html>