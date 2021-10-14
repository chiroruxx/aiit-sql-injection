<?php
$query = 'SELECT * FROM products';

if (isset($_GET['name'])) {
    // プリペアードステートメントを使用するべき
    $query .= " WHERE name LIKE '%{$_GET['name']}%'";
}

// root ユーザでログインしないべき
// ユーザ名・パスワードをコード上に残さないべき
$mysqli = mysqli_connect('db', 'root', 'password', 'test');
$result = mysqli_query(
    $mysqli,
    $query
);

// エラーの内容から情報が漏れるので、エラーは表示させないべき
if ($result === false) {
    echo mysqli_error($mysqli) . '<br>';
}

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
$keys = isset($items[0]) ? array_keys($items[0]) : [];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <title>SQLインジェクションテスト</title>
</head>
<body>
<form>
    製品名で絞り込みができます。<br>
    <label>
        製品名:
        <input type="text" name="name" required="required">
    </label>
    <input type="submit" value="検索する">
</form>

<table>
    <tr>
        <?php foreach ($keys as $key): ?>
            <th><?= $key ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($items as $itemLine): ?>
    <tr>
        <?php foreach ($itemLine as $item): ?>
        <td><?= $item ?></td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
