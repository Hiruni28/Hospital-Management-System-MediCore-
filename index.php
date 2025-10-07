<?php include('customer-main/main.php');?>

    <section class="hero">

        <div class="hero-content">
          <div class="hero-text">
          <h1 style="color: rgb(232, 235, 62);font-size: 70px;">" MEDICORE "</h1>
            
            <h2 style="color: rgb(173, 84, 158);font-size: 50px;">Serving our community with excellence, our hospital system</h2>
            <h4 style="color: rgba(204, 144, 16, 1);font-size: 40px;">Provides quality healthcare with respect, dignity, and efficiency.</h4>
            <br> 
          <p style="color: #dc2626;">
           Contact Us:074-2909788
          </p>
            </div>
             <div class="side-image">
                <img src="images/side.png" alt="Side image here">
             </div>
            </div>
    </section>
    <section class="about-us" id="about">
  <div class="about-overlay">
    <div class="about-us-box">

      <!-- About Hospital -->
      <div class="about-content">
        <img src="images/about1.jpg" alt="Medicore Hospital Interior">
        <div class="text-content">
          <h2>About MediCore Hospital</h2>
          <p>
            MediCore Hospital System is a leading healthcare provider offering a wide
            range of medical services to meet patient needs. With advanced technology
            and expert professionals, we deliver compassionate, high-quality healthcare.
          </p>
          <p>
            From primary care to specialized treatments, MediCore is dedicated to
            improving health outcomes through excellence, innovation, and integrity.
          </p>
        </div>
      </div>

      <!-- Equipments -->
      <div class="about-content">
        <img src="images/about2.jpg" alt="Hospital Equipment">
        <div class="text-content">
          <h2>Available Equipment</h2>
          <p>
            MediCore maintains essential medical equipment including X-ray machines,
            ECG, ultrasound scanners, hospital beds, oxygen cylinders, and emergency
            tools like defibrillators and ambu bags. Laboratory facilities are equipped
            with analyzers, centrifuges, and microscopes to ensure accurate diagnostics.
          </p>
        </div>
      </div>

      <!-- Team -->
      <div class="team-members">
        <h2>Meet Our Teams</h2>
        <div class="trainers-row">
          <div class="trainer-box">
            <img src="images/trainers/trainer1.jpg" alt="Nursing Team" class="trainer-img">
            <h3>Nursing Team</h3>
            <p>📞 07732745689</p>
            <p>✉️ info@hospital.com</p>
          </div>

          <div class="trainer-box">
            <img src="images/trainers/trainer2.jpg" alt="Doctors Team" class="trainer-img">
            <h3>Doctors Team</h3>
            <p>📞 0785678988</p>
            <p>✉️ info@hospital.com</p>
          </div>

          <div class="trainer-box">
            <img src="images/trainers/trainer3.jpg" alt="Staff Members" class="trainer-img">
            <h3>Staff Members</h3>
            <p>📞 0742909788</p>
            <p>✉️ info@hospital.com</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="services" id="services" style="margin-top: -70px;">
    <h2 style="color: orangered;">Available Services</h2>
    <div class="services-container">

        <?php
        // Fetch the categories from the database where active = 'Yes'
        $sql = "SELECT * FROM tbl_programCategories WHERE active='Yes'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Loop through the results and display each active category
            while ($row = $result->fetch_assoc()) {
                $id = $row['id']; // Assuming you have a unique 'id' field in your category table
                $title = $row['title'];
                $image_name = $row['image_name'];

                // Set the default image if no image is found
                $image_path = $image_name != "" ? "images/category/" . htmlspecialchars($image_name) : "images/default-service.jpg";

                ?>
                <a href="programs.php?category_id=<?php echo $id; ?>" class="service-box">
                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($title); ?>">
                    <h3><?php echo htmlspecialchars($title); ?></h3>
                </a>
                <?php
            }
        } else {
            // If no active categories are found
            echo "<p>No active services available at the moment.</p>";
        }
        ?>

        <!-- <div class="view-services">
            <button class="btn">View All Services</button>
        </div> -->
    </div>
</section>

<section class="ambulance-description">
  <div class="container">
    <div class="description-content">
      <h1 style="color:rgba(243, 33, 33, 1);">24/7 Emergency Ambulance Services</h1>
      
      
      <div class="service-details">
       
        <div class="features-grid" style="margin-top: -15px;">
          <div class="feature">
            <i class="fas fa-ambulance"></i>
            <h4>Advanced Life Support</h4>
            <p>Equipped with state-of-the-art life support systems and staffed by experienced paramedics.</p>
          </div>
          <div class="feature">
            <i class="fas fa-clock"></i>
            <h4>Rapid Response</h4>
            <p>Average response time of under 15 minutes in urban areas, 24/7 availability.</p>
          </div>
          <div class="feature">
            <i class="fas fa-user-md"></i>
            <h4>Expert Medical Team</h4>
            <p>Staffed by highly trained emergency medical technicians and paramedics.</p>
          </div>
        </div>
        
        <div class="cta-section">
          <h3 style="color: rgba(235, 31, 31, 1);">Need Emergency Assistance?</h3>
          <p style="color: black; margin:bold;">Don't hesitate to call our emergency hotline for immediate help. Our team is ready to respond to your medical emergency 24 hours a day, 7 days a week.</p>
          <a href="tel:1111" class="emergency-btn">
            <i class="fas fa-phone"></i> Call 1111 Now
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="gym-gallery" id="artgallery">
    <h4 class="label" style="color: orangered;">Art Gallery</h4>
    <div class="container">
        <div class="gallery">
            <?php
            // Fetch images from the database
            $sql = "SELECT image_name FROM tbl_gallery";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any images in the database
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image_name = $row['image_name'];
                    
                    // Set image path
                    $image_path = "images/gallery/" . $image_name;
            ?>
                    <!-- Display each image in a box -->
                    <div class="box">
                        <img src="<?php echo $image_path; ?>" alt="Gallery Image">
                    </div>
            <?php
                }
            } else {
                echo "<p>No images available in the gallery.</p>";
            }
            ?>
        </div>
    </div>
</section>

<section class="feedback" style="margin-top: 0px;">
    <div class="container" style="margin-top: -40px;">
        <h2 class="heading" style="color: orangered;">Customer Feedback</h2>
        
    </div>

    <div class="voices">
        <?php
        // Fetch feedback from the feedback table (assuming table name is 'tbl_feedback')
        $sql = "SELECT customer_name, email, feedback FROM tbl_feedback ORDER BY created_at DESC";
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            // Loop through each feedback entry and display it
            while ($row = $res->fetch_assoc()) {
                $customer_name = $row['customer_name'];
                $email = $row['email'];
                $feedback = $row['feedback'];
                ?>
                <div class="voice">
                    <div class="profile">
                        <div class="detail">
                            <li><?php echo htmlspecialchars($customer_name); ?></li>
                            <li><a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a></li>
                        </div>
                    </div>
                    <p><?php echo htmlspecialchars($feedback); ?></p>
                </div>
                <?php
            }
        } else {
            // Display message if no feedback is available
            echo "<p>No feedback available yet. Be the first to leave feedback!</p>";
        }
        ?>
    </div>

    <div class="feedback-button">
        <a href="addFeedback.php">Add Feedback</a>
    </div>
</section>



    <section class="contact-location" id="contact">
        <div class="location-box">
            <h2 style="color: orangered;">Our Location</h2>
            <p>123 Health Street, Colombo 03, Sri Lanka</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.6786566503984!2d80.21297921149564!3d6.038757793921613!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae173bc63a6d715%3A0xf3cdaa69256d4844!2sICBT%20Galle%20Campus!5e0!3m2!1sen!2slk!4v1733295777018!5m2!1sen!2slk"  style="border: 0" allowfullscreen="" loading="lazy"></iframe>
        </div>


        <div class="contact-box">
            <h2 style="color: orangered;">Contact Us</h2>
            <form action="contact-us.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" name="full_name" required>

                <label for="email">Email:</label>
                <input type="email" name="email" required>

                <label for="mobile">Mobile Number:</label>
                <input type="tel" name="mobile" required>

                <label for="message">Message:</label>
                <textarea name="message" rows="5" required></textarea>

                <input class="btn" type="submit" name="submit" value="Send Message">
            </form>

        </div>
    </section>
    <br>

    <section class="section__container logo__banner">
  <div class="logo__slider">
    <div class="logo__track">
      <img src="images/banner-1.png" alt="banner" />
      <img src="images/banner-3.png" alt="banner" />
      <img src="images/banner-4.png" alt="banner" />
      <img src="images/banner-5.png" alt="banner" />
      <img src="images/banner-2.png" alt="banner" />
      <img src="images/banner-6.jpg" alt="banner" />  
      <!-- repeat logos for smooth infinite effect -->
      <img src="images/banner-1.png" alt="banner" />
      <img src="images/banner-3.png" alt="banner" />
      <img src="images/banner-4.png" alt="banner" />
      <img src="images/banner-5.png" alt="banner" />
      <img src="images/banner-2.png" alt="banner" />
      <img src="images/banner-6.jpg" alt="banner" />  
    </div>
  </div>
</section>

<style>
.logo__banner {
  background-color: #867e7eff;
  padding: 60px;
  border-radius: 10px;
  margin: 20px 0;
  overflow: hidden;
}

.logo__slider {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.logo__track {
  display: flex;
  gap: 100px;
  width: calc(250px * 8); /* adjust based on number of logos */
  animation: scroll 20s linear infinite;
}

.logo__track img {
  height: 180px;
  width: auto;
  object-fit: contain;
}

/* Animation */
@keyframes scroll {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
</style>

    <!-- Chat Button and Container -->
    <div class="chat-button" onclick="toggleChat()">
        <i class="fa fa-comment" style="font-size: 40px; color: white;"></i>
    </div>

    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            <span><i class="fa fa-hospital"></i> MediCore Hospital Chat-Bot</span>
            <button class="close-chat" onclick="toggleChat()">×</button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <!-- Default Messages -->
            <div class="message bot-message">
                <i class="fa fa-stethoscope"></i> Hello! I'm your MediCore Hospital Chat-Bot.
            </div>
            <div class="message bot-message">
                I can help you with appointments, doctor information, services, visiting hours, and general inquiries!
            </div>
        </div>
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Ask about doctors, appointments, services..." autocomplete="off">
            <button onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
        </div>
    </div>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .chat-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 75px;
            height: 75px;
            background: linear-gradient(135deg,rgb(160, 44, 59),rgb(236, 9, 21));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(44, 90, 160, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .chat-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(44, 90, 160, 0.6);
        }

        .chat-container {
            display: none;
            flex-direction: column;
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 430px;
            height: 550px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            z-index: 1000;
            border: 1px solid #e2e8f0;
        }

        .chat-header {
            background: linear-gradient(135deg, #27427aff, #1e3a8a);
            color: white;
            padding: 15px;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }

        .close-chat {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .close-chat:hover {
            background: rgba(255,255,255,0.2);
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background:url(images/gallery/Gallery_4516.jpg);
        }

        .message {
            margin: 12px 0;
            padding: 12px 15px;
            border-radius: 12px;
            max-width: 85%;
            line-height: 1.5;
        }

        .bot-message {
            background:rgba(91, 143, 150, 1) ;
            border: 1px rgb(190, 144, 83);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-right: auto;
        }

        .bot-message i {
            color:rgb(21, 22, 22);
            margin-right: 8px;
        }

        .user-message {
            background: linear-gradient(135deg,rgba(109, 93, 49, 1),rgba(177, 161, 72, 1));
            color: white;
            margin-left: auto;
            box-shadow: 0 2px 8px rgba(44, 90, 160, 0.3);
        }

        .chat-input {
            display: flex;
            padding: 15px;
            border-top: 1px solid #e2e8f0;
            background: white;
            border-radius: 0 0 15px 15px;
        }

        .chat-input input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 25px 0 0 25px;
            outline: none;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .chat-input input:focus {
            border-color: #2c5aa0;
        }

        .chat-input button {
            padding: 12px 20px;
            background: linear-gradient(135deg, #2c5aa0, #1e3a8a);
            color: white;
            border: none;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
            transition: all 0.2s;
        }

        .chat-input button:hover {
            background: linear-gradient(135deg, #1e3a8a, #1e40af);
        }

        .department-tag {
            display: inline-block;
            background: #e0f2fe;
            color: #0277bd;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-right: 5px;
        }

        .availability-available {
            color: #16a34a;
            font-weight: 600;
        }

        .availability-busy {
            color: #dc2626;
            font-weight: 600;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
    </style>

    <script>
        // Hospital data
        const doctors = [
            { id: 1, name: "Dr. Dilum Dilshan", specialty: "OPD/ETU", availability: "Available", experience: "15 years", phone: "+94 11 234 5678", schedule: "Mon-Fri 9AM-5PM" },
            { id: 2, name: "Dr. Rakshitha Gamage", specialty: "Specialist Consultation", availability: "Busy", experience: "12 years", phone: "+94 11 234 5679", schedule: "Mon-Wed 10AM-6PM" },
            { id: 3, name: "Dr. Amasha Kasunthi", specialty: "Mother & Baby Care", availability: "Available", experience: "8 years", phone: "+94 11 234 5680", schedule: "Tue-Sat 8AM-4PM" },
            { id: 4, name: "Dr. Hiruni Kalhari", specialty: "Operation Theatre", availability: "Available", experience: "20 years", phone: "+94 11 234 5681", schedule: "Mon-Fri 7AM-3PM" },
            { id: 5, name: "Dr. Kaveesha Nethmi", specialty: "Operation Theatre", availability: "Busy", experience: "10 years", phone: "+94 11 234 5682", schedule: "Wed-Fri 11AM-7PM" },
            { id: 6, name: "Dr. Chamidu Kanchana", specialty: "Specialist Consultation", availability: "Available", experience: "18 years", phone: "+94 11 234 5683", schedule: "24/7 On-call" },
            { id: 7, name: "Dr. Apekshi Perera", specialty: "Mother & Baby Care", availability: "Available", experience: "14 years", phone: "+94 11 234 5684", schedule: "Mon-Thu 9AM-5PM" },
            { id: 8, name: "Dr. Monali Kaveesha", specialty: "OPD/ETU", availability: "Busy", experience: "16 years", phone: "+94 11 234 5685", schedule: "Tue-Sat 10AM-6PM" }
        ];

        const services = [
            { name: "Emergency Care", department: "Emergency", description: "24/7 emergency medical services", price: "Free consultation" },
            { name: "Heart Surgery", department: "Cardiology", description: "Advanced cardiac procedures", price: "Consultation required" },
            { name: "MRI Scan", department: "Radiology", description: "Magnetic Resonance Imaging", price: "Rs. 25,000" },
            { name: "Blood Test", department: "Laboratory", description: "Complete blood count and analysis", price: "Rs. 2,500" },
            { name: "X-Ray", department: "Radiology", description: "Digital X-ray imaging", price: "Rs. 1,500" },
            { name: "Physiotherapy", department: "Rehabilitation", description: "Physical therapy sessions", price: "Rs. 3,000 per session" },
            { name: "Dental Cleaning", department: "Dentistry", description: "Professional dental hygiene", price: "Rs. 4,000" },
            { name: "Eye Examination", department: "Ophthalmology", description: "Comprehensive eye check-up", price: "Rs. 2,000" }
        ];

        const hospitalInfo = {
            name: "MediCore Hospital",
            address: "123 Health Street, Colombo 03, Sri Lanka",
            phone: "+94 11 234 5600",
            email: "info@medicorehospital.lk",
            visitingHours: "Daily 8:00 AM - 8:00 PM",
            emergencyHours: "24/7",
            parking: "Free parking available"
        };

        function toggleChat() {
            const chatContainer = document.getElementById('chatContainer');
            chatContainer.style.display = 
                chatContainer.style.display === 'flex' ? 'none' : 'flex';
            if (chatContainer.style.display === 'flex') {
                document.getElementById('userInput').focus();
            }
        }

        function sendMessage() {
            const input = document.getElementById('userInput');
            const messages = document.getElementById('chatMessages');
            const message = input.value.trim();

            if (!message) return;

            // Add user message
            const userMsg = document.createElement('div');
            userMsg.className = 'message user-message';
            userMsg.textContent = message;
            messages.appendChild(userMsg);

            // Add bot response
            const botMsg = document.createElement('div');
            botMsg.className = 'message bot-message';
            botMsg.innerHTML = getBotResponse(message);
            messages.appendChild(botMsg);

            // Clear input and scroll to bottom
            input.value = '';
            messages.scrollTop = messages.scrollHeight;
        }

        function getBotResponse(message) {
            const cleanMessage = message.toLowerCase().trim();

            // Greetings
            if (cleanMessage.includes('hello') || cleanMessage.includes('hi') || cleanMessage.includes('hey')) {
                return '<i class="fa fa-wave-hand"></i> Hello! Welcome to MediCore Hospital. How can I assist you today?';
            }

            if (cleanMessage.includes('thanks') || cleanMessage.includes('thank you')) {
                return '<i class="fa fa-heart"></i> You\'re very welcome! Is there anything else I can help you with?';
            }

            // Doctor queries
            if (cleanMessage.includes('doctor') || cleanMessage.includes('physician')) {
                if (cleanMessage.includes('cardiology') || cleanMessage.includes('heart')) {
                    const cardioDoc = doctors.find(d => d.specialty === 'Cardiology');
                    return `<i class="fa fa-heartbeat"></i> <strong>${cardioDoc.name}</strong><br>
                            <span class="department-tag">Cardiology</span><br>
                            Experience: ${cardioDoc.experience}<br>
                            Status: <span class="availability-${cardioDoc.availability.toLowerCase()}">${cardioDoc.availability}</span><br>
                            Schedule: ${cardioDoc.schedule}<br>
                            Phone: ${cardioDoc.phone}`;
                }
                
                if (cleanMessage.includes('pediatric') || cleanMessage.includes('children') || cleanMessage.includes('kids')) {
                    const pediatricDoc = doctors.find(d => d.specialty === 'Pediatrics');
                    return `<i class="fa fa-baby"></i> <strong>${pediatricDoc.name}</strong><br>
                            <span class="department-tag">Pediatrics</span><br>
                            Experience: ${pediatricDoc.experience}<br>
                            Status: <span class="availability-${pediatricDoc.availability.toLowerCase()}">${pediatricDoc.availability}</span><br>
                            Schedule: ${pediatricDoc.schedule}<br>
                            Phone: ${pediatricDoc.phone}`;
                }

                if (cleanMessage.includes('emergency')) {
                    const emergencyDoc = doctors.find(d => d.specialty === 'Emergency Medicine');
                    return `<i class="fa fa-ambulance"></i> <strong>${emergencyDoc.name}</strong><br>
                            <span class="department-tag">Emergency</span><br>
                            Experience: ${emergencyDoc.experience}<br>
                            Status: <span class="availability-${emergencyDoc.availability.toLowerCase()}">${emergencyDoc.availability}</span><br>
                            Schedule: ${emergencyDoc.schedule}<br>
                            Phone: ${emergencyDoc.phone}`;
                }

                // List all available doctors
                const availableDocs = doctors.filter(d => d.availability === 'Available');
                return `<i class="fa fa-user-md"></i> <strong>Available Doctors:</strong><br><br>` +
                       availableDocs.map(doc => 
                           `• <strong>${doc.name}</strong> - ${doc.specialty}<br>
                            &nbsp;&nbsp;${doc.schedule} | ${doc.phone}`
                       ).join('<br><br>');
            }

            // Appointment queries
            if (cleanMessage.includes('appointment') || cleanMessage.includes('book') || cleanMessage.includes('schedule')) {
                return `<i class="fa fa-calendar-plus"></i> <strong>Book an Appointment</strong><br><br>
                        To schedule an appointment, please:<br>
                        • Call our main line: <strong>${hospitalInfo.phone}</strong><br>
                        • Visit our reception during visiting hours<br>
                        • Email us: <strong>${hospitalInfo.email}</strong><br><br>
                        Please have your ID and insurance information ready!`;
            }

            // Services and pricing
            if (cleanMessage.includes('service') || cleanMessage.includes('price') || cleanMessage.includes('cost')) {
                if (cleanMessage.includes('mri')) {
                    const mri = services.find(s => s.name === 'MRI Scan');
                    return `<i class="fa fa-microscope"></i> <strong>${mri.name}</strong><br>
                            <span class="department-tag">${mri.department}</span><br>
                            ${mri.description}<br>
                            <strong>Cost: ${mri.price}</strong>`;
                }
                
                if (cleanMessage.includes('blood test') || cleanMessage.includes('lab')) {
                    const bloodTest = services.find(s => s.name === 'Blood Test');
                    return `<i class="fa fa-vial"></i> <strong>${bloodTest.name}</strong><br>
                            <span class="department-tag">${bloodTest.department}</span><br>
                            ${bloodTest.description}<br>
                            <strong>Cost: ${bloodTest.price}</strong>`;
                }

                // List popular services
                return `<i class="fa fa-clipboard-list"></i> <strong>Popular Services:</strong><br><br>` +
                       services.slice(0, 4).map(service => 
                           `• <strong>${service.name}</strong> - ${service.price}<br>
                            &nbsp;&nbsp;<span class="department-tag">${service.department}</span> ${service.description}`
                       ).join('<br><br>');
            }

            // Hospital information
            if (cleanMessage.includes('address') || cleanMessage.includes('location') || cleanMessage.includes('where')) {
                return `<i class="fa fa-map-marker-alt"></i> <strong>Hospital Location:</strong><br><br>
                        ${hospitalInfo.address}<br><br>
                        <i class="fa fa-phone"></i> Phone: ${hospitalInfo.phone}<br>
                        <i class="fa fa-envelope"></i> Email: ${hospitalInfo.email}<br>
                        <i class="fa fa-car"></i> ${hospitalInfo.parking}`;
            }

            if (cleanMessage.includes('hours') || cleanMessage.includes('visiting') || cleanMessage.includes('open')) {
                return `<i class="fa fa-clock"></i> <strong>Hospital Hours:</strong><br><br>
                        <strong>Visiting Hours:</strong> ${hospitalInfo.visitingHours}<br>
                        <strong>Emergency:</strong> ${hospitalInfo.emergencyHours}<br><br>
                        Emergency services are always available!`;
            }

            // Emergency
            if (cleanMessage.includes('emergency') || cleanMessage.includes('urgent')) {
                return `<i class="fa fa-exclamation-triangle" style="color: #dc2626;"></i> <strong style="color: #dc2626;">EMERGENCY</strong><br><br>
                        For immediate medical attention:<br>
                        • Call: <strong style="color: #dc2626;">1111</strong> (Ambulance)<br>
                        • Visit our Emergency Department (24/7)<br>
                        • Location: ${hospitalInfo.address}<br><br>
                        Our emergency team is ready to help!`;
            }

            // Default response
            return `<i class="fa fa-info-circle"></i> I'm here to help! You can ask me about:<br><br>
                    • Doctor information and schedules<br>
                    • Booking appointments<br>
                    • Services and pricing<br>
                    • Hospital location and hours<br>
                    • Emergency services<br><br>
                    What would you like to know?`;
        }

        // Allow Enter key to send message
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('userInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>

<?php include('customer-main/footer.php'); ?>