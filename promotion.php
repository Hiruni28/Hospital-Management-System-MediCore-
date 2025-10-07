<?php include('customer-main/main.php'); ?>

<section class="promotions">
    <h1 style="color: orangered;">New Promotions Available!</h1>

    <?php
    // Fetch promotions from tbl_promotions table
    $sql = "SELECT * FROM tbl_promotions WHERE active = 'Yes' ORDER BY id DESC"; // Fetch active promotions
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $title = $row['title'];
            $program_name = $row['program_name'];
            $price = $row['price'];
            $discount_rate = $row['discount_rate'];
            $discounted_price = $row['discounted_price'];
            $image_name = $row['image_name'];
            $description = $row['description'];

            // Set default image if no image is provided
            $image_path = $image_name != "" ? "images/promotions/" . $image_name : "images/default-promotion.jpg";
    ?>

    <div class="promotion-box">
        <img src="<?php echo $image_path; ?>" alt="Promotion Image">
        <h3 class="promotion-name"><?php echo $title; ?></h3>
        <h4 class="program-name"><strong>Program:</strong> <?php echo $program_name; ?></h4> <!-- Displaying program name -->
        <p class="normal-price">Normal Price: <span class="price"><?php echo $price; ?></span></p>
        <p class="discount-rate">Discount: <?php echo intval($discount_rate); ?>%</p>
        <p class="actual-price">Price with Discount: Rs.<?php echo $discounted_price; ?></p>
        <p class="description"><?php echo $description; ?></p>
    </div>

    <?php
        }
    } else {
        echo "<p>No promotions available at the moment.</p>";
    }
    ?>

</section>

<section class="event">
    <h1 style="color: orangered;">New Events Available!</h1>

    <?php
    // Fetch active events from the database
    $sql = "SELECT * FROM tbl_events WHERE active = 'Yes' ORDER BY event_datetime ASC";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $event_name = $row['event_name'];
            $event_datetime = $row['event_datetime'];
            $gifts = $row['gifts'];
            $description = $row['description'];
            $image_name = $row['image_name'];

            // Format the event date and time
            $formatted_date = date('F j, Y', strtotime($event_datetime));
            $formatted_time = date('g:i A', strtotime($event_datetime));

            // Set the image path or use a placeholder if no image is provided
            $image_path = ($image_name != "") ? "images/events/" . $image_name : "images/placeholder.png";
            ?>
            
            <div class="event-box">
                <img src="<?php echo $image_path; ?>" alt="<?php echo $event_name; ?>" class="event-image">
                <div class="event-details">
                    <h2 class="event-name"><?php echo $event_name; ?></h2>
                    <p class="event-date">Date: <?php echo $formatted_date; ?></p>
                    <p class="event-time">Time: <?php echo $formatted_time; ?></p>
                    <p class="event-gifts">Gifts: <?php echo $gifts; ?></p>
                    <p class="event-description"><?php echo $description; ?></p>
                </div>
            </div>

            <?php
        }
    } else {
        echo "<p>No events available at the moment. Please check back later.</p>";
    }
    ?>

</section>

<?php include('customer-main/footer.php'); ?>
