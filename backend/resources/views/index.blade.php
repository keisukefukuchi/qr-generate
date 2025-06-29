<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRコード生成</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Noto Sans JP', sans-serif;
        }

        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            padding: 40px 20px;
        }

        h1 {
            text-align: center;
            color: #2d3748;
            margin-bottom: 40px;
            font-size: 2.5rem;
        }

        .qr-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        .qr-item {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 380px;
        }

        .qr-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .qr-item h2 {
            color: #4a5568;
            margin-bottom: 20px;
            font-size: 1.25rem;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }

        .qr-item h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, #4299e1, #667eea);
            border-radius: 2px;
        }

        .qr-image {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }

            h1 {
                font-size: 2rem;
                margin-bottom: 30px;
            }

            .qr-container {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                padding: 10px;
            }

            .qr-item {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <h1>QRコードジェネレーター</h1>
    <div class="qr-container">
        <div class="qr-item">
            <h2>通常のQRコード</h2>
            <img src="{{ $normalQr }}" alt="通常のQRコード" class="qr-image">
        </div>
        <div class="qr-item">
            <h2>カスタムQRコード</h2>
            <img src="{{ $customQr }}" alt="カスタムQRコード" class="qr-image">
        </div>
        <div class="qr-item">
            <h2>デザインQRコード</h2>
            <img src="{{ $designQr }}" alt="デザインQRコード" class="qr-image">
        </div>
        <div class="qr-item">
            <h2>画像パターンQRコード</h2>
            <img src="{{ $imagePatternQr }}" alt="画像パターンQRコード" class="qr-image">
        </div>
    </div>
</body>
</html>