export default function () {
   document.body.addEventListener('added_to_cart', function () {
      var count = document.querySelector('.js-cart-count').textContent;
      document.querySelector('.js-cart-link').dataset.count = count;
   });

}