<?php

if (isset($_POST['checkbox']) && is_array($_POST['checkbox'])) { echo implode(' ', $_POST['checkbox']); }

?>