<?php include "header.php"; ?>
<?php
// Blog yazılarını hazırlıklı ifade ile alın
$stmt = $con->prepare("SELECT * FROM blog_posts ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

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
    <div class="container text-center">
        <div class="row">
            <div class="">
                <div class="blog-page-blogs col-md-12">
                    <?php while ($post = $result->fetch_assoc()) { ?>
                    <div class="col-6">
                        <div class="clearfix">
                            <p>
                                 meaningless phrases here to demonstrate how the columns interact here with the floated image.
                            </p>

                            <p>
                                As you can see the paragraphs gracefully wrap around the floated image. Now imagine how this would look with some actual content in here, rather than just this boring placeholder text that goes on and on, but actually conveys no tangible information at. It simply takes up space and should not really be read.
                            </p>

                            <p>
                                And yet, here you are, still persevering in reading this placeholder text, hoping for some more insights, or some hidden easter egg of content. A joke, perhaps. Unfortunately, there's none of that here.
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="clearfix">
                            <img src="assets/img/categorys/YAT 1.jpeg" class="col-md-6 float-md-end mb-3 ms-md-3" alt="...">
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!-- <div class="blog-page-blogs col-md-12">
                    <div class="container text-center">
                        <div class="row">
                            <div class="col-sm-5 col-md-6">.col-sm-5 .col-md-6</div>
                            <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0">.col-sm-5 .offset-sm-2 .col-md-6 .offset-md-0</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-5 col-lg-6">.col-sm-6 .col-md-5 .col-lg-6</div>
                            <div class="col-sm-6 col-md-5 offset-md-2 col-lg-6 offset-lg-0">.col-sm-6 .col-md-5 .offset-md-2 .col-lg-6 .offset-lg-0</div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>
