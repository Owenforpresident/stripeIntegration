<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Stripe Checkout Sample</title>
    <meta name="description" content="A demo of Stripe Payment Intents" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <!-- Load Stripe.js on your website. -->
    <script src="https://js.stripe.com/v3/"></script>
    <!-- jQuery library -->
    
  </head>

  <body>
<div class= "container">        

  <div class="card align-items-center mt-4"  >

  <div class="card-body">
  <div>
            <h1>Example heading</h1>
          </div>
          <div class="">
            <button class="btn btn-primary" id="subtract" disabled>
              -
            </button>

            <input type="number" id="quantity-input" min="1" value="1" />

            <button class="btn btn-primary" id="add">
            +
            </button>
          </div>

          <p class="align-text-center">Quantity</p>

          <button type= "button" class= "btn btn-success" id="checkoutButton">
            Buy for $<span id="total">25</span>.00
          </button>

        </section>

        <div id="error-message"></div>
      </div>
    </div>
        
</div> 
  </div>
</div>

</div> 

    <script>

      var PUBLISHABLE_KEY = "pk_test_MqdNg8rEMqnJYIn9x3fk0MXg00aAYP5KwK";
      var DOMAIN = window.location.origin;
      var PRICE_ID = "price_1GptnEFoloIWBemsQIXCAWaG";


      var MIN_PHOTOS = 1;
      var MAX_PHOTOS = 15;

      var stripe = Stripe(PUBLISHABLE_KEY);

      var basicPhotoButton = document.getElementById("checkoutButton");
      document
        .getElementById("quantity-input")
        .addEventListener("change", function(evt) {
          // Ensure customers only buy between 1 and 10 photos
          if (evt.target.value < MIN_PHOTOS) {
            evt.target.value = MIN_PHOTOS;
          }
          if (evt.target.value > MAX_PHOTOS) {
            evt.target.value = MAX_PHOTOS;
          }
        });

      var updateQuantity = function(evt) {
        if (evt && evt.type === "keypress" && evt.keyCode !== 13) {
          return;
        }

        var isAdding = evt.target.id === "add";
        var inputEl = document.getElementById("quantity-input");
        var currentQuantity = parseInt(inputEl.value);

        document.getElementById("add").disabled = false;
        document.getElementById("subtract").disabled = false;

        var quantity = isAdding ? currentQuantity + 1 : currentQuantity - 1;

        inputEl.value = quantity;
        document.getElementById("total").textContent = quantity * 25;

        // Disable the button if the customers hits the max or min
        if (quantity === MIN_PHOTOS) {
          document.getElementById("subtract").disabled = true;
        }
        if (quantity === MAX_PHOTOS) {
          document.getElementById("add").disabled = true;
        }
      };

      Array.from(document.getElementsByClassName("btn btn-primary")).forEach(
        element => {
          element.addEventListener("click", updateQuantity);
        }
      );

      // Handle any errors from Checkout
      var handleResult = function(result) {
        if (result.error) {
          var displayError = document.getElementById("error-message");
          displayError.textContent = result.error.message;
        }
      };

      basicPhotoButton.addEventListener("click", function() {
        var quantity = parseInt(
          document.getElementById("quantity-input").value
        );

        // Make the call to Stripe.js to redirect to the checkout page
        // with the current quantity
        stripe
          .redirectToCheckout({
            mode: 'payment',
            lineItems: [{ price: PRICE_ID, quantity: quantity }],
            successUrl:
            DOMAIN + "/success.html?session_id={CHECKOUT_SESSION_ID}",
            cancelUrl: DOMAIN + "/canceled.html"
          })
          .then(handleResult);
      });
    </script>
  </body>
</html>