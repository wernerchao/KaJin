$(document).ready(function() {

                var thanks = $('.rating').text();
                var index;
                var ratingObj = {}
                $('.rating').next().append('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>'); // append the stars
                $('.formContainer').lightbox_me({
                    centered: true
                });
                
                //when the form is submitted, pass the data through ajax call
                $('.ratingForm').submit(function(event) { 
                    //something happens when the submit button is clicked
                    var testText = $(this).children("textarea").val();
                    console.log(ratingObj);
                    console.log(testText);
                    /*$.ajax({
                                type: 'POST',
                                url: 'add.php',
                                datatype: 'json',
                                success: function(data) {
                                    console.log("nothing yet");
                                }
                            });*/
                    event.preventDefault();
                    return false;
                });

                //when the stars are clicked, pass the score and the relevant text to the ratingObj
                $('.rating').next().on('click','i',function(){
                        if(thanks!='thanks') {
                            index = $(this).index()+1; //get the rating score, out of 5
                            var text = $(this).parent().prev().text(); //get the specific text of the rating selected
                            ratingObj[text]=index;
                            $(this).nextAll().removeClass('fa-star').addClass('fa-star-o');
                            $(this).prevAll().removeClass('fa-star-o').addClass('fa-star'); //the star(s) before the mouse click location
                            $(this).removeClass('fa-star-o').addClass('fa-star'); //the star on the mouse click location
                        }
                });
            });