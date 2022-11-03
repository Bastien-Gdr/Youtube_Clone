$(document).ready(function(){
    $(".navShowHide").on("click",function(){

        //simple
        //$('#sidenavContainer').toggle();

        // + complet

        var main = $("#mainSectionContainer");
        var nav = $("#sideNavContainer");

        if(main.hasClass("leftPadding")){
            nav.hide();
        }else{
            nav.show();
        }

        main.toggleClass("leftPadding");
    });
})

    function notSignedIn(){
        alert ("Vous devez vous connecter pour effectuer cette action");
    }
