<?php
include 'db.php';

$slug = $_GET['slug'];
$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE slug=?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    echo "Post not found.";
    exit;
}

echo "<div class='container mt-5'>";
echo "<h1>{$post['title']}</h1>";
if ($post['image']) echo "<img src='{$post['image']}' class='img-fluid mb-3'>";
echo "<p class='text-muted'>By {$post['author']} | {$post['created_at']}</p>";
echo "<div>{$post['content']}</div>";
echo "</div>";

// Comments section
echo "<div class='container mt-5'>";
echo "<h3>Comments</h3><div class='list-group'>";
$comments = $conn->prepare("SELECT * FROM comments WHERE post_id=? AND approved=1 ORDER BY created_at DESC");
$comments->bind_param("i", $post['id']);
$comments->execute();
$res = $comments->get_result();
while ($c = $res->fetch_assoc()) {
    echo "<div class='list-group-item'><strong>{$c['name']}</strong><br>{$c['comment']}</div>";
}
echo "</div>";

// Comment form
echo <<<FORM
<h4 class='mt-4'>Leave a Comment</h4>
<form method="POST" action="submit_comment.php" class="mt-2">
    <input type="hidden" name="post_id" value="{$post['id']}">
    <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control"></div>
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control"></div>
    <div class="mb-3"><label>Comment</label><textarea name="comment" class="form-control"></textarea></div>
    <input type="submit" value="Submit" class="btn btn-success">
</form>
</div>
FORM;
?>