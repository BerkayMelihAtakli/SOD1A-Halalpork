<?php
// Database connectie voor MAMP/XAMPP.
// MAMP op Mac gebruikt meestal gebruiker root en wachtwoord root.
try
{
    $db = new PDO(
        'mysql:host=localhost;dbname=halalpork;charset=utf8mb4',
        'root',
        'root'
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch(PDOException $e)
{
    // Fallback voor XAMPP/andere installaties waar root geen wachtwoord heeft.
    try
    {
        $db = new PDO(
            'mysql:host=localhost;dbname=halalpork;charset=utf8mb4',
            'root',
            ''
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
        $sMsg = '<p>
                Regelnummer: '.$e->getLine().'<br />
                Bestand: '.$e->getFile().'<br />
                Foutmelding: '.$e->getMessage().'
            </p>';
        trigger_error($sMsg);
    }
}
?>
