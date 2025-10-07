<?php include('partials/main.php'); ?>
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Update Program</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Select Category:</td>
                    <td>
                        <select name="category">
                            <option value="1">Category 1</option>
                            <option value="2">Category 2</option>
                            <option value="3">Category 3</option>
                            <!-- You will dynamically load categories from the database here later -->
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="Enter Program Title" value="Cardio Training">
                        <!-- This value will be dynamically filled from the database -->
                    </td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" rows="5" placeholder="Enter Program Description">High-Intensity Interval Training</textarea>
                        <!-- Dynamically load the description -->
                    </td>
                </tr>

                <tr>
                    <td>Price for Locals:</td>
                    <td>
                        <input type="text" name="price_local" placeholder="Enter Price for Locals" value="50.00">
                        <!-- Dynamically load the price for locals -->
                    </td>
                </tr>

                <tr>
                    <td>Price for Foreigners:</td>
                    <td>
                        <input type="text" name="price_foreign" placeholder="Enter Price for Foreigners" value="100.00">
                        <!-- Dynamically load the price for foreigners -->
                    </td>
                </tr>

                <tr>
                    <td>Date and Time Schedule:</td>
                    <td>
                        <div id="schedule-list" class="schedule-list">
                            <input type="text" name="schedule[]" placeholder="Enter Date and Time" class="schedule-input" value="Monday: 8:00 AM - 9:00 AM">
                            <input type="text" name="schedule[]" placeholder="Enter Date and Time" class="schedule-input" value="Wednesday: 10:00 AM - 11:00 AM">
                            <input type="text" name="schedule[]" placeholder="Enter Date and Time" class="schedule-input" value="Friday: 6:00 PM - 7:00 PM">
                            <!-- Dynamically load multiple schedules from the database -->
                        </div>
                        <button type="button" onclick="addSchedule()" class="btn-add-schedule">Add More Schedule</button>
                    </td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <img src="../images/cardio.jpg" alt="Program Image" width="100px">
                        <!-- This image should be dynamically loaded -->
                    </td>
                </tr>

                <tr>
                    <td>Select New Image:</td>
                    <td>
                    <!-- accept="image/*" onchange="previewImage(event)" -->
                        <input type="file" name="image" >
                        <img id="image-preview" src="" alt="Image Preview" style="display:none; margin-top:10px;" width="100">
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <textarea name="featured" rows="3" placeholder="Enter Featured Details">Special discount for early registration</textarea>
                        <!-- Dynamically load the featured details -->
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" checked> Yes
                        <input type="radio" name="active" value="No"> No
                        <!-- Dynamically load the active status -->
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Program" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<!-- Add your CSS here to fix the layout -->

<script>
    // Function to dynamically add more schedule fields
    function addSchedule() {
        const scheduleList = document.getElementById('schedule-list');
        const newSchedule = document.createElement('input');
        newSchedule.setAttribute('type', 'text');
        newSchedule.setAttribute('name', 'schedule[]');
        newSchedule.setAttribute('placeholder', 'Enter Date and Time');
        newSchedule.setAttribute('class', 'schedule-input'); // Apply the same class to new inputs
        scheduleList.appendChild(newSchedule);
    }

    // Function to preview the uploaded image
    // function previewImage(event) {
    //     const imagePreview = document.getElementById('image-preview');
    //     const file = event.target.files[0];
    //     if (file) {
    //         imagePreview.src = URL.createObjectURL(file);
    //         imagePreview.style.display = 'block';
    //     }
    // }
</script>
