<?php
include "config.php";

$tables = ['users', 'energy_usage', 'appliance_usage', 'feedback', 'contact_messages'];

foreach ($tables as $table) {
    echo "Altering $table...\n";
    $sql = "ALTER TABLE $table ADD COLUMN IF NOT EXISTS is_deleted TINYINT(1) DEFAULT 0";
    if ($conn->query($sql) === TRUE) {
        echo "$table altered successfully.\n";
    } else {
        echo "Error altering $table: " . $conn->error . "\n";
    }
}
?>
