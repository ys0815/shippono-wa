# Deploy 先での統計情報自動更新確認手順

## 1. 基本的な動作確認

### A. cron サービスの起動確認

```bash
# Deploy先で実行
docker-compose ps | grep cron
# または
docker ps | grep cron
```

### B. 手動での統計情報更新テスト

```bash
# Deploy先で実行
docker-compose exec laravel.test php artisan stats:update
```

### C. 統計情報の表示確認

-   サイトトップページにアクセス
-   「統計情報」セクションの「最終更新」時刻を確認
-   数値が正しく表示されているか確認

## 2. cron ログの確認

### A. cron ログの確認

```bash
# Deploy先で実行
docker-compose exec cron tail -f /var/log/cron.log
```

### B. 8 時前後のログ確認

```bash
# 8時前後（例：7:58-8:05）に実行
docker-compose exec cron tail -n 20 /var/log/cron.log
```

## 3. フォールバック機能のテスト

### A. キャッシュをクリアしてテスト

```bash
# Deploy先で実行
docker-compose exec laravel.test php artisan cache:clear
```

### B. サイトトップページにアクセス

-   統計情報が自動更新されるか確認
-   「最終更新」時刻が現在時刻に更新されるか確認

## 4. 問題が発生した場合の対処

### A. cron サービスが起動しない場合

```bash
# cronサービスを手動起動
docker-compose up -d cron

# ログを確認
docker-compose logs cron
```

### B. 統計情報が更新されない場合

-   フォールバック機能により、ページアクセス時に自動更新される
-   24 時間以内であれば、手動で`php artisan stats:update`を実行

### C. タイムゾーンの問題

-   日本時間（JST）で正しく動作するよう設定済み
-   問題がある場合は、サーバーのタイムゾーン設定を確認

## 5. 定期確認

### A. 毎日 8 時後の確認

-   サイトトップページの「最終更新」時刻が 8 時頃になっているか確認
-   統計数値が正しく更新されているか確認

### B. 週次確認

-   cron ログにエラーがないか確認
-   統計情報の数値が妥当か確認
