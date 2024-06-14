<?php
    include 'includes/db.php';

    session_start();

    function checkRole($role) {
        return isset($_SESSION['role_id']) && $_SESSION['role_id'] == $role;
    }

    include 'includes/crud_operations.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Simple Blog</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Welcome, <?php echo $_SESSION['username']; ?>! <a href="logout.php">Logout</a></p>
    <?php else: ?>
        <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
    <?php endif; ?>
    <?php if (checkRole(1) || checkRole(2)): ?>
        <form method="POST" enctype="multipart/form-data" class="mb-5">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Add Post</button>
        </form>
    <?php endif; ?>
    <div class="posts">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><a href="post.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?php echo $row['image']; ?>" class="img-fluid mb-3">
                    <?php endif; ?>
                    <p class="card-text"><small class="text-muted">Posted on <?php echo $row['created_at']; ?></small></p>
                    <?php if (checkRole(1) || checkRole(2)): ?>
                        <button class="btn btn-secondary btn-sm" onclick="editPost(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['title']); ?>', '<?php echo htmlspecialchars($row['content']); ?>')">Edit</button>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editPostId" name="id">
                    <div class="form-group">
                        <label for="editPostTitle">Title</label>
                        <input type="text" class="form-control" id="editPostTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="editPostContent">Content</label>
                        <textarea class="form-control" id="editPostContent" name="content" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editPostImage">Image</label>
                        <input type="file" class="form-control-file" id="editPostImage" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function editPost(id, title, content) {
        document.getElementById('editPostId').value = id;
        document.getElementById('editPostTitle').value = title;
        document.getElementById('editPostContent').value = content;
        $('#editPostModal').modal('show');
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>