<?php
require 'config.php';

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

$stmt = $pdo->prepare("SELECT * FROM journals WHERE title LIKE ?");
$stmt->execute(['%' . $searchQuery . '%']);
$journals = $stmt->fetchAll(PDO::FETCH_ASSOC);

$authorsStmt = $pdo->query("SELECT * FROM authors");
$authors = $authorsStmt->fetchAll(PDO::FETCH_ASSOC);
$authorsById = [];
foreach ($authors as $author) {
    $authorsById[$author['id']] = $author;
}

$result = [];
foreach ($journals as $journal) {
    $authorNames = [];
    $authorIds = json_decode($journal['author_ids']);
    foreach ($authorIds as $authorId) {
        $authorNames[] = $authorsById[$authorId]['last_name'] . ' ' . $authorsById[$authorId]['first_name'];
    }

    $result[] = [
        'id' => $journal['id'],
        'title' => $journal['title'],
        'short_description' => $journal['short_description'],
        'release_date' => $journal['release_date'],
        'authors' => implode(', ', $authorNames)
    ];
}

header('Content-Type: application/json');
echo json_encode($result);
