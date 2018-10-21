<?php
    session_start();
    require_once("./inc/top_layout.php");
?>

    <div id="blackouts-per-rotation">
        <h2 id="blackouts-entered-title">Blackouts Entered Per Congregation</h2>
        <div class="table-responsive">
        </div>
    </div>
    <div class="loader"></div>
<?php
    require_once("./inc/bottom_layout.php");
?>