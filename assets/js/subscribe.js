function subscribe(userTo,userFrom,button){
    if(userTo == userFrom){
        alert ("Vous ne pouvez pas vous abonnez à votre propre chaîne");
        return;
    }

    //  appel Ajax

    $.post("assets/ajax/subscribe.php",{userTo:userTo,userFrom:userFrom})
    .done(function(data){

            if(data != null){
                $(button).toggleClass("subscribe unsubscribe");
                var textBtn = $(button).hasClass("subscribe") ? "S'abonner" : "Abonné";

                $(button).text(textBtn + " " + data)
            }
    });
}