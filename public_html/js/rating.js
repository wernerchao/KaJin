$(document).ready(function() {
    var score = 5; //total score out of 5，請從database更改這個數字

    for (i=0;i<5-score;i++) { //fill in empty stars 
        $('.rating').next().append('<i class="fa fa-star-o"></i>');
    }
    for (i=0;i<score;i++) { //fill in solid stars 
        $('.rating').next().append('<i class="fa fa-star"></i>');
    }

            });