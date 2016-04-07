<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$variablename = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Do stuff
        }


?>


<div class="mdl-grid demo-content">
<?php
// Check and see if we've saved the settings. If we have, tell the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
echo "  <div class='mdl-card__title mdl-card--expand mdl-color--red-300 mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>
    <h2 class='mdl-card__title-text'>Settings have been saved</h2>
  </div>";
}
?>


 <div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
  </div>
</div>




 </div>
</div>
<?php
include 'tpl/footer.php';
?>
