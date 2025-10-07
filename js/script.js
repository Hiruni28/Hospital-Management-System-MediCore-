document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const status = urlParams.get('status');
  const action = urlParams.get('action');

  console.log('Status:', status, 'Action:', action);

  if (status && action) {
      let message = '';
      switch (action) {
          case 'add':
              message = (status === 'success') ? 'Admin added successfully!' : 'Failed to add admin.';
              break;
          case 'delete':
              message = (status === 'success') ? 'Admin deleted successfully!' : 'Failed to delete admin.';
              break;
          case 'update':
              message = (status === 'success') ? 'Admin updated successfully!' : 'Failed to update admin!';
              break;
          case 'login':
              message = (status === 'success') ? 'Login successful!' : 'Login failed! Username or password did not match! Try again.';
              break;
          case 'no-login-message':
              message = 'Please login to access the admin panel.';
              break;
          case 'add_membership':
              message = (status === 'success') ? 'Adding Membership package successful!' : 'Failed to add package!';
              break; 
              
          case 'upload':
              message = (status === 'success') ? 'Image uploaded successfully!' : 'Failed to upload image.';
              break;
          case 'add_category':
              message = (status === 'success') ? 'Service Category added successfully!' : 'Failed to add category.';
              break;
          case 'delete_category':
              message = (status === 'success') ? 'Service Category deleted successfully!' : 'Failed to delete category.';
              break;
          case 'booking':
              message = (status === 'success') ? 'Service Booking successful!' : 'Booking failed!';
              break;

          case 'delete_package':
                message = (status === 'success') ? 'Deleted Membership Package succeffully!' : 'failed to Deleted Package!';
                break;

          case 'update_package':
                message = (status === 'success') ? 'Updated Membership Package succeffully!' : 'failed to update Package!';
                break;
          case 'not_found':
                message = (status === 'success') ? '' : 'not found package Package!';
                break;

          case 'Membershipbooking':
                message = (status === 'success') ? 'Membership Booking Successfully our staff member will contact you soon.' : 'failed to Booking Package!';
                break;

          case 'add_category':
                message = (status === 'success') ? 'Service Category Successfully added.' : 'failed to add category!';
                break;

          case 'delete_category':
                message = (status === 'success') ? 'Service Category Deleted successfully!.' : 'failed to delete category!';
                break;

                
          case 'update_category':
                message = (status === 'success') ? 'Service Category Updated successfully!.' : 'failed to update category!';
                break;
            
          case 'add_program':
                message = (status === 'success') ? 'Service added successfully!.' : 'failed to add Service!';
                break;

          case 'delete_program':
                message = (status === 'success') ? 'Service deleted successfully!.' : 'failed to delete Service!';
                break;

          case 'add_trainer':
                message = (status === 'success') ? 'Service added successfully!.' : 'failed to add Service!';
                break;
    
               
          case 'update_trainer':
                message = (status === 'success') ? 'Service updated successfully!.' : 'failed to update Service!';
                break; 

          case 'delete_trainer':
                message = (status === 'success') ? 'Service deleted successfully!.' : 'failed to delete Service!';
                break;     


          case 'add_trainerprogram':
                message = (status === 'success') ? 'Service program added successfully!.' : 'failed to add Training program r!';
                    break;  
                
          case 'update_program':
                message = (status === 'success') ? 'Service program updated successfully!.' : 'failed to update Training program r!';
                break;  
                
                           
          case 'update_class':
                message = (status === 'success') ? 'Service updated succeffully!' : 'failed to update Program!';
                break;

          case 'delete_class':
                message = (status === 'success') ? 'Service updated succeffully!' : 'failed to update Program!';
                break;
 
          case 'add_promotion':
                message = (status === 'success') ? 'Promotion added succeffully!' : 'failed to add Promotion!';
                break;

          case 'update_promotion':
                message = (status === 'success') ? 'Promotion updated succeffully!' : 'failed to update Promotion!';
                break;
    
                
          case 'delete_promotion':
                message = (status === 'success') ? 'Promotion Deleted succeffully!' : 'failed to deleted Promotion!';
                break;
                    
          case 'success_sent':
                message = (status === 'success') ? 'Message sent succeffully!' : 'failed to send message!';
                break;

          case 'delete_message':
                message = (status === 'success') ? 'Message Deleted succeffully!' : 'failed to delete message!';
                break;

          case 'programBooking':
                message = (status === 'success') ? 'ServiceBooking succeffully! Our one of team member will contact you soon.have a wondaful day!' : 'failed to book program!';
                break;

          case 'confirm_booking':
                message = (status === 'success') ? 'Confirm succeffully!' : 'failed to confirm booking!';
                break;
        
          case 'cancel_booking':
                message = (status === 'success') ? 'cancelation succeffully!' : 'failed to cancel booking!';
                break;
                        
                    
                    
          case 'add_events':
                message = (status === 'success') ? 'Event Added succeffully!' : 'failed to add events!';
                break;

           
          case 'update_events':
                message = (status === 'success') ? 'Event updated succeffully!' : 'failed to update events!';
                break;

          case 'delete_events':
                message = (status === 'success') ? 'Event deleted succeffully!' : 'failed to delete events!';
                break;


          case 'success_feedback':
                message = (status === 'success') ? 'Feedback submitted succeffully!' : 'failed to submit feedback!';
                break;

          case 'add_blog_success':
                message = (status === 'success') ? 'Blog added succeffully!' : 'failed to add blog!';
                break;

          case 'delete_blog':
                message = (status === 'success') ? 'Blog deleted succeffully!' : 'failed to detele blog!';
                break;
                
           case 'update_blog':
                message = (status === 'success') ? 'Blog updated succeffully!' : 'failed to update blog!';
                break;  

           case 'update_image':
                message = (status === 'success') ? 'image updated succeffully!' : 'failed to update image!';
                break;  

           case 'add_image':
                message = (status === 'success') ? 'image added succeffully!' : 'failed to add image!';
                break;  

           case 'delete_image':
                message = (status === 'success') ? 'image deleted succeffully!' : 'failed to delete image!';
                break; 

           case 'add_success_story':
                message = (status === 'success') ? 'Success Story added succeffully!' : 'failed to added Success Story image!';
                break; 

          case 'delete_success_story':
                message = (status === 'success') ? 'Success Story deleted succeffully!' : 'failed to delete Success Story image!';
                break; 

          case 'update_success_story':
                message = (status === 'success') ? 'Success Story updated succeffully!' : 'failed to update Success Story image!';
                break; 
            
          case 'login_cus':
              message = (status === 'success') ? 'Login successful!' : 'Login failed!';
              break;
          case 'customer':
              message = (status === 'success') ? 'Registation successfully!' : 'Registation failed!';
              break;

          case 'order':
              message = (status === 'success') ? 'Order successfully!' : 'Order failed! try again!';
              break;
          case 'customer_delete':
              message = (status === 'success') ? 'Customer Delete successfully!' : 'failed to delete customer!';
              break;

          case 'feedback':
              message = (status === 'success') ? 'Your Feddback successfully submitted' : 'Failed to submit feedback.Try again!';
              break;
          case 'contacts':
              message = (status === 'success') ? 'successfully submitted' : 'Failed to submit.Try again!';
              break;

          case 'doc_add':
                message = (status === 'success') ? 'Doctor Added succeffully!' : 'failed to add doctor!';
                break;
           
          case 'doc_update':
                message = (status === 'success') ? 'Doctorupdated succeffully!' : 'failed to update doctor!';
                break;

          case 'doc_delete':
                message = (status === 'success') ? 'Doctor deleted succeffully!' : 'failed to delete doctor!';
                break;


          default:
              return;
      }

      alert(message);

      // Remove parameters from URL
      urlParams.delete('status');
      urlParams.delete('action');
      const newUrl = urlParams.toString() ? `${window.location.pathname}?${urlParams.toString()}` : window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);
  }
});







function addAccess() {
    const accessList = document.getElementById('access-list');
    const newAccess = document.createElement('input');
    newAccess.setAttribute('type', 'text');
    newAccess.setAttribute('name', 'access[]'); 
    newAccess.setAttribute('placeholder', 'Enter Access Detail');
    accessList.appendChild(newAccess);
    accessList.appendChild(document.createElement('br')); 
}

function addFeature() {
    const featureList = document.getElementById('feature-list');
    const newFeature = document.createElement('input');
    newFeature.setAttribute('type', 'text');
    newFeature.setAttribute('name', 'features[]');
    newFeature.setAttribute('placeholder', 'Enter Feature Detail');
    featureList.appendChild(newFeature);
    featureList.appendChild(document.createElement('br'));
}



// Function to dynamically add more access fields in update package
function updateAccess() {
    const accessList = document.getElementById('access-list');
    const newAccess = document.createElement('input');
    newAccess.setAttribute('type', 'text');
    newAccess.setAttribute('name', 'access[]');
    newAccess.setAttribute('placeholder', 'Enter Access Detail');
    accessList.appendChild(newAccess);
    accessList.appendChild(document.createElement('br'));
}

// Function to dynamically add more feature fields in update package
function updateFeature() {
    const featureList = document.getElementById('feature-list');
    const newFeature = document.createElement('input');
    newFeature.setAttribute('type', 'text');
    newFeature.setAttribute('name', 'features[]');
    newFeature.setAttribute('placeholder', 'Enter Feature Detail');
    featureList.appendChild(newFeature);
    featureList.appendChild(document.createElement('br'));
}



function addSchedule() {
    const scheduleList = document.getElementById('schedule-list');
    const newSchedule = document.createElement('input');
    newSchedule.setAttribute('type', 'text');
    newSchedule.setAttribute('name', 'schedule[]');
    newSchedule.setAttribute('placeholder', 'Enter Date and Time');
    newSchedule.classList.add('schedule-input'); // Adding class for styling (if needed)

    scheduleList.appendChild(newSchedule);
    scheduleList.appendChild(document.createElement('br')); // Line break for spacing
}



