# php-steem-tools
Various tools and scripts written in PHP for exploring the STEEM blockchain.

## Steem Rate: Interest Rate Calculator for Steem Power

A quick little script I put together for looking at the Steem Power Interest rate and how it changes over time.

To run it:

```
php steem_rate.php <username>
```
Where <username> is the Steem username you want to look into. For more debugging output, include a `true` parmeter:

```
php steem_rate.php <username> true
```
Example:

```
➜  php-steem-tools git:(master) ✗ php steem_rate.php dantheman true
Getting exchange rate at 2016-07-12T11:55:50-05:00...
   Rate: 1 SP = 1M VESTS = 211.18176472117
Getting VESTS balance for dantheman via Piston...
  VESTS: 5916182088.009379...
 1468342552: Starting Steem Power balance: 1249389.7737576
Sleeping 1 minute...
.
Getting exchange rate at 2016-07-12T11:56:52-05:00...
   Rate: 1 SP = 1M VESTS = 211.18302197611
Getting VESTS balance for dantheman via Piston...
  VESTS: 5916182088.009379...
 1468342614: Ending Steem Power balance: 1249397.2119068
--------------------------------
Interest Rate Per Hour: 0.0357%
Steem Power Per Hour: 446.28894975409
Interest Rate Per Week: 5.9976%
Steem Power Per Week: 74976.543558687
```

Note: If any other activity is going on within the account during the time you run the test, you won't get accurate results reflecting *just* the changes due to interest rates.

Requirements:

PHP with Curl  
[Piston](http://piston.readthedocs.io/en/develop/index.html)
