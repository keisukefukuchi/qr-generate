# QR コードアプリケーションの技術的な仕組み

## 使用ライブラリ

このアプリケーションは、以下の 2 つの主要なライブラリを使用して QR コードを生成しています：

1. **SimpleSoftwareIO/QrCode**

    - Laravel 向けの QR コード生成ファサード
    - 内部的に`bacon/bacon-qr-code`パッケージを使用
    - 主な機能：
        - QR コードのサイズ、マージン設定
        - エラー訂正レベルの制御（L:7%, M:15%, Q:25%, H:30%）
        - 出力フォーマット（SVG、PNG、EPS）の指定
        - スタイル（square、dot、round）の設定
        - 色やグラデーションのカスタマイズ

2. **Imagick（ImageMagick）**
    - PHP の画像処理拡張モジュール
    - QR コードの高度な画像処理に使用
    - 主な処理：
        - QR コード画像の読み込みと変換
        - 画像のリサイズと合成
        - 明るさ・コントラストの調整
        - マスク処理による画像パターンの適用

## QR コード生成の詳細な仕組み

SimpleSoftwareIO/QrCode パッケージは、以下のような多層構造で QR コードを生成しています：

1. **データエンコーディング層**

    - 入力データを指定された文字エンコーディング（デフォルト：ISO-8859-1）に変換
    - UTF-8、SHIFT-JIS、ASCII など多様な文字エンコーディングをサポート
    - エンコードされたデータをバイトストリームに変換

    ```php
    // 内部実装例（Generator.php）
    public function encoding(string $encoding): self
    {
        $this->encoding = strtoupper($encoding);
        return $this;
    }

    // 実際の使用例
    QrCode::encoding('UTF-8')->generate('こんにちは！');
    ```

2. **エラー訂正層**

    - Reed-Solomon エラー訂正アルゴリズムを使用
    - 4 段階のエラー訂正レベルをサポート：
        - L (Low): 7%のデータを復元可能
        - M (Medium): 15%のデータを復元可能
        - Q (Quartile): 25%のデータを復元可能
        - H (High): 30%のデータを復元可能

    ```php
    // 内部実装例（Generator.php）
    public function errorCorrection(string $errorCorrection): self
    {
        $errorCorrection = strtoupper($errorCorrection);
        $this->errorCorrection = ErrorCorrectionLevel::$errorCorrection();
        return $this;
    }

    // 実際の使用例
    QrCode::errorCorrection('H')->generate('高い修復性能が必要なデータ');
    ```

3. **マトリックス生成層**

    - データとエラー訂正コードを QR コードのマトリックスに配置
    - 位置検出パターン、タイミングパターン、位置合わせパターンを配置
    - マスクパターンの適用によるデータの最適化

    ```php
    // 内部実装例（Generator.php）
    public function getWriter(ImageRenderer $renderer): Writer
    {
        return new Writer($renderer);
    }

    public function generate(string $text, string $filename = null)
    {
        $qrCode = $this->getWriter($this->getRenderer())
                      ->writeString($text, $this->encoding, $this->errorCorrection);
        // ...
    }
    ```

4. **レンダリング層**

    - 3 種類の出力フォーマットをサポート：
        - SVG: ベクター形式での出力（デフォルト）
        - PNG: ラスター形式での出力
        - EPS: 印刷用フォーマットでの出力
    - カスタマイズ可能な要素：
        - サイズ（ピクセル単位）
        - マージン
        - スタイル（square、dot、round）
        - カラー（前景色・背景色）
        - グラデーション

    ```php
    // 内部実装例（Generator.php）
    public function format(string $format): self
    {
        if (!in_array($format, ['svg', 'eps', 'png'])) {
            throw new InvalidArgumentException("$format is not a valid format.");
        }
        $this->format = $format;
        return $this;
    }

    public function color(int $red, int $green, int $blue, ?int $alpha = null): self
    {
        $this->color = $this->createColor($red, $green, $blue, $alpha);
        return $this;
    }

    // 実際の使用例
    QrCode::format('png')
          ->size(300)
          ->color(255, 0, 0)
          ->style('dot')
          ->generate('スタイリッシュなQRコード');
    ```

5. **画像処理層**

    - 画像の合成機能（merge）をサポート
    - PNG 形式の画像を QR コードの中心に配置可能
    - 画像サイズの自動調整
    - 透明度の制御

    ```php
    // 内部実装例（ImageMerge.php）
    public function merge($percentage)
    {
        $this->setProperties($percentage);
        $img = imagecreatetruecolor($this->sourceImage->getWidth(), $this->sourceImage->getHeight());
        imagealphablending($img, true);

        // 画像の合成処理
        imagecopyresampled(
            $img,
            $this->mergeImage->getImageResource(),
            $this->centerX,
            $this->centerY,
            0,
            0,
            $this->postMergeImageWidth,
            $this->postMergeImageHeight,
            $this->mergeImageWidth,
            $this->mergeImageHeight
        );

        return $this->createImage();
    }

    // 実際の使用例
    QrCode::format('png')
          ->merge('path-to-logo.png', 0.3)
          ->generate('ロゴ入りQRコード');
    ```

これらの層が連携することで、高いカスタマイズ性と信頼性を持つ QR コードの生成を実現しています。各層は疎結合な設計となっており、必要に応じて個別の機能を拡張することも可能です。

## QR コード生成プロセス

1. **基本的な QR コード生成**

    - データのエンコード（UTF-8）
    - ビット配列への変換
    - エラー訂正コードの生成
    - QR コードマトリックスの作成

2. **画像パターン処理**
    - 基本 QR コードの生成
    - パターン画像の読み込みとリサイズ
    - 画像の明るさ・コントラスト調整
    - マスク処理による画像の合成
