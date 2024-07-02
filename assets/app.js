import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

require('bootstrap');

// loads the jquery package from node_modules
import $ from 'jquery';



$(document).ready(function () {

/*     $("#filter").change(function () {

        // Fonction pour effectuer la requête asynchrone
        async function fetchData(filter) {
            try {
                // Construit l'URL avec le filtre
                const url = `/event/filter/${filter}`;

                // Exécute la requête asynchrone
                const response = await fetch(url, {
                    method: 'GET', // Méthode HTTP
                    headers: {
                        'Content-Type': 'application/json', // Type de contenu attendu de la réponse
                    },
                });

                // Vérifie si la requête a réussi
                if (!response.ok) {
                    throw new Error(`Erreur: ${response.status}`); // Lance une exception si la réponse est une erreur
                }

                // Extrait les données JSON de la réponse
                const data = await response.json();
                console.log("data");
                console.log(data);

                let listEvents = "";

                for(let i = 0; i < data.length; i++) {

                    listEvents += 
                    '<div class="card col-md-4 m-4" style="width: 18rem;">'
                        +'<img class="h-50" src="/uploads/event/'+ data[i].picture +'" alt="'+ data[i].name +'" name="'+ data[i].name +'">'
                        +'<div class="card-body">'
                            +'<h5 class="card-title">' + data[i].name + '</h5>'
                            +'<p>Du : '+ data[i].startAt +'</p>'
                            +'<p>Au : '+ data[i].endAt +'</p>'
                            +'<p>A : '+ data[i].city +' </p>'
                            +'<p class="card-text">'+ data[i].price +' €</p>'

                        +'<div class="row">'

                            +'<a class="btn bouton " href="/event/14">Voir</a>'
                            +'<a class="btn bouton mt-2" href="/event/14/edit">Modifier</a>'
                            +'<a class="btn btn-danger mt-2" href="/event/14">Supprimer</a>'    
                        +'</div>'
                        +'</div>'
                    +'</div>';

                }

                console.log(listEvents);

                $("#list-events").html(
                    listEvents
                );


            } catch (error) {
                console.error("Il y a eu une erreur avec la requête fetch: ", error.message);
            }
        }

        let filter = $(this).val();
        // Appel de la fonction avec le filtre désiré, par exemple 'monFiltre'
        fetchData(filter);

    }); */

});

