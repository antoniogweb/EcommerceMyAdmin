<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="result-message"></div>
<div id="paypal-button-container"></div>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypalClientId;?>&currency=EUR&disable-funding=credit,mybank"></script>

<script>
window.paypal
  .Buttons({
    async createOrder() {
      try {
        const response = await fetch("<?php echo Url::getRoot();?>paypal/createorder/<?php echo $ordine["cart_uid"];?>/<?php echo $ordine["banca_token"];?>?paypal_csrf=<?php echo $paypalCsrf;?>", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          // use the "body" param to optionally pass additional order information
          // like product ids and quantities
          body: JSON.stringify({
            order: <?php echo $ordine["id_o"];?>
          }),
        });
        
        const orderData = await response.json();
        
        if (orderData.id != 0) {
          return orderData.id;
        } else {
			
          const errorMessage = orderData.description ? orderData.description : "";
          
          throw new Error(errorMessage);
        }
      } catch (error) {
        resultMessage(error.message, false);
      }
    },
    async onApprove(data, actions) {
      try {
        const response = await fetch("<?php echo Url::getRoot();?>paypal/captureorder/<?php echo $ordine["cart_uid"];?>/<?php echo $ordine["banca_token"];?>?paypal_csrf=<?php echo $paypalCsrf;?>", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
        });
        
        const orderData = await response.json();
        // Three cases to handle:
        //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
        //   (2) Other non-recoverable errors -> Show a failure message
        //   (3) Successful transaction -> Show confirmation or thank you message
        
        const errorDetail = orderData?.details?.[0];
        
        if (errorDetail?.issue === "INSTRUMENT_DECLINED") {
          // (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
          // recoverable state, per https://developer.paypal.com/docs/checkout/standard/customize/handle-funding-failures/
          return actions.restart();
        } else if (errorDetail) {
          // (2) Other non-recoverable errors -> Show a failure message
          throw new Error("<?php echo gtext("Errore nel pagamento, si prega di contattare il negozio.");?>");
        } else if (!orderData.purchase_units) {
          throw new Error("<?php echo gtext("Errore nel pagamento, si prega di contattare il negozio.");?>");
        } else {
			resultMessage("<?php echo gtext("Transazione avvenuta con successo");?>", true);
			location.href = "<?php echo Url::getRoot()."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"];?>?n=Y&echo_result";
        }
      } catch (error) {
//         console.error(error);
        resultMessage(
         error.message,false
        );
      }
    },
  })
  .render("#paypal-button-container");
  
// Example function to show a result to the user. Your site's UI library can be used instead.
function resultMessage(message, result) {
	const container = document.querySelector("#result-message");
	
	if (result)
		container.innerHTML = "<div class='<?php echo v("alert_success_class");?>'>" + message + "</div>";
	else
		container.innerHTML = "<div class='<?php echo v("alert_error_class");?>'>" + message + "</div>";
}
</script>
