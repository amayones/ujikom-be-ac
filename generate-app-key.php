<?php
// Generate Laravel APP_KEY
$key = 'base64:' . base64_encode(random_bytes(32));
echo "Add this to your task-definition.json:\n";
echo "{\n";
echo '  "name": "APP_KEY",'."\n";
echo '  "value": "'.$key.'"'."\n";
echo "}\n";
?>