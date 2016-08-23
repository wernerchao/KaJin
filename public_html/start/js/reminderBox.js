function() reminderBox {
     var i=0;
     var ID;
        
     function myFunc() {
        $(".word").fadeIn();
        $(".word").fadeOut("slow");
        i++;
        if (i==3) {clearInterval(ID);}
        }
}