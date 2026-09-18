<?php
$file = "upload/a53f2fdfdeb39046542ffc591d2998f8.pdf";
if (!unlink($file))
  {
  echo ("Error deleting $file");
  }
else
  {
  echo ("Deleted $file");
  }
?> 