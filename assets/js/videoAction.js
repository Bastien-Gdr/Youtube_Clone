function likeVideo(button,videoId){

    // Nous permet d'envoyer les données via la méthode post
    $.post("assets/ajax/likeVideo.php",{videoId:videoId})

    // Quand l'appel à Ajax est terminé, on fait...
    .done(function(data){ // data représente le contenu de la page likeVideo.php

        var likeBtn = $(button);
        var dislikeBtn = $(button).siblings(".dislikeBtn");

        likeBtn.addClass("active");
        dislikeBtn.removeClass("active");

        var result = JSON.parse(data);
        updateLikesValue(likeBtn.find(".text"),result.likes);
        updateLikesValue(dislikeBtn.find(".text"),result.dislikes);

        // Modification du bouton

        if(result.likes < 0){
            // On enelève la classe 'active'
            likeBtn.removeClass("active")

            // On change l'image
            likeBtn.find("img:first").attr("src","assets/images/icons/thumb_up.png")
        }else{
            likeBtn.find("img:first").attr("src","assets/images/icons/thumb-up-active.png")
        }

        dislikeBtn.find("img:first").attr("src","assets/images/icons/thumb_down.png")

    });
}

function updateLikesValue(element,number){
    var likesCountValue = element.text() || 0;
    element.text(parseInt(likesCountValue) + parseInt(number));

}

function dislikeVideo(button,videoId){

    // Nous permet d'envoyer les données via la méthode post
    $.post("assets/ajax/dislikeVideo.php",{videoId:videoId})

    // Quand l'appel à Ajax est terminé, on fait...
    .done(function(data){ // data représente le contenu de la page likeVideo.php

        var dislikeBtn = $(button);
        var likeBtn = $(button).siblings(".likeBtn");

        dislikeBtn.addClass("active");
        likeBtn.removeClass("active");

        var result = JSON.parse(data);
        updateDislikesValue(likeBtn.find(".text"),result.likes);
        updateDislikesValue(dislikeBtn.find(".text"),result.dislikes);

        // Modification du bouton

        if(result.dislikes < 0){
            // On enelève la classe 'active'
            dislikeBtn.removeClass("active")

            // On change l'image
            dislikeBtn.find("img:first").attr("src","assets/images/icons/thumb_down.png")
        }else{
            dislikeBtn.find("img:first").attr("src","assets/images/icons/thumb-down-active.png")
        }

        likeBtn.find("img:first").attr("src","assets/images/icons/thumb_up.png")

    });

    function updateDislikesValue(element,number){
        var dislikesCountValue = element.text() || 0;
        element.text(parseInt(dislikesCountValue) + parseInt(number));
    
    }
}