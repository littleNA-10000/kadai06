<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <title>投票機能</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>
    <?php
    //デバッグ用にJSのconsole.log的関数を設定
    function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
    }

    function h ($value) {
        return htmlspecialchars($value, ENT_QUOTES);
    }

    //***受け取り用
    $star = h($_POST["star"]);
    $text = h($_POST["text"]);
    $username = h($_POST["username"]);
    
    // １．コメントが空欄の場合は星の評価のみ配列に格納、２．コメントがあって名前がないときは名前を「匿名さん」に設定、３．名前・コメントともにあればそのまま格納。
    if ($text == "") {
        $review = array("star"=>$star, "username"=>"", "text"=>"");
    } elseif ($username == "") {
        $review = array("star"=>$star, "username"=>"匿名さん", "text"=>$text);
    } else {
        $review = array("star"=>$star, "username"=>$username, "text"=>$text);
    }
    // console_log( $review);

    // 書き出し用にJSON化。
    $json = json_encode( $review ) ;

    // DB用ファイル名
    $filename = 'review.txt';

    // ファイルに書き込む（星の評価（必須項目）がNullの場合は書き込まない）
    if(!IS_NULL($star)){
    $file = fopen($filename, "a");
    fwrite( $file, $json."\n");
    fclose($file);
    }

    // ファイルから読み込み。配列（配列の要素は連想配列）json_decoとして全内容読み込ませる。
    $fp = fopen($filename, 'r');
    while (!feof($fp)) {
        // 配列に格納する際に、最後の改行（\n）が不要なので削る。
        $txt = str_replace("\n","",fgets($fp));
        // JSONをデコード。
        $json_deco[] = json_decode($txt,true);
    }
    fclose($fp);
    // console_log($json_deco);

    // 配列数をカウント
    $x = count($json_deco);

    // 票数カウント用変数
    $star_count5 = 0;
    $star_count4 = 0;
    $star_count3 = 0;
    $star_count2 = 0;
    $star_count1 = 0;
    ?>

    <!-- ブラウザの画面に見える内容 -->
    <div id="wrapper">
        <h1>みんなで楽しむ音楽レビューサイト</h1>
        <div id="vote">

            <!-- 投票の対象 -->
            <div id="vote_for_what">
                <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/rgWSUylxBeI"
                    frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
            <!-- 投票部分 -->
            <div id="vote_review">
            <!-- レビュー結果がすぐに見られるよう、自分自身をとび先にしてみた。 -->
                <form method="post" action="index.php">
                    <p>この動画の評価は？（必須）<br>
                        <input type="radio" name="star" value="5">星5つ
                        <input type="radio" name="star" value="4">星4つ
                        <input type="radio" name="star" value="3">星3つ
                        <input type="radio" name="star" value="2">星2つ
                        <input type="radio" name="star" value="1">星1つ
                    </p>
                    <p>コメント<br>
                        <textarea name="text" id="text" cols="50" rows="5"></textarea>
                    </p>
                    <p>
                        名前<br>
                        <input type="text" name="username" id="username">
                    </p>
                    <p>
                        <!-- <button id="send">送信</button> -->
                        <input type="submit" value="送信">
                    </p>
                </form>

            </div>
        </div>
               <h2>みんなのレビュー</h2>
        <div id="review">
            <!-- 投票結果が表示される箇所 -->
            <div class="starbox">
                <div class="star_count">
                    <div class="starimg_box">
                        <img src="imgs/star5.png" alt="" class="starimg">
                    </div>
                    <div id="star_count5">
                    <!-- 星５の票数をカウント（星４以下も同様） -->
                    <?
                    for($i = 0; $i < $x; $i++){
                        if($json_deco[$i]["star"] == "5"){
                            $star_count5++;
                        }
                    }
                    echo $star_count5."票";
                    ?>
                    </div>
                </div>
                <div id="star_review5">
                <!-- 星５のレビュー（名前とコメント）を表示（星４以下も同様） -->
                <?
                for($i = $x-1; $i >= 0; $i--) {
                    if($json_deco[$i]["star"] == "5" && $json_deco[$i]["text"] != ""){
                        echo '<div class="review_name">'.$json_deco[$i]["username"].'</div><div class="review_content">'.$json_deco[$i]["text"].'</div>';
                    }
                }
                ?>
                </div>
            </div>
            <div class="starbox">
                <div class="star_count">
                    <div class="starimg_box">
                        <img src="imgs/star4.png" alt="" class="starimg">
                    </div>
                    <div id="star_count4">
                    <?
                    for($i = 0; $i < $x; $i++){
                        if($json_deco[$i]["star"] == "4"){
                            $star_count4++;
                        }
                    }
                    echo $star_count4."票";
                    ?>
                    </div>
                </div>
                <div id="star_review4">
                <?
                for($i = $x-1; $i >= 0; $i--) {
                    if($json_deco[$i]["star"] == "4" && $json_deco[$i]["text"] != ""){
                        echo '<div class="review_name">'.$json_deco[$i]["username"].'</div><div class="review_content">'.$json_deco[$i]["text"].'</div>';
                    }
                }
                ?>
                </div>
            </div>
            <div class="starbox">
                <div class="star_count">
                    <div class="starimg_box">
                        <img src="imgs/star3.png" alt="" class="starimg">
                    </div>
                    <div id="star_count3">
                    <?
                    for($i = 0; $i < $x; $i++){
                        if($json_deco[$i]["star"] == "3"){
                            $star_count3++;
                        }
                    }
                    echo $star_count3."票";
                    ?>
                    </div>
                </div>
                <div id="star_review3">
                <?
                for($i = $x-1; $i >= 0; $i--) {
                    if($json_deco[$i]["star"] == "3" && $json_deco[$i]["text"] != ""){
                        echo '<div class="review_name">'.$json_deco[$i]["username"].'</div><div class="review_content">'.$json_deco[$i]["text"].'</div>';
                    }
                }
                ?>
                </div>
            </div>
            <div class="starbox">
                <div class="star_count">
                    <div class="starimg_box">
                        <img src="imgs/star2.png" alt="" class="starimg">
                    </div>
                    <div id="star_count2">
                    <?
                    for($i = 0; $i < $x; $i++){
                        if($json_deco[$i]["star"] == "2"){
                            $star_count2++;
                        }
                    }
                    echo $star_count2."票";
                    ?>
                    </div>
                </div>
                <div id="star_review2">
                <?
                for($i = $x-1; $i >= 0; $i--) {
                    if($json_deco[$i]["star"] == "2" && $json_deco[$i]["text"] != ""){
                        echo '<div class="review_name">'.$json_deco[$i]["username"].'</div><div class="review_content">'.$json_deco[$i]["text"].'</div>';
                    }
                }
                ?>
                </div>
            </div>
            <div class="starbpx">
                <div class="star_count">
                    <div class="starimg_box">
                        <img src="imgs/star1.png" alt="" class="starimg">
                    </div>
                    <div id="star_count1">
                    <?
                    for($i = 0; $i < $x; $i++){
                        if($json_deco[$i]["star"] == "1"){
                            $star_count1++;
                        }
                    }
                    echo $star_count1."票";
                    ?>
                    </div>
                </div>
                <div id="star_review1">
                <?
                for($i = $x-1; $i >= 0; $i--) {
                    if($json_deco[$i]["star"] == "1" && $json_deco[$i]["text"] != ""){
                        echo '<div class="review_name">'.$json_deco[$i]["username"].'</div><div class="review_content">'.$json_deco[$i]["text"].'</div>';
                    }
                }
                ?>
                </div>
            </div>
        </div>
    </div>　　　　
    <!--/ ブラウザの画面に見える内容 -->

</body>

</html>