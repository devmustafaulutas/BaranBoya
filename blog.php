<?php include "header.php"; ?>
<?php include "z_db.php" ?>

<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content text-center">
                    <h2 class="text-white text-uppercase mb-3">Blog</h2>
                    <ol class="breadcrumb d-flex justify-content-center">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Ana Sayfa</a></li>
                        <li class="breadcrumb-item text-white active">Blog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row" style="padding:40px;">
            <?php
            // Fetch blog posts from the database
            $query = "SELECT * FROM blog ORDER BY updated_at DESC";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="blog-post shadow-sm">';
                    
                    // Blog Thumbnail
                    echo '<div class="blog-post-thumbnail" style="background-image: url(\'' . htmlspecialchars($row['logo']) . '\');"></div>';
                    
                    echo '<div class="blog-post-content p-3">';
                    echo '<h3 class="blog-post-title">' . htmlspecialchars($row['blog_title']) . '</h3>';
                    echo '<p class="blog-post-desc">' . htmlspecialchars(substr($row['blog_desc'], 0, 150)) . '...</p>';
                    echo '<div class="blog-post-footer d-flex justify-content-between align-items-center">';
                    echo '<a href="blog_post.php?id=' . htmlspecialchars($row['id']) . '" class="read-more">Read More</a>';
                    echo '<button class="like-btn" data-id="' . $row['id'] . '"><i class="fas fa-thumbs-up"></i> Like</button>';
                    echo '</div>'; // blog-post-footer
                    echo '</div>'; // blog-post-content
                    
                    echo '</div>'; // blog-post
                    echo '</div>'; // col-md-4
                }
            } else {
                echo '<p>No blog posts found.</p>';
            }
            ?>
        </div>
    </div>
</section>



<?php include "footer.php"; ?>