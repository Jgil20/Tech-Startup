<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $content = $_POST['content'];
    $author = $_POST['author'];
    $category = $_POST['category'];

    $image = '';
    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $stmt = $conn->prepare("INSERT INTO blog_posts (title, slug, content, author, image, category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $slug, $content, $author, $image, $category);
    $stmt->execute();
    header("Location: blog_admin.php");
}
?>
<form method="post" enctype="multipart/form-data" class="container mt-4">
    <div class="mb-3">
        <label>Title</label><input type="text" name="title" class="form-control">
    </div>
    <div class="mb-3">
        <label>Author</label><input type="text" name="author" class="form-control">
    </div>
    <div class="mb-3">
        <label>Category</label><input type="text" name="category" class="form-control">
    </div>
    <div class="mb-3">
        <label>Image</label><input type="file" name="image" class="form-control">
    </div>
    <div class="mb-3">
        <label>Content</label><textarea name="content" id="content" class="form-control"></textarea>
    </div>
    <input type="submit" value="Publish" class="btn btn-primary">
</form>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>tinymce.init({ selector: '#content' });</script>