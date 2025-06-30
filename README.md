# QR コード生成アプリケーション

このアプリケーションは、Laravel フレームワークと SimpleSoftwareIO/QrCode パッケージを使用して、カスタマイズ可能な QR コードを生成する Web アプリケーションです。

<img width="954" alt="スクリーンショット 2025-06-30 12 35 39" src="https://github.com/user-attachments/assets/415ea0dd-0957-4fbe-bfea-2d5288222a0b" />

> [QR コードの仕組みについて詳しく知る](docs/qr-code-mechanism.md)

## アプリケーションの特徴

このアプリケーションでは、以下の 4 種類の QR コード生成が可能です：

1. **通常の QR コード**

    - シンプルな白黒の QR コード
    - 基本的な URL 埋め込み

2. **カスタム QR コード**

    - 丸みを帯びたデザイン
    - カスタムカラー（青）の使用
    - 円形のアイパターン

3. **デザイン QR コード**

    - ドットスタイルのパターン
    - グラデーションカラーの適用
    - カスタム背景色

4. **画像パターン QR コード**
    - カスタム画像を QR コードのパターンとして使用
    - Imagick を使用した高度な画像処理
    - パターンの明るさ・コントラスト調整

## 環境構築

### 必要要件

-   PHP 8.1 以上
-   Composer
-   Node.js & npm
-   Docker & Docker Compose（推奨）
-   ImageMagick PHP 拡張

### 実行コマンド

```bash
make build
```

これで http://localhost:8000 にアクセスできるようになります。

### 利用可能な make コマンド

-   `make up`: Docker コンテナを起動
-   `make down`: Docker コンテナを停止
-   `make restart`: Docker コンテナを再起動
-   `make logs`: コンテナのログを表示
-   `make shell`: アプリケーションコンテナの bash シェルに接続
-   `make migrate`: データベースマイグレーションを実行
-   `make migrate-fresh`: データベースを再作成してマイグレーションを実行
-   `make pint`: コードスタイルの修正を実行
-   `make clear`: キャッシュをクリア

## 技術的な仕組み

> [QR コードアプリケーションの技術的な仕組みについて詳しく知る](docs/technical-mechanism.md)
