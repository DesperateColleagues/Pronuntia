<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-5">Elenco test autovalutazione</h1>

        <p class="lead">I seguenti test sono progettati per permettere di capire se è necessario l'aiuto di un
            logopedista per correggere difetti di pronuncia e patologie assimilabili.</p>



        <?php

        echo "<table style='width:100%'><tr>";
        echo "<td>Test 1</td><td>";
        echo Html::a('Download', [Yii::$app->urlManager->createUrl(['site/download', 'path' => '/testautovalutazione/', 'file' => 'TestAutovalutazionePronuntia.docx'])], ['class' => 'btn btn-outline-primary']);
        echo "</td>";
        echo "</tr></table>";
        ?>

    </div>

    <div>
        <?php
            echo Html::a('Torna alla home', ['index'], ['class' => 'btn btn-outline-secondary']);
        ?>
    </div>
</div>