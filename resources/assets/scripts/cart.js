export default function () {
   console.log('cart.js');
   document.body.addEventListener('added_to_cart', function () {
      var count = document.querySelector('.js-cart-count').textContent;
      document.getElementById('js-cart-link').dataset.count = count;
   });

}