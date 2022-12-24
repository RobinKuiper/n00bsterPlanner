<?php

for($i = 0; $i < 20; $i++){
    echo password_hash('test@', PASSWORD_DEFAULT) . "\n";
}
