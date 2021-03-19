$(document).ready(function(){

$(document).on('click','.btn-like',function(){

    let did=$(this).data('id');
    let dname=$(this).data('name');
    
    $.ajax({
        url:"http://localhost/pictureshare/AjaxRequests/likes",
        type:"POST",
        data:{picid:did,
               typeoflike:dname},
        success:function($res){

            $cardbody='.card-likebar-'+did;
           $($cardbody).html($res); 
        }

    })

});


$(document).on('click','.btn-showHallOfFame',function(){
    $('.pageselector').hide();
    console.log('asdasd');
    $.ajax({
        url:"http://localhost/pictureshare/AjaxRequests/hallOfFame",
        type:'post',
        success:function(res){
           $('.cardholder').html(res);
        }
    })
});


/*
$(document).on('click','.btn-showAllPictures',function(){


   $.ajax({
        url:"http://localhost/pictureshare/AjaxRequests/loadAllPictures",
        type:'post',
        success:function(res){
           $('.cardholder').html(res);
        }
    })

})
*/





});