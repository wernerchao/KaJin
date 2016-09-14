<?php

include 'api/lib/Stripe.php';

include 'api/lib/Util/Util.php';
include 'api/lib/Util/Set.php';
include 'api/lib/Util/RequestOptions.php';
include 'api/lib/JsonSerializable.php';
include 'api/lib/StripeObject.php';

include 'api/lib/ApiResource.php';
include 'api/lib/ExternalAccount.php';
include 'api/lib/Card.php';

include 'api/lib/Collection.php';

include 'api/lib/HttpClient/ClientInterface.php';
include 'api/lib/HttpClient/CurlClient.php';

include 'api/lib/Error/Base.php';
include 'api/lib/Error/Api.php';
include 'api/lib/Error/ApiConnection.php';
include 'api/lib/Error/Authentication.php';
include 'api/lib/Error/Card.php';
include 'api/lib/Error/InvalidRequest.php';
include 'api/lib/Error/RateLimit.php';
//
include 'api/lib/ApiResponse.php';
include 'api/lib/ApiRequestor.php';

//include("api/lib/Account.php");
//include("api/lib/SingletonApiResource.php");

//include("api/lib/ApplicationFee.php");
//include("api/lib/ApplicationFeeRefund.php");
include 'api/lib/AttachedObject.php';
//include("api/lib/Balance.php");
//include("api/lib/BalanceTransaction.php");
//include("api/lib/BankAccount.php");
//include("api/lib/BitcoinReceiver.php");
//include("api/lib/BitcoinTransaction.php");
//
include 'api/lib/Charge.php';
//
//include("api/lib/Coupon.php");
//include("api/lib/Customer.php");
//include("api/lib/Dispute.php");

//include("api/lib/Event.php");

include 'api/lib/FileUpload.php';
include 'api/lib/Invoice.php';
include 'api/lib/InvoiceItem.php';

include 'api/lib/Order.php';
include 'api/lib/Plan.php';
include 'api/lib/Product.php';
include 'api/lib/Recipient.php';
include 'api/lib/Refund.php';

include 'api/lib/SKU.php';

include 'api/lib/Subscription.php';
include 'api/lib/Token.php';
include 'api/lib/Transfer.php';
include 'api/lib/TransferReversal.php';

include 'api/lib/AlipayAccount.php';

//include("api/lib/Stripe.php");
//include("api/lib/Charge.php");
// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey($system['stripe']['secret']);
