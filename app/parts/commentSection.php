<?php
include("app/functions/comment_get.php");
?>

<section>
    <?php foreach ($comment_array as $comment) : ?>
        <!-- スレッドのidとコメントのthread_idが一致するとき -->
        <?php if ($thread["id"] == $comment["thread_id"]) : ?>
            <article>
                <div class="wrapper">
                    <div class="nameArea">
                        <span>スレッドID：</span>
                        <p class="threadId"><?php echo $comment["thread_id"]; ?></p>
                        <span>　名前：</span>
                        <p class="username"><?php echo $comment["username"]; ?></p>
                        <span>　性別：</span>
                        <p class="gender">
                            <?php
                            if ($comment["gender"] == 1) {
                                echo "男";
                            } else if ($comment["gender"] == 2) {
                                echo "女";
                            }
                            ?>
                        </p>
                        <time>：<?php echo $comment["post_date"]; ?></time>
                    </div>
                    <p class="comment"><?php echo $comment["body"]; ?></p>
                </div>
            </article>
        <?php endif; ?>
    <?php endforeach ?>
</section>