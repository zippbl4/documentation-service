<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Визуальный Diff</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    .container {
      display: flex;
      gap: 20px;
    }
    .article {
      width: 100%;
      border: 1px solid #ccc;
      padding: 10px;
      overflow-y: auto;
      #height: 300px;
      background-color: #f9f9f9;
    }
    .diff {
      margin-top: 20px;
      padding: 10px;
      background-color: #fff9e6;
      border: 1px solid #ccc;
    }
    del {
      background-color: #ffcccc;
      text-decoration: none;
    }
    ins {
      background-color: #ccffcc;
      text-decoration: none;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="article">
    <p>{!! $from !!}</p>
  </div>
  <div class="article">
    <p>{!! $to !!}</p>
  </div>
</div>
<div class="diff">
  <p>{!! $diff !!}</p>
</div>
</body>
</html>
