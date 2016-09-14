'use strict';
(function(){
    var update = function(event){
        event.preventDefault();
        var self=this;
        var ask=$(this).find('input[name="ask"]').val();
        var qid=$(this).find('#q_Id').val();
        var cid=$(this).find('#c_Id').val();
        if(/^\s*$/.test(ask))
            return;
        $.ajax({
            url:'./ajax_api_add_reply.php',//real
            type:'POST',
            data:{
                ask:ask,
                q_Id:qid,
                c_Id:cid
            },
            success:function(data){
                var data1=JSON.parse(data);
                if(data1['error']==1)
                    return ;
                $(self).find('input[name="ask"]').val('');
                if(qid==0){
                    $(self).parent().parent().after(
                            '<div class="qa">'+
                                '<div class="small-qa">'+
                                    '<div class="photo"></div>'+
                                    '<div class="info">'+
                                    '<h5 class="name">'+data1['user_name']+'</h5>'+
                                        '<p class="content">'+ask+'</p>'+
                                        '<button class="reply-btn" style="background:transparent;color:#67C4CB;border:none;font-size:14px;display:block;padding:0;">回覆</button>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="block-ask reply">'+
                                    '<div class="photo"></div>'+
                                    '<form class="reply-form">'+
                                        '<input type="text" placeholder="留言……" name="ask">'+
                                        '<input type="hidden" id="q_Id" name="q_Id" value='+data1['qid']+'>'+
                                        '<input type="hidden" id="c_Id" name="c_Id" value='+data1['cid']+'>'+
                                    '</form>'+
                                '</div>'+
                            '</div>');
                }
                else{
                    $(self).parent().before(
                            '<div class="small-qa reply">'+
                                '<div class="photo"></div>'+
                                '<div class="info">'+
                                    '<h5 class="name">'+data1['user_name']+'</h5>'+
                                    '<p class="content">'+ask+'</p>'+
                                '</div>'+
                            '</div>');
                }
            }
        });
    };
    $('#qa-big-block').delegate('.reply-form','submit',update);
    $('#qa-big-block').delegate('.reply-btn','click',function(){
        $(this).parent().parent().parent().find('.block-ask.reply').addClass('active');
    });
})(window);
