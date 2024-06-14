<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Update post
        // Admin and Author can update
        if (!checkRole(1) && !checkRole(2)) {
            echo "Access denied.";
            exit;
        }

        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            }
        }

        if ($image) {
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
            $stmt->bind_param("sssi", $title, $content, $image, $id);
        } else {
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $content, $id);
        }
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        // Delete post
        // Admin and Author can delete
        if (!checkRole(1) && !checkRole(2)) {
            echo "Access denied.";
            exit;
        }

        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new post
        // Admin and Author can create
        if (!checkRole(1) && !checkRole(2)) {
            echo "Access denied.";
            exit;
        }

        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            }
        }

        $stmt = $conn->prepare("INSERT INTO posts (title, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $image);
        $stmt->execute();
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>