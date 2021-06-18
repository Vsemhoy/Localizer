<?php
// DATABASE CONNECTION
$host =    Config::$HS;
$db =      Config::$DB;
$charset = 'utf8mb4';
$user =    Config::$US;
$pass =    Config::$PS;

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     $db_errmsg = new \PDOException($e->getMessage());
     $db_errno  = (int)$e->getCode();
}
unset($host);
unset($db);
unset($charset);
unset($user);
unset($pass);
/*
$stmt = $pdo->query('SELECT * FROM TEST');
while ($row = $stmt->fetch())
{
    echo $row['name'] . "<br>";
    echo $row['number'] . "<br>";
    echo $row['pop'] . "<br>";
}
1045 - user wrong  / no pass
2002 - no host
1049 - no database

*/
?>