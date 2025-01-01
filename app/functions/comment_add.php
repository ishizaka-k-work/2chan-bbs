<?php
$error_message = array();

session_start();

if (isset($_POST["submitButton"])) {

    //お名前入力チェック
    if (empty($_POST["username"])) {
        $error_message["username"] = "お名前を入力してください。";
    } else {
        //エスケープ処理
        $escaped["username"] = htmlspecialchars($_POST["username"], ENT_QUOTES, "UTF-8");
        $_SESSION["username"] = $escaped["username"];
    }

    //性別チェック
    if (empty($_POST["gender"])) {
        $error_message["gender"] = "性別を入力してください。";
    } else {
        //エスケープ処理
        $_SESSION["gender"] = $_POST["gender"];
    }

    //コメント入力チェック
    if (empty($_POST["body"])) {
        $error_message["body"] = "コメントを入力してください。";
    } else {
        //エスケープ処理
        $escaped["body"] = htmlspecialchars($_POST["body"], ENT_QUOTES, "UTF-8");
    }
    if (empty($error_message)) {
        $post_date = date("Y-m-d H:i:s");

        //トランザクション開始
        $pdo->beginTransaction();

        try {
            $sql = "INSERT INTO `comment` (`username`, `gender`, `body`, `post_date`, `thread_id`) 
            VALUES (:username, :gender, :body, :post_date, :thread_id);";
            $statement = $pdo->prepare($sql);

            //値をセットする。
            $statement->bindParam(":username", $escaped["username"], PDO::PARAM_STR);
            $statement->bindParam(":gender", $_POST["gender"], PDO::PARAM_INT);
            $statement->bindParam(":body", $escaped["body"], PDO::PARAM_STR);
            $statement->bindParam(":post_date", $post_date, PDO::PARAM_STR);
            $statement->bindParam(":thread_id", $_POST["threadID"], PDO::PARAM_STR);

            $statement->execute();

            $pdo->commit();
        } catch (Exception $error) {
            $pdo->rollBack();
        }
    }
}

if (isset($_POST["aiButton"])) {

    // APIアクセスURL
    $url = "https://yesno.wtf/api";

    // ストリームコンテキストのオプションを作成
    $options = array(
        // HTTPコンテキストオプションをセット
        'http' => array(
            'method' => 'GET',
            'header' => 'Content-type: application/json; charset=UTF-8' //JSON形式で表示
        )
    );

    // ストリームコンテキストの作成
    $context = stream_context_create($options);
    $raw_data = file_get_contents($url, false, $context);

    // json の内容を連想配列として $data に格納する
    $yes_no_data = json_decode($raw_data, true);

    if (empty($error_message)) {
        $post_date = date("Y-m-d H:i:s");

        //トランザクション開始
        $pdo->beginTransaction();

        try {
            $sql = "INSERT INTO `comment` (`username`, `gender`, `body`, `post_date`, `thread_id`) 
            VALUES (:username, :gender, :body, :post_date, :thread_id);";
            $statement = $pdo->prepare($sql);

            //値をセットする。
            $username_YesNoAPI = "YesNoAPI";
            $gender_YesNoAPI = 2;
            $statement->bindParam(":username", $username_YesNoAPI);
            $statement->bindParam(":gender", $gender_YesNoAPI, PDO::PARAM_INT);
            $statement->bindParam(":body", $yes_no_data["answer"], PDO::PARAM_STR);
            $statement->bindParam(":post_date", $post_date, PDO::PARAM_STR);
            $statement->bindParam(":thread_id", $_POST["threadID"], PDO::PARAM_STR);

            $statement->execute();

            $pdo->commit();
        } catch (Exception $error) {
            echo "<script type='text/javascript'>alert('失敗です！');</script>";
            $pdo->rollBack();
        }
    }
}
