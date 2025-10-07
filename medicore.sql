-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 06:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medicore`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `appointment_id` int(10) NOT NULL,
  `patient_id` int(10) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `patient_phone` varchar(10) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `appointment_id`, `patient_id`, `doctor_id`, `patient_name`, `patient_phone`, `appointment_date`, `appointment_time`, `status`, `created_at`) VALUES
(1, 1, 19, 2, 'Haala', '0779987655', '2025-08-21', '12:27:23', 'confirmed', '2025-08-14 17:58:07'),
(8, 4, 19, 1, 'Haala Husain', '0758412275', '2025-08-20', '01:00:00', 'confirmed', '2025-08-17 05:59:04'),
(11, 2, 38, 1, 'Dilum Dilshan', '0742909788', '2025-08-19', '22:11:00', 'confirmed', '2025-08-19 15:41:45'),
(12, 13, 39, 1, 'Hiruni Parindya', '0776897657', '2025-09-04', '16:00:00', 'confirmed', '2025-09-01 06:33:14'),
(18, 16, 39, 1, 'Hiruni Parindya', '0777324569', '2025-09-05', '20:25:00', 'confirmed', '2025-09-01 09:49:22'),
(17, 16, 39, 2, 'Hiruni Parindya', '0777324569', '2025-09-05', '21:15:00', 'confirmed', '2025-09-01 09:43:45'),
(16, 15, 2, 1, 'Monali Kaveesha', '0777324569', '2025-09-05', '17:50:00', 'confirmed', '2025-09-01 09:18:24'),
(19, 17, 38, 1, 'Dilum Dilshan', '0777324569', '2025-09-05', '21:30:00', 'confirmed', '2025-09-01 09:55:39'),
(20, 18, 38, 0, 'Dilum Dilshan', '0742909788', '2025-09-09', '01:00:00', 'pending', '2025-09-03 16:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `doctor_id` int(10) NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `doctorName` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `docFees` varchar(255) DEFAULT NULL,
  `contactno` bigint(11) DEFAULT NULL,
  `docEmail` varchar(255) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `doctor_id`, `specilization`, `doctorName`, `address`, `docFees`, `contactno`, `docEmail`, `username`, `password`, `profile_image`) VALUES
(1, 1, 'OPD/ETU', 'Dilum Dilshan(OPD/ETU)', 'No:25,Hikkaduwa,Galle.', '1000', 742909788, 'dilumdilshan527@gmail.com', 'dilum', '123', 'doctor_12_1754540505.png'),
(2, 2, 'Specialist Consultation', 'Rakshitha Gamage( Consultation)', 'No123/B, Karapitiya ,Galle', '3500', 785678908, 'Rakshitha@gmail.com', 'Rakshitha', '123', 'doctor_2_1755485168.jpg'),
(3, 3, 'Mother & Baby Care', 'Amasha Kasunthi(Mother & Baby Care)', 'No12/A, Kadana,Colombo', '2500', 765678907, 'Amasha@gmail.com', 'Amasha', '456', 'doctor_15_1751283765.png'),
(4, 4, 'Operation Theatre', 'Hiruni Kalhari(Operation)', 'Ambalangoda', '5000', 785432768, 'hiruni@gmail.com', 'hiruni', '202cb962ac59075b964b07152d234b70', NULL),
(5, 5, 'Operation Theatre', 'Kaveesha Nethmi(Operation)', 'Nugegoda', '4000', 773214567, 'kaveesha@gmail.com', 'kaveesha', '202cb962ac59075b964b07152d234b70', NULL),
(6, 6, 'Specialist Consultation', 'Chamidu Kanchana(Consultation)', 'Galle', '4500', 761232145, 'chamidu@gmail.com', 'chamidu', '202cb962ac59075b964b07152d234b70', NULL),
(7, 7, 'Specialist Consultation', 'Sadunika Sandeepa(Consultation)', 'Matara', '2500', 762345387, 'sadunika@gmail.com', 'sadunika', '202cb962ac59075b964b07152d234b70', NULL),
(8, 8, 'Mother & Baby Care', 'Apekshi Perera(Mother & Baby Care)', 'Colombo', '4500', 752345637, 'apekshi@gmail.com', 'apekshi', '202cb962ac59075b964b07152d234b70', NULL),
(9, 9, 'Operation Theatre', 'Pasindu Yasith(Operation)', 'Galle', '2000', 752343567, 'pasindu@gmail.com', 'pasidu', '202cb962ac59075b964b07152d234b70', NULL),
(10, 10, 'Operation Theatre', 'Ruwan Perera(Operation)', 'Galle', '4500', 762345390, 'ruwan@gmail.com', 'ruwan', '202cb962ac59075b964b07152d234b70', NULL),
(14, 0, 'OPD/ETU', 'Monali Kaveesha (OPD/ETU)', 'Galle', '3000', 711212234, 'monali@gmail.com', 'monali', '123', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lab_reports`
--

CREATE TABLE `lab_reports` (
  `id` int(11) NOT NULL,
  `patient_id` int(10) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `doctor_name` varchar(20) NOT NULL,
  `report_type` varchar(100) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `report_file` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `lab_reports`
--

INSERT INTO `lab_reports` (`id`, `patient_id`, `doctor_id`, `patient_name`, `doctor_name`, `report_type`, `report_date`, `report_file`) VALUES
(6, 19, 2, 'Haala', 'Rakshitha', 'Blood Report', '2025-08-19', 'uploads/reports/report_68950323e6ca80.23165625.png'),
(9, 20, 2, 'Dilhani', 'Rakshitha', 'PCR', '2025-09-26', 'uploads/1755079210_Diagram-Page-4.drawio.png'),
(10, 39, 1, 'Hiruni Parindya', 'Dr. Dilum', 'CBC Report', '2025-09-04', 'uploads/1756709639_cbc.png'),
(11, 39, 1, 'Hiruni Parindya', 'Dr. Dilum', 'Blood Report', '2025-09-04', 'uploads/1756721295_cbc.png');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `prescription_id` int(11) NOT NULL,
  `patient_id` int(10) NOT NULL,
  `doctor_id` int(10) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `medicine` text NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `duration` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`prescription_id`, `patient_id`, `doctor_id`, `patient_name`, `doctor_name`, `date`, `medicine`, `dosage`, `duration`, `notes`, `image`) VALUES
(3, 19, 2, 'Haala', 'Rakshitha', '2025-08-07', 'mnj', 'twice for day', '2 months', 'cygj', '1755030131_download1.jpg'),
(5, 39, 1, 'Hiruni Parindya', 'Dr. Dilum', '2025-09-04', 'Paracetamol 500mg', '1 tablet, three times daily after meals', '5 days', 'Wound dressing to be done daily. Keep the wound area clean and dry. Report if swelling, discharge, or increased pain occurs.', '1756709303_hiru.png'),
(6, 43, 2, 'Monali Kaveesha', 'Dr. Rakshitha', '2025-09-05', 'Sertraline 50 mg', '1 tablet once daily', '4–6 weeks', 'Monitor mood, sleep, and appetite; follow up regularly with a counsellor/psychiatrist.', '1756717325_pres.jpg'),
(9, 39, 2, 'Hiruni Parindya', 'Dr. Rakshitha', '2025-09-05', 'Naltrexone', '1 tablet once daily', '4–12 weeks', 'Monitor liver function; take with food, and follow up regularly.', '1756720018_p.jpeg'),
(10, 39, 2, 'Hiruni Parindya', 'Dr. Dilum', '2025-09-05', 'Salbutamol Nebulization', '2.5 mg in 2–3 mL saline', 'Every 6–8 hours as needed for 5 days', 'Administer via nebulizer under supervision; monitor for tremors or palpitations; ensure proper inhalation technique.', '1756720341_cbc.png'),
(8, 43, 1, 'Monali Kaveesha', 'Dr. Dilum', '2025-09-05', 'Vitamin B12 Injection', '1000 mcg intramuscular (IM)', 'Once weekly for 4 weeks', 'Administered by trained healthcare professional; monitor for allergic reactions; keep site clean.', '1756719702_hiru.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(25) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `full_name`, `username`, `email`, `position`, `password`) VALUES
(12, 'Dilum Dilshan', 'dilum', 'dilumdilshan2004@gmail.com', 'manager', '202cb962ac59075b964b07152d234b70'),
(13, 'Dilum Dilshan', 'dilum', 'dilumdilshan@gmail.com', 'staff', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blogs`
--

CREATE TABLE `tbl_blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_blogs`
--

INSERT INTO `tbl_blogs` (`id`, `title`, `content`, `image`, `created_at`) VALUES
(3, '10 Simple Habits for a Healthier Heart', 'Heart disease remains the leading cause of death worldwide, but the good news is that much of it is preventable through lifestyle changes. This blog dives deep into ten actionable habits that anyone can incorporate into their daily routine to significantly improve heart health. From choosing nutrient-rich foods like leafy greens, nuts, and fatty fish, to limiting intake of processed foods and trans fats, nutrition plays a pivotal role in cardiovascular wellness. Regular physical activity, such as brisk walking or cycling for at least 150 minutes per week, helps strengthen the heart muscle, lower bad cholesterol (LDL), and improve circulation. Equally important is managing stress, which can elevate blood pressure and lead to unhealthy coping mechanisms like smoking or overeating.\r\n\r\nIn addition to diet and exercise, this article highlights the significance of monitoring critical heart health indicators such as blood pressure, cholesterol, and blood sugar. Routine health check-ups can catch early warning signs of hypertension or diabetes, conditions that greatly increase heart disease risk. The blog also educates readers on the importance of quitting smoking and limiting alcohol, as these habits accelerate arterial damage and increase the likelihood of heart attacks and strokes. Beyond physical measures, quality sleep and maintaining a healthy weight are emphasized as essential for reducing inflammation and promoting overall cardiovascular function.\r\n\r\nLastly, this blog encourages readers to adopt a heart-healthy lifestyle not as a temporary fix, but as a lifelong commitment. It addresses common barriers such as time constraints and lack of motivation and offers practical tips to overcome them. Whether it’s taking the stairs instead of the elevator or preparing home-cooked meals, small, consistent changes accumulate to powerful benefits. This blog is an empowering resource for anyone wanting to protect their heart and enjoy a longer, healthier life.\r\n\r\n', 'Blog_4487.jpg', '2024-10-20 11:57:51'),
(5, 'The Truth About Sleep: Why 7 Hours Isn’t Optional', 'Sleep is a fundamental biological process critical to physical, emotional, and cognitive health, yet modern lifestyles often push it to the bottom of the priority list. This blog explores why adults need at least seven hours of quality sleep each night and what happens to the body when those hours aren’t met. During sleep, the brain processes memories, clears toxins, and regulates hormones such as cortisol and insulin. Lack of sleep impairs these vital processes, leading to increased risks of chronic diseases including obesity, diabetes, cardiovascular problems, and mental health disorders. The article explains the stages of sleep—light, deep, and REM—and their unique roles in restoring different systems of the body, showing why uninterrupted, quality sleep is crucial.\r\n\r\nBeyond explaining the science, the blog addresses common causes of poor sleep, such as excessive screen time, caffeine consumption late in the day, stress, and irregular sleep schedules. It offers practical advice on improving sleep hygiene, including maintaining a consistent bedtime, creating a dark and quiet environment, and limiting exposure to blue light before bed. The blog also discusses the impact of sleep disorders such as insomnia, sleep apnea, and restless leg syndrome, emphasizing when to seek professional help. Recognizing that sleep problems often coexist with other health issues, it encourages a holistic approach to treatment.\r\n\r\nFinally, the article highlights the broader societal and personal consequences of sleep deprivation, from decreased productivity and accidents to weakened immune defense and impaired emotional regulation. It stresses that prioritizing sleep is not indulgent but essential self-care. Readers will be inspired to view sleep as a vital part of their health regimen, unlocking better energy, focus, and long-term wellness by reclaiming their nights.', 'Blog_4855.png', '2024-10-20 12:05:52'),
(6, 'Managing Diabetes: What You Need to Know', 'Diabetes is a chronic metabolic condition characterized by elevated blood glucose levels, resulting from either insufficient insulin production (Type 1) or insulin resistance (Type 2). This blog post provides a comprehensive overview of diabetes, explaining the differences between Type 1, Type 2, and gestational diabetes, and how each affects the body. It highlights common symptoms such as increased thirst, frequent urination, fatigue, and blurred vision, underscoring the importance of early diagnosis. The blog stresses that diabetes is a manageable condition, especially when caught early, and that lifestyle changes combined with medication can significantly reduce complications.\r\n\r\nThe core of diabetes management lies in maintaining stable blood sugar levels. The blog explains the role of a balanced diet focused on low-glycemic index foods, portion control, and consistent meal timing to prevent spikes and dips in glucose. Physical activity is another cornerstone, improving insulin sensitivity and cardiovascular health. The post also outlines the importance of regular blood sugar monitoring, medication adherence, and insulin therapy for some patients. It emphasizes the need for a multidisciplinary care team, including dietitians, endocrinologists, and diabetes educators, to tailor treatment plans.\r\n\r\nAdditionally, the blog covers the long-term risks of poorly managed diabetes, including nerve damage, kidney disease, vision loss, and cardiovascular complications. It encourages patients to adopt proactive habits such as foot care, regular eye exams, and monitoring for signs of complications. Mental and emotional support are also discussed, as diabetes management can be stressful and impact quality of life. This article aims to empower readers with the knowledge and motivation needed to live well with diabetes, turning what may seem like a daunting diagnosis into a manageable journey toward health.\r\n\r\n', 'Blog_7425.jpg', '2024-10-20 12:06:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact`
--

CREATE TABLE `tbl_contact` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `reply_message` text NOT NULL,
  `status` enum('Pending','Replied') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_contact`
--

INSERT INTO `tbl_contact` (`id`, `full_name`, `email`, `mobile`, `message`, `reply_message`, `status`) VALUES
(17, 'Dilum Idumini Dilshan', 'dilumdilshan2004@gmail.com', '0742909788', 'I need emergency assistance!', 'Please dial 1111 immediately for emergency ambulance service. Stay calm, help is on the way.', 'Replied'),
(18, 'Hiruni Parindhya', 'hiruni@gmail.com', '0758412275', 'What are your visiting hours?', 'Visiting hours are from 10:00 AM to 1:00 PM and 4:00 PM to 7:00 PM. Please follow hospital safety guidelines.', 'Replied'),
(19, 'Kasun Kalhara', 'kasun@gmail.com', '0721212345', 'Is Dr. Fernando available tomorrow?', 'Yes', 'Replied'),
(22, 'Dilhani Gamage', 'dilhani@gmail.com', '0712909788', 'What are your visiting hours?', '', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tel_no` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`id`, `full_name`, `username`, `tel_no`, `email`, `password`) VALUES
(5, 'Dilum', 'dilum', '0742909788', 'dilumdilshan2004@gmail.com', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `id` int(11) UNSIGNED NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `gifts` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` enum('Yes','No') DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_events`
--

INSERT INTO `tbl_events` (`id`, `event_name`, `event_datetime`, `gifts`, `description`, `image_name`, `created_at`, `active`) VALUES
(2, 'Blood Donation Camp', '2025-08-31 08:00:00', 'Free Health Check & Refreshments for Donors', 'Join us in saving lives through our hospital-wide blood donation campaign. All donors will receive a free mini health check and refreshments. Open to all healthy individuals aged 18–60. Walk-ins welcome!\r\n\r\n', 'Event_5645.jpg', '2024-10-20 04:24:24', 'Yes'),
(3, 'Cancer Awareness & Support Day ', '2025-08-15 10:00:00', 'Free Cancer Risk Screening & Awareness Kit', 'Visit our oncology team for educational sessions, early detection screenings, and support resources. Special focus on breast, cervical, and prostate cancers. Survivors will share their journeys in a support circle.', 'Event_3563.jpg', '2024-10-20 04:26:14', 'Yes'),
(5, 'Child Wellness & Immunization Camp', '2025-09-26 09:00:00', 'Free Growth & Nutrition Kit for Children', 'A full-day camp offering pediatric check-ups, developmental screenings, vaccination advice, and parenting tips. Perfect for parents with children under 12. Meet our child specialists and ensure your child’s healthy future.\r\n\r\n', 'Event_5523.jpeg', '2024-10-20 04:37:57', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_feedback`
--

INSERT INTO `tbl_feedback` (`id`, `customer_name`, `email`, `feedback`, `created_at`) VALUES
(12, 'Rajitha Perera', 'rajitha@gmail.com', 'Having heart issues means regular hospital visits, and carrying files around used to be a hassle. With the new system, all my reports and history are stored digitally. During an emergency visit, the doctors accessed my entire file instantly and started treatment right away. The speed and accuracy of the care I received truly made a difference. I felt safe, and that’s priceless.', '2025-08-07 10:58:36'),
(13, 'Mala Herath', 'mala@gmail.com', 'Managing diabetes was stressful until I started using the hospital’s online system. I can now access my lab results, prescriptions, and doctor’s notes from my phone. I even get reminders for tests and medication refills. It’s made managing my condition less overwhelming and more empowering. For the first time, I feel in control of my health.', '2025-08-07 10:59:31'),
(14, 'Saman Jayalath', 'saman@gmail.com', 'My daughter had a minor surgery, and the hospital made the process incredibly smooth. From registration to discharge, everything was well-organized. Her test results were updated in real time, and I was kept informed throughout. The system reduced our waiting time and removed a lot of the usual stress. I felt reassured knowing she was in capable, well-coordinated hands.', '2025-08-07 11:00:10'),
(15, 'Thilini Gamage', 'thilini@gmail.com', 'After my delivery, I was amazed at how well everything was managed. From ultrasound scans to discharge papers, everything was stored and shared digitally. I didn’t have to repeat my history or wait long for reports. The entire maternity team seemed to work in sync, and I could focus on recovery and bonding with my baby.', '2025-08-07 11:01:06'),
(16, 'Mohomed Riyaz', 'riyaz@gmail.com', 'When I was discharged after COVID treatment, I was still anxious. But the hospital continued to monitor me remotely using their system. I received follow-up calls, could share my vitals online, and ask questions through the portal. I never felt alone during recovery. The support and care meant more than I can express.', '2025-08-07 11:02:06'),
(17, 'Dilum Dilshan', 'dilumdilshan2004@gmail.com', 'Booking my appointments online has never been easier. The hospital’s system lets me choose a doctor, view available times, and even reschedule without having to call. It saves me so much time, especially with my busy schedule. I also love getting reminders before my appointments.', '2025-08-18 17:22:44'),
(18, 'Dilhani Gamage', 'dilhani@gmail.com', 'Managing my condition was difficult until I started using the hospital’s online system. Now I can easily check lab results, prescriptions, and doctor’s notes from my phone. I also receive reminders for tests and medication refills. It has made the process less stressful and more manageable, giving me confidence and control over my health.', '2025-09-03 02:20:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gallery`
--

CREATE TABLE `tbl_gallery` (
  `id` int(11) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_gallery`
--

INSERT INTO `tbl_gallery` (`id`, `image_name`) VALUES
(3, 'Gallery_1850.jpg'),
(4, 'Gallery_6113.jpg'),
(6, 'Gallery_3558.jpg'),
(7, 'Gallery_6288.jpg'),
(9, 'Gallery_6768.jpg'),
(10, 'Gallery_4516.jpg'),
(13, 'Gallery_Image_3420.jpeg'),
(16, 'Gallery_Image_2042.jpg'),
(18, 'Gallery_Image_2598.jpg'),
(19, 'Gallery_Image_4755.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membershipbookings`
--

CREATE TABLE `tbl_membershipbookings` (
  `id` int(15) UNSIGNED NOT NULL,
  `patient_id` int(10) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel_no` varchar(255) NOT NULL,
  `package` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_membershipbookings`
--

INSERT INTO `tbl_membershipbookings` (`id`, `patient_id`, `full_name`, `email`, `tel_no`, `package`, `message`, `status`, `booking_date`) VALUES
(12, 0, 'Haala', 'haala@gmail.com', '0112343567', 'VIP Membership', 'Yes', 'Confirmed', '2025-08-16 19:26:54'),
(13, 0, 'Dilum Dilshan', 'dilumdilshan2004@gmail.com', '0742909788', 'Basic Membership', 'Yes ', 'Confirmed', '2025-08-18 17:49:06'),
(15, 0, 'Dilum Dilshan', 'dilumdilshan2004@gmail.com', '0742909788', 'VIP Membership', 'Yes', 'Cancelled', '2025-08-18 19:53:09'),
(17, 0, 'Hiruni', 'hiruni@gmail.com', '0776897654', 'Basic Membership', 'Require wheelchair access', 'Confirmed', '2025-09-01 06:37:39'),
(18, 0, 'Monali', 'monali@gmail.com', '0777324567', 'Standard Membership', 'No additional details at this time.', 'Confirmed', '2025-09-01 07:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_packages`
--

CREATE TABLE `tbl_packages` (
  `id` int(15) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `price_local` decimal(10,2) NOT NULL,
  `price_foreigner` decimal(10,2) NOT NULL,
  `access` text NOT NULL,
  `features` text NOT NULL,
  `active` enum('Yes','No') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_packages`
--

INSERT INTO `tbl_packages` (`id`, `title`, `image_name`, `price_local`, `price_foreigner`, `access`, `features`, `active`) VALUES
(1, 'Basic Membership', 'Package_9223.jpg', 3000.00, 15.00, '[\"Access to general outpatient wellness clinics\",\"Participation in health awareness sessions\",\"Use of general waiting lounge & hospital facilities during visits\"]', '[\"Free monthly general checkup (BP, sugar, BMI)\",\"Optional consultation with dietician\",\" Access to non-urgent follow-up appointments\"]', 'Yes'),
(2, 'Standard Membership', 'Package_7573.jpeg', 6000.00, 20.00, '[\" Priority access to outpatient services\",\"Participation in disease-specific wellness programs (e.g., diabetes, heart care)\",\"Access to diagnostic labs (basic tests)\"]', '[\"Quarterly health screening package\",\"Diet & lifestyle counseling\",\"Follow-up consultations without extra charges\"]', 'Yes'),
(5, 'VIP Membership', 'Package_5469.jpg', 12000.00, 40.00, '[\"Dedicated access to premium care unit\",\"Private waiting & consultation areas\",\"Access to executive health check zones\",\"Priority appointments with specialists\",\"\"]', '[\"Full body annual medical checkup\",\"Personalized care coordinator\",\"Mental wellness and stress therapy sessions\",\"Massage & physiotherapy sessions (for recovery)\"]', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient`
--

CREATE TABLE `tbl_patient` (
  `patient_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `nic` varchar(12) DEFAULT NULL,
  `tel_no` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_no` varchar(15) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `registered_on` timestamp NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_patient`
--

INSERT INTO `tbl_patient` (`patient_id`, `full_name`, `gender`, `dob`, `nic`, `tel_no`, `address`, `email`, `emergency_contact_name`, `emergency_contact_no`, `blood_group`, `allergies`, `username`, `password`, `registered_on`, `profile_image`) VALUES
(19, 'Haala', 'Female', '2004-09-04', '123456784354', '0112343567', 'Galle', 'haala@gmail.com', 'qwe', '0773987653', 'AB+', 'no', 'haala', '$2y$10$p6LqfSLf9.FzpHEAbCLvfedY.ejcpeKqpUliTo7at6J9taH6ahMsC', '2025-08-13 04:11:49', 'patient_19.jpg'),
(20, 'Dilhani Gamage', 'Female', '2000-12-14', '200065343345', '0786590863', '', 'dilhani@gmail.com', 'Kumudu', '0773987653', 'AB+', 'ejijdejojojoe', 'dilhani', '$2y$10$/EGwho.VGi40tpQbgt/8AOF/F6fyFxZk857SrZ7JuVEaPfEdVe9rS', '2025-08-12 20:09:18', NULL),
(22, 'Amal', 'Male', '2000-09-05', '200012345634', '0763427687', 'Galle', 'amal@gmail.com', 'Amali', '0981234542', 'B+', 'ejijdejojojoe', 'amal', '$2y$10$iG4ldjM766qqZ2OYRnTXGuWu7Q2r/MqnRXBEJV47tJjKnjD27pzk6', '2025-08-15 17:55:49', 'patient_22.png'),
(38, 'Dilum Dilshan', 'Male', '2004-05-27', '200414800322', '0742909788', 'Galle', 'dilumdilshan2004@gmail.com', 'Asanka Silva', '0758412275', 'B+', 'No', 'dilum', '$2y$10$xqLrn7M8bdpaDqaUuV4fEOzzh.gplY8aDKgwtGjvr5Dr7aU4oDk5i', '2025-08-18 16:27:19', 'patient_38.jpg'),
(39, 'Hiruni', 'Female', '2002-02-13', '200289776609', '0776897654', 'Galle', 'hiruni@gmail.com', 'Kalhari', '0777989876', 'B+', 'None', 'hiruni', '$2y$10$9I.EMG64OpIqBYG13VA5Wus9Ra/FB0WagkYo23fMoCyXHnWW9gUyK', '2025-09-01 06:13:40', ''),
(43, 'Monali', 'Female', '2003-02-12', '200389776898', '0777324567', 'Galle', 'monali@gmail.com', 'Sajith', '0785989765', 'B+', 'None', 'monali', '$2y$10$vzdj/4uDkyT0lWlByRYzfeImKpAHkbIuq4ygY9Onukw7Axxb1IjOO', '2025-09-01 07:25:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programbooking`
--

CREATE TABLE `tbl_programbooking` (
  `id` int(10) UNSIGNED NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel_no` varchar(20) DEFAULT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `program_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `age` int(10) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_programbooking`
--

INSERT INTO `tbl_programbooking` (`id`, `patient_id`, `full_name`, `email`, `tel_no`, `category_id`, `program_id`, `doctor_id`, `age`, `message`, `status`, `booking_date`) VALUES
(1, 1, 'Lakshi Sewwandi', 'lak@gmail.com', '0773278999', 1, 11, 2, 26, 'I need to gain the weight and be healthy', 'Confirmed', '2024-10-19 17:30:17'),
(2, 2, 'Haritha Perera', 'haritha@gmail.com', '0773278985', 2, 8, NULL, 58, 'I need to build mussel and gain weight. please help me to achieve that. ', 'Confirmed', '2024-10-19 17:35:36'),
(3, 3, 'Kaveesha Silva', 'kaveesha@gmail.com', '0773278985', 1, 12, NULL, 43, 'I need to join this class. can I know about this class more.', 'Confirmed', '2024-10-19 17:59:04'),
(4, 4, 'Nimal perera', 'nimal@gmail.com', '0775689589', 2, 13, NULL, 60, 'I would like to join with this trainer to achieve my dream body.I think you will contact me soon. ', 'Confirmed', '2024-10-20 08:32:10'),
(5, 5, 'Dilum Dilshan', 'dilum@gmail.com', '0776758989', 4, 15, NULL, 60, 'I need to lose the weight and make my dream figure', 'Cancelled', '2024-10-21 04:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programcategories`
--

CREATE TABLE `tbl_programcategories` (
  `id` int(15) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `active` enum('Yes','No') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_programcategories`
--

INSERT INTO `tbl_programcategories` (`id`, `title`, `image_name`, `active`, `created_at`) VALUES
(1, 'Operation Theatre', 'Category_8453.jpg', 'Yes', '2024-10-18 11:07:46'),
(2, 'Laboratory Service', 'Category_9652.png', 'Yes', '2024-10-18 11:13:33'),
(4, 'OPD/ETU', 'Category_7678.jpg', 'Yes', '2024-10-18 11:46:21'),
(9, 'Specialist Consultation', 'Program_Category_5805.jpg', 'Yes', '2025-06-08 07:33:43'),
(10, 'Mother & Baby Care', 'Program_Category_2705.jpg', 'Yes', '2025-06-14 06:10:45'),
(11, 'Dental Emergency', 'Program_Category_6892.jpg', 'Yes', '2025-06-14 06:11:11'),
(12, 'Neurology', 'Program_Category_7428.jpg', 'Yes', '2025-08-11 13:04:29'),
(13, 'Orthopedics', 'Program_Category_7080.jpg', 'Yes', '2025-08-11 13:53:07'),
(14, 'Dermatology', 'Program_Category_2673.jpg', 'Yes', '2025-08-11 14:43:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programs`
--

CREATE TABLE `tbl_programs` (
  `id` int(15) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `trainer_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `price_local` decimal(10,2) NOT NULL,
  `price_foreign` decimal(10,2) NOT NULL,
  `schedule` text NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `features` text NOT NULL,
  `active` varchar(3) DEFAULT 'No',
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_programs`
--

INSERT INTO `tbl_programs` (`id`, `category_id`, `trainer_id`, `title`, `description`, `price_local`, `price_foreign`, `schedule`, `image_name`, `features`, `active`, `created_at`) VALUES
(32, 1, NULL, 'Heart (bypass) operation', 'A critical heart surgery for patients with blocked coronary arteries. This bypass procedure improves blood flow to the heart and reduces the risk of heart attacks. Ideal for patients with chronic chest pain or coronary artery disease (CAD).', 48000.00, 120.00, '[\"Wednesday: (8.00 AM - 10.00 PM)\",\"Friday: (8.00 AM - 10.00 PM)\",\"Sunday: (8.00 AM - 9.00 PM)\"]', 'Program_Image_9563.jpg', '[\"Performed by a board-certified cardiac surgeon\",\"Includes pre-op ECG, blood work, and consultation\",\"2-day ICU care and 5-day recovery ward stay\",\"Post-op follow-up plan and dietary support\"]', 'Yes', 0),
(33, 2, 5, 'Blood Pressure Test', 'This test measures the pressure in your arteries as your heart pumps. Ideal for individuals with hypertension, diabetes, or those undergoing regular checkups.', 2000.00, 20.00, '[\"Monday : (8.00 A.M - 10.00 P.M)\",\"Friday: (8.00 AM - 10.00 PM)\",\"Sunday: (8.00 AM - 12.00 PM)\"]', 'Program_Image_7426.jpg', '[\"Fast & accurate readings\",\"Monitored by certified nurses\",\"Results available instantly\"]', 'Yes', 0),
(36, 9, NULL, 'Depression Counseling', 'Depression Counseling offers professional psychological support for individuals experiencing sadness, lack of motivation, or emotional distress. Through guided therapy sessions, patients learn to understand the root causes of depression and develop strategies to cope and recover in a safe and non-judgmental environment.', 5000.00, 50.00, '[\"Sunday: (8.00 AM - 8.00 PM)\"]', 'Program_Image_3161.jpg', '[\"Confidential 1-on-1 sessions\",\"Certified psychologists and counselors\",\"Supportive therapy environment\",\"Personalized coping strategies\"]', 'Yes', 0),
(37, 10, NULL, 'Childbirth (දරු ප්‍රසුතිය)', 'Comprehensive maternity service offering safe and professional childbirth care in a hospital setting. Includes labor support, normal delivery, pain relief options, and postnatal observation for both mother and baby. Our trained staff ensures comfort, safety, and dignity throughout the process.\r\n\r\n', 150000.00, 500.00, '[\"Available 24\\/7 \"]', 'Program_Image_3671.jpg', '[\"24\\/7 labor room and delivery services\",\"Supervised by obstetricians and midwives\",\"Pain management and emergency readiness\",\"Post-delivery monitoring and support\"]', 'Yes', 0),
(38, 11, NULL, 'Oral surgery', 'Oral surgery services are designed to treat complex dental conditions such as impacted teeth, jaw cysts, and severe infections. Procedures are performed under sterile conditions by experienced oral surgeons with full pre- and post-surgical support to ensure safe and comfortable recovery.', 5000.00, 50.00, '[\"Sunday: (8.00 AM - 8.00 PM)\",\"Monday : (8.00 A.M - 10.00 P.M)\",\"Wensday: (8.00 AM - 10.00 PM)\"]', 'Program_Image_2351.jpg', '[\"Tooth extraction (including impacted wisdom teeth)\",\"Surgical treatment of oral infections\",\"Pre-surgical evaluation by dental surgeons\",\"Post-operative care and pain management\"]', 'Yes', 0),
(39, 1, NULL, 'Cataract Surgery', 'A safe and quick outpatient procedure to restore vision by replacing a clouded lens. Suitable for elderly patients or anyone diagnosed with cataracts.', 40000.00, 60.00, '[\"Monday(9:00 AM \\u2013 4:00 PM)\",\"Friday (9:00 AM \\u2013 4:00 PM)\"]', 'Program_Image_5530.jpg', '[\"Advanced laser-assisted procedure\",\"Includes lens implant and 1-day recovery\",\"Post-op eye care kit provided\",\"Vision check-up after 1 week included\"]', 'Yes', 0),
(40, 1, NULL, ' Appendix Removal (Appendectomy)', 'Emergency surgical removal of an inflamed or infected appendix. This common procedure helps prevent complications such as rupture or abscess formation.\r\n\r\n', 18000.00, 90.00, '[\"Wednesday(10:00 AM \\u2013 8:00 PM)\",\"Saturday (10:00 AM \\u2013 8:00 PM)\",\"Sunday (10:00 AM \\u2013 4:00 PM)\"]', 'Program_Image_1672.jpg', '[\"Laparoscopic and open options available\",\"Same-day discharge for most patients\",\"Includes antibiotics and post-surgery pain care\",\"24\\/7 emergency surgery availability\"]', 'Yes', 0),
(41, 2, NULL, ' COVID-19 PCR Test', 'The COVID-19 PCR test detects the presence of SARS-CoV-2, the virus causing COVID-19. It is the gold standard for accuracy and widely used for travel, work clearance, and clinical diagnosis. Results are available within 12 hours, with certified reports accepted by airlines and institutions.\r\n\r\n', 45000.00, 35.00, '[\"Monday(9:00 AM \\u2013 12:00 PM)\",\"Wednesday (9:00 AM \\u2013 12:00 PM)\",\"Friday (9:00 AM \\u2013 12:00 PM)\"]', 'Program_Image_1049.jpeg', '[\"RT-PCR test \",\"Travel certificate issued\",\"Same-day results available\",\"Nasal and throat swab collection\"]', 'Yes', 0),
(42, 2, NULL, 'Diabetes Screening', 'A comprehensive diabetes screening package to detect and monitor blood glucose levels. Recommended for individuals with risk factors such as obesity, family history, or frequent urination.\r\n\r\n', 25000.00, 22.00, '[\"Monday(9:00 AM \\u2013 4:00 PM)\",\"Thursday(9:00 AM \\u2013 4:00 PM)\",\"Sunday(9:00 AM \\u2013 4:00 PM)\"]', 'Program_Image_7636.webp', '[\"Fasting & random blood sugar tests\",\"HbA1c levels included\",\"Certified lab diagnostics\"]', 'Yes', 0),
(44, 4, NULL, 'Injection Administration', 'Safe and hygienic injection service for antibiotics, vitamins, insulin, and other prescribed medications. Administered in accordance with medical standards.', 800.00, 12.00, '[\"Available 24\\/7\"]', 'Nutrition_Image_2878.jpg', '[\"Intramuscular, subcutaneous & IV injections\",\"Single-use sterile syringes\",\"Administered by certified nurses\"]', 'Yes', 0),
(45, 4, NULL, 'Wound Dressing', 'Professional wound care service to clean, dress, and manage different types of injuries including surgical wounds, diabetic ulcers, and accidental cuts. Ensures faster healing and reduced infection risk under medical supervision.', 1500.00, 20.00, '[\"Available 24\\/7\"]', 'Program_Image_5559.jpg', '[\"Sterile dressing materials\",\"Infection control assurance\",\"Handled by trained nurses\",\"Pain-free cleaning techniques\"]', 'Yes', 0),
(46, 4, NULL, 'Nebulization Service', 'Nebulization helps open airways for easier breathing. Suitable for children and adults suffering from respiratory distress, asthma, or bronchitis.', 800.00, 12.00, '[\"Available 24\\/7\"]', 'Program_Image_4763.jpg', '[\"For asthma, COPD & respiratory infections\",\"Sterile and disposable kits\",\"Supervised by respiratory therapists\"]', 'Yes', 0),
(47, 9, NULL, 'Addiction Recovery Counseling', 'Designed for individuals struggling with drug, alcohol, or behavioral addictions. This program provides therapy to understand addiction cycles, build resilience, and develop long-term recovery plans with professional guidance and peer support.', 7000.00, 65.00, '[\"Wednesday (10:00 A.M \\u2013 6:00 P.M)\",\" Saturday (10:00 A.M \\u2013 6:00 P.M)\"]', 'Program_Image_3298.webp', '[\"Substance abuse and behavioral addiction therapy\",\"Relapse prevention strategies\",\"Psychological and social support\",\"Works in coordination with rehab centers\"]', 'Yes', 0),
(48, 9, NULL, ' Child Behavioral Therapy', 'This service helps children (ages 4–12) manage behavioral issues such as aggression, hyperactivity, and difficulty with attention. Sessions use play, storytelling, and family-based strategies to improve behaviour and emotional control in a safe, supportive setting.', 5500.00, 50.00, '[\"Thursday (9:00 A.M \\u2013 4:00 P.M)\",\"Sunday(9:00 A.M \\u2013 12:00 P.M)\"]', 'Program_Image_8788.webp', '[\"Conducted by pediatric psychologists\",\"Play-based therapy techniques\",\"Behavior modification plans\",\"Parent involvement sessions\"]', 'Yes', 0),
(49, 10, NULL, 'Newborn Baby Care & Monitoring', 'A dedicated newborn care service to ensure the baby’s first days are healthy and safe. Our pediatricians monitor physical development, feeding habits, reflexes, and early signs of illness. Support is also provided to new mothers on breastfeeding and baby handling.\r\n\r\n', 4000.00, 45.00, '[\"Monday (7:00 A.M \\u2013 6:00 P.M)\",\"Thursday (7:00 A.M \\u2013 6:00 P.M)\",\"Friday (7:00 A.M \\u2013 6:00 P.M)\",\"Sunday (7:00 A.M \\u2013 12:00 P.M)\"]', 'Program_Image_5390.jpg', '[\"Neonatal examination and health screening\",\"Baby weight, temperature, and feeding checks\",\"Immunization counseling and scheduling\",\"Mother-infant bonding support\"]', 'Yes', 0),
(50, 10, NULL, 'Postnatal Mother Care Program', 'A complete care package for mothers in the weeks following childbirth. Includes physical recovery monitoring, mental health support, family planning advice, and education on postnatal hygiene, nutrition, and safe practices at home.\r\n\r\n', 6000.00, 55.00, '[\"Monday (7:00 A.M \\u2013 6:00 P.M)\",\"Wednesday: (7.00 AM - 6.00 PM)\",\"Friday (7:00 A.M \\u2013 6:00 P.M)\"]', 'Program_Image_6516.jpg', '[\"Uterine recovery & wound checkups\",\"Nutritional and emotional support\",\"Guidance for physical activity and sleep\",\"Postpartum depression screening\"]', 'Yes', 0),
(51, 11, NULL, 'Dental Check-Up & Cleaning', 'A preventive care service aimed at maintaining oral health. Includes a complete dental check-up, professional cleaning, and personalized recommendations for daily care. Ideal for children, adults, and elderly patients as a part of routine dental maintenance.', 2000.00, 20.00, '[\"Tuesday (9:00 A.M \\u2013 5:00 P.M)\",\"Friday (9:00 A.M \\u2013 5:00 P.M)\"]', 'Program_Image_1524.jpeg', '[\"Full oral examination\",\"Professional teeth cleaning and polishing\",\"Plaque and tartar removal\",\"Dental hygiene guidance\"]', 'Yes', 0),
(52, 11, NULL, 'Root Canal Treatment (RCT)', 'Root Canal Treatment is offered for patients with severe tooth pain due to infected pulp. This service removes the infection, cleans and seals the tooth, and prevents extraction. Performed using advanced techniques for maximum comfort and effectiveness.\r\n\r\n', 8000.00, 70.00, '[\"Monday (10:00 A.M \\u2013 6:00 P.M)\",\"Thursday (10:00 A.M \\u2013 6:00 P.M)\",\"Saturday (10:00 A.M \\u2013 6:00 P.M)\"]', 'Program_Image_5125.jpg', '[\"Pain-free root canal procedure\",\"Digital X-ray and cavity evaluation\",\"Infection removal and tooth preservation\",\"Follow-up visit included\"]', 'Yes', 0),
(53, 12, NULL, 'Neurodiagnostics & Imaging', 'Comprehensive neurological diagnostic services using advanced imaging and testing to detect and evaluate brain, nerve, and spinal conditions.', 2000.00, 80.00, '[\"Monday : (8.00 A.M - 10.00 P.M)\",\"Saturday : (8.00 A.M - 10.00 P.M)\"]', 'Nutrition_Image_2927.jpeg', '[\"EEG (Electroencephalogram) & EMG (Electromyography) tests\",\"Nerve conduction studies\",\"MRI & CT brain and spine scans\",\"Detailed neurologist review and reporting\"]', 'Yes', 0),
(54, 13, NULL, 'Sports Medicine & Injury Prevention', 'goodSpecialized orthopedic services for athletes, sports enthusiasts, and active individuals to prevent injuries, speed recovery, and enhance physical performance. Includes advanced assessments and personalized training advice.', 1500.00, 65.00, '[\"Available 24\\/7\"]', 'Nutrition_Image_8631.jpeg', '[\"Sports-specific orthopedic evaluations\",\"Injury prevention & performance optimization programs\",\"Customized exercise and flexibility training\",\"Rapid return-to-play rehabilitation plans\"]', 'Yes', 0),
(55, 14, NULL, 'Clinical Dermatology', 'Comprehensive diagnosis and treatment of skin, hair, and nail conditions for all age groups, using evidence-based medical approaches.', 1000.00, 60.00, '[\"Monday : (8.00 A.M - 10.00 P.M)\",\"Saturday : (8.00 A.M - 10.00 P.M)\"]', 'Nutrition_Image_6624.jpeg', '[\"Acne, eczema, and psoriasis management\",\"Diagnosis and treatment of skin infections\",\"Allergy testing and skin patch tests\",\"Hair and nail disorder treatments\"]', 'Yes', 0),
(56, 13, NULL, 'Pediatric Orthopedics', 'Dedicated orthopedic care for infants, children, and teenagers with growth-related, developmental, or congenital bone and joint problems.', 1200.00, 55.00, '[\"Monday (10:00 A.M \\u2013 6:00 P.M)\",\"Friday (10:00 A.M \\u2013 6:00 P.M)\",\"Sunday  (10:00 A.M \\u2013 6:00 P.M)\"]', 'Program_Image_7303.jpeg', '[\"Early diagnosis of growth & bone disorders\",\"Scoliosis and posture correction treatments\",\"Limb deformity correction programs\",\"Child-friendly physiotherapy & rehabilitation\"]', 'Yes', 0),
(57, 13, NULL, 'Orthopedic Geriatric Care', 'Comprehensive orthopedic services for older adults to improve mobility, reduce pain, and maintain independence. Special focus on arthritis care and fall prevention.', 1300.00, 60.00, '[\"Monday  (9:00 A.M \\u2013 7:00 P.M)\",\"Wednesday (9:00 A.M \\u2013 7:00 P.M)\",\"Saturday (9:00 A.M \\u2013 12:00 P.M)\"]', 'Program_Image_7657.jpeg', '[\"Arthritis & chronic pain management\",\"Hip and knee joint care\",\"Fall risk assessment and prevention programs\",\"Gentle physiotherapy & mobility improvement exercises\"]', 'Yes', 0),
(58, 12, NULL, 'Stroke Care & Rehabilitation', 'Specialized treatment and rehabilitation for patients recovering from strokes to restore mobility, speech, and cognitive functions.', 2500.00, 100.00, '[\"Monday : (8.00 A.M - 10.00 P.M)\",\"Sunday: (8.00 AM - 8.00 PM)\"]', 'Program_Image_2720.jpeg', '[\"Immediate stroke assessment and management\",\"Physiotherapy and occupational therapy\",\"Speech and language therapy\",\"Long-term recovery monitoring\"]', 'Yes', 0),
(59, 12, NULL, 'Pediatric Neurology', 'Neurological care for children with developmental, genetic, or injury-related brain and nerve conditions.', 2200.00, 90.00, '[\"Monday : (8.00 A.M - 10.00 P.M)\",\"Wednesday (9:00 A.M \\u2013 7:00 P.M)\",\"Sunday: (8.00 AM - 10.00 PM)\"]', 'Nutrition_Image_7898.jpeg', '[\"Diagnosis and treatment of epilepsy & seizures\",\"Developmental delay assessment\",\"Management of cerebral palsy & muscular disorders\",\"Family counseling and ongoing monitoring\"]', 'Yes', 0),
(60, 14, NULL, 'Cosmetic & Aesthetic Dermatology', 'Non-surgical cosmetic skin treatments to enhance appearance, rejuvenate skin, and boost confidence.', 2500.00, 95.00, '[\"Monday : (8.00 A.M - 10.00 P.M)\",\"Wednesday: (8.00 A.M - 10.00 P.M)\",\"Sunday: (8.00 AM - 10.00 PM)\"]', 'Program_Image_8858.jpeg', '[\"Chemical peels and microdermabrasion\",\"Anti-aging treatments (Botox, fillers)\",\"Laser skin resurfacing\",\"Pigmentation and scar reduction treatments\"]', 'Yes', 0),
(61, 14, NULL, 'Chronic Skin Disease Management', 'Specialized long-term care for persistent skin disorders, focusing on effective treatment, flare-up control, and patient education.', 1800.00, 70.00, '[\"Monday : (8.00 A.M - 10.00 P.M)\",\"Wednesday: (8.00 A.M - 10.00 P.M)\",\"Sunday: (8.00 AM - 8.00 PM)\"]', 'Program_Image_4934.jpeg', '[\"Psoriasis, vitiligo, and pityriasis treatment programs\",\"Eczema and chronic dermatitis management\",\"Lichen planus and autoimmune skin condition care\",\"Lifestyle guidance to prevent recurrences\"]', 'Yes', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_promotions`
--

CREATE TABLE `tbl_promotions` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `program_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_rate` decimal(5,2) NOT NULL,
  `discounted_price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `active` enum('Yes','No') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_promotions`
--

INSERT INTO `tbl_promotions` (`id`, `title`, `program_name`, `price`, `discount_rate`, `discounted_price`, `description`, `image_name`, `active`) VALUES
(1, 'VIP Privilege Offer', 'VIP Executive Health Package', 12000.00, 15.00, 10200.00, 'Exclusive offer for VIP members including full-body diagnostics, priority appointments, specialist consultations, and wellness planning. Enjoy premium care at a discounted rate.', 'Promotion_Image_9393.png', 'Yes'),
(2, 'Smoker\'s Day Special – Free Lung Health Check', 'Respiratory Screening Program', 1500.00, 100.00, 0.00, 'In honor of Smoker\'s Day, we offer a free lung function test (spirometry) and chest consultation for smokers. Raise awareness and take a step toward better respiratory health.\r\n\r\n', 'Promotion_Image_7335.jpg', 'Yes'),
(7, 'Women\'s Wellness Return ', 'Women’s Preventive Health Package', 5000.00, 30.00, 3500.00, 'Designed for women resuming work after holidays. Includes CBC, vitamin D & B12, thyroid screening (TSH), Pap smear, breast exam, and a wellness consultation. Prioritize your health before getting back to your routine.\r\n\r\n', 'Promotion_Image_2251.webp', 'Yes'),
(10, 'Senior Wellness Package (60+ Years)', 'special hospital promotion package for seniors (60+ years)', 8000.00, 35.00, 5200.00, 'The Senior Wellness Package (60+ Years) ensures early detection and prevention with blood tests, heart check, bone & joint screening, eye & hearing tests, cancer screening, and a doctor’s consultation for personalized health guidance.', 'Promotion_Image_5165.png', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_success_stories`
--

CREATE TABLE `tbl_success_stories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_success_stories`
--

INSERT INTO `tbl_success_stories` (`id`, `title`, `description`, `email`, `image_name`) VALUES
(2, 'Rapid Emergency Response Saves John’s Life', 'John, a 55-year-old man, arrived at the emergency department with symptoms of a heart attack. Thanks to the hospital’s advanced HMS, his medical history and allergy information were instantly available to the ER team through integrated electronic health records. The system’s real-time alerts triggered the emergency protocol, enabling doctors to quickly order tests, prepare necessary medications, and activate the cardiac care team. Within minutes, John received critical treatment, and the timely intervention prevented major heart damage. John credits the hospital’s seamless digital system for saving his life and enabling a smooth recovery.\r\n\r\n', 'john@gmail.com', 'Success_Story_4020.jpg'),
(3, 'Seamless Diabetes Management for Maria', 'Maria, a 42-year-old woman newly diagnosed with Type 2 diabetes, was initially overwhelmed by the complexity of managing her condition. Her hospital’s HMS played a crucial role by providing personalized care plans, easy access to her lab results, medication schedules, and educational resources through a patient portal. Regular reminders for appointments and medication adherence helped Maria stay on track. Over six months, her blood sugar levels stabilized, and she regained confidence in managing her health. Maria’s story showcases how HMS technology empowers patients with chronic diseases to take control of their care.', 'maria@example.com', 'Success_Story_7760.avif'),
(4, 'Coordinated Cancer Care for Mr. Kumar', 'Mr. Kumar, diagnosed with lung cancer, required multidisciplinary treatment involving oncologists, radiologists, surgeons, and nursing staff. The hospital’s HMS ensured that all specialists had real-time access to his diagnostic reports, treatment plans, and progress notes, improving communication and reducing delays. Appointment scheduling and follow-ups were coordinated efficiently, minimizing the stress of navigating complex care. Thanks to this coordinated approach facilitated by the HMS, Mr. Kumar’s treatment was timely and well-organized, leading to successful remission and improved quality of life.\r\n\r\n', 'kumar@gmail.com', 'Success_Story_3533.jpg'),
(5, 'Pediatric Surgery Success for Little Aisha', 'Little Aisha, a 7-year-old girl with a congenital heart defect, needed urgent surgery. The hospital’s HMS enabled a smooth workflow from pre-surgical assessments to post-operative care. Her medical records, imaging results, and surgical notes were accessible across departments, allowing the surgical team to prepare thoroughly. The system’s automated scheduling minimized wait times, and post-surgery monitoring was closely tracked through digital records. Aisha’s parents expressed gratitude for the attentive care and smooth process, made possible by the hospital’s efficient management system.', 'kasuni@gmail.com', 'Success_Story_8319.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_reports`
--
ALTER TABLE `lab_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`prescription_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_blogs`
--
ALTER TABLE `tbl_blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_gallery`
--
ALTER TABLE `tbl_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_membershipbookings`
--
ALTER TABLE `tbl_membershipbookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_packages`
--
ALTER TABLE `tbl_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `tbl_programbooking`
--
ALTER TABLE `tbl_programbooking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`patient_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `tbl_programcategories`
--
ALTER TABLE `tbl_programcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_programs`
--
ALTER TABLE `tbl_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_promotions`
--
ALTER TABLE `tbl_promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_success_stories`
--
ALTER TABLE `tbl_success_stories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `lab_reports`
--
ALTER TABLE `lab_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(25) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_blogs`
--
ALTER TABLE `tbl_blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_gallery`
--
ALTER TABLE `tbl_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_membershipbookings`
--
ALTER TABLE `tbl_membershipbookings`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_packages`
--
ALTER TABLE `tbl_packages`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tbl_programbooking`
--
ALTER TABLE `tbl_programbooking`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_programcategories`
--
ALTER TABLE `tbl_programcategories`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_programs`
--
ALTER TABLE `tbl_programs`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `tbl_promotions`
--
ALTER TABLE `tbl_promotions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_success_stories`
--
ALTER TABLE `tbl_success_stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
