document.addEventListener('DOMContentLoaded', function () {

   // rewrite above function to vanilla js
   document.body.addEventListener('added_to_cart', function () {
      var count = document.querySelector('.count-cart').textContent;
      document.getElementById('js-cart-link').dataset.count = count;
   });
});