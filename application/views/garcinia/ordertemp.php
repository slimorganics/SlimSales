<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <title>Sample Order Page</title>

  <!-- The required Stripe lib -->
  <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<!--Social Share Javascript-->
<script type='text/javascript' src='http://connect.facebook.net/en_US/all.js?ver=3.6#xfbml=1'></script>
<script type='text/javascript' src='https://platform.twitter.com/widgets.js?ver=3.6'></script>
<script type="text/javascript">

//Pinterest JS
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));

//Social Callbacks
  FB.init();
  $(document).ready(function() {

    $('#pin-button').click(function(){
      window.setTimeout(function(){
        shareDiscount("PN");
      }, 3000);
    });

    FB.Event.subscribe("edge.create", function(href, widget) { 
      shareDiscount("FB");
    });


    twttr.ready(function (twttr) {
      twttr.events.bind("tweet", function(event) {
        alert('twit');
         shareDiscount("TW");
      });
    });

$('option').click(function(){
//  the thing needs to be switched
})

  });

  //Manage Share Discounts
  function shareDiscount(via){
    $('#socialUse').val(via);
    alert("you are saving $5");
  }
</script>


  <script type="text/javascript">
    //Stripe JS Needs

    // This identifies your website in the createToken call below
    Stripe.setPublishableKey('pk_test_0kdZI4wci31QHujnp25jBOum');

    var stripeResponseHandler = function(status, response) {
      var $form = $('#payment-form');

      if (response.error) {
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('button').prop('disabled', false);
      } else {
        // token contains id, last4, and card type
        var token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        // and re-submit
        $form.get(0).submit();
      }
    };

    jQuery(function($) {
      $('#payment-form').submit(function(e) {
        var $form = $(this);

        // Disable the submit button to prevent repeated clicks
        $form.find('button').prop('disabled', true);

        Stripe.createToken($form, stripeResponseHandler);

        // Prevent the form from submitting with the default action
        return false;
      });
    });
  </script>

</head>
<body>
  <h1>Skeleton Order Page</h1>


<form method="POST" class="form-inside-box" action="/index.php/order" method="POST" id="payment-form">
	First Name: <input name="FirstName" type="text" /><br />
	Last Name: <input name="LastName" type="text" /><br />
	Email: <input name="Email" type="text" /><br />
	Street Address: <input name="Street1" type="text" /><br />
	Street Address 2: <input name="Street2" type="text" /><br />
	City: <input name="City" type="text" /><br />
	State: <input name="State" type="text" /><br /><br />
	ZIP: <input name="Zip" type="text" /><br />
    Card Number: <input type="text" size="20" data-stripe="number"><br />
    CVC : <input type="text" size="4" data-stripe="cvc"><br />
	Expiration (MM/YYYY):  <input type="text" size="2" data-stripe="exp-month"> <input type="text" size="4" data-stripe="exp-year">
	<input name="socialUse" id="socialUse" type="hidden" value="0" />
    <input type="hidden" name="productID" value="1" />
    <input type="hidden" name="redirectUrl" value="garcinia/thanks" />
    <br /><br />
    <button type="submit">Place Order</button>
 </form>

 <br />
 <br />
 <br />
 <br />
  <h4>Social Discount</h4>
  <p>LIKE or SHARE for $5 saving</p>
 <div>
  <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
  <div>
    <iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.1379634856.html#_=1379882286890&amp;count=vertical&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=http://www.google.com/&amp;size=m&amp;text= #testestest:&amp;url=http://www.google.com" class="twitter-share-button twitter-count-vertical" title="Twitter Tweet Button" data-twttr-rendered="true" style="width: 56px; height: 62px;"></iframe>
  </div>
  <div>
    <fb:like id="fbLikeButton" layout="box_count" href="http://www.fabthemes.com/torres/" show_faces="false" width="450" fb-xfbml-state="rendered" class="fb_edge_widget_with_comment fb_iframe_widget"><span style="height: 61px; width: 48px;"><iframe id="f23dced24" name="f47736258" scrolling="no" title="Like this content on Facebook." class="fb_ltr" src="http://www.facebook.com/plugins/like.php?api_key=&amp;channel_url=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D27%23cb%3Df394a6b88%26domain%3Dwww.fabthemes.com%26origin%3Dhttp%253A%252F%252Fwww.fabthemes.com%252Ff105d88048%26relation%3Dparent.parent&amp;colorscheme=light&amp;extended_social_context=false&amp;href=http%3A%2F%2Fwww.fabthemes.com%2Ftorres%2F&amp;layout=box_count&amp;locale=en_US&amp;node_type=link&amp;sdk=joey&amp;show_faces=false&amp;width=450" style="border: none; overflow: hidden; height: 61px; width: 48px;"></iframe></span></fb:like>
  </div>
  <div>
    <div id="pin-button">
      <a href="//www.pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="above"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>    </div>
    </div>
</body>
</html>