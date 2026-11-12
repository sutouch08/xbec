window.addEventListener('load', function() {
  setInterval(syncApproveOrder, 5000); // Sync every 5 seconds
  syncApproveOrder(); // Initial sync on page load
});

async function syncApproveOrder() {
 let url = `${BASE_URL}orders/order_approval/count_approval_orders`;

 try {
   let response = await fetch(url);
   if(response.ok) {
     let data = await response.json();
     let count = data.count;

     if(count > 0) {
       $('#menu-group-AP').text(count);
       $('#menu-group-AP').removeClass('hide');
     }     
     else {
       $('#menu-group-AP').text('');
       $('#menu-group-AP').addClass('hide');
     }
   }
 } catch (error) {
   console.error('Error fetching approval orders:', error);
 }
}